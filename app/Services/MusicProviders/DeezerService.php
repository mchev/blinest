<?php

namespace App\Services\MusicProviders;

use App\Jobs\ProcessImportTrack;
use App\Models\Playlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class DeezerService
{
    public function searchTrack()
    {
        try {
            $term = Request::get('term');
            if (empty($term)) {
                return collect([]);
            }

            $query = urlencode(trim($term));
            $url = 'https://api.deezer.com/search/track?q='.$query.'&strict=on&limit=25';
            $response = Http::get($url);

            if (! $response->successful()) {
                return collect([[
                    'error' => true,
                    'provider' => 'deezer',
                    'message' => 'Deezer : '.$response->body(),
                    'status_code' => $response->status(),
                ]]);
            }

            $collection = $response->collect();
            $results = $collection['data'] ?? null;

            return $results ? collect($results)
                ->where('readable', true)
                ->map(fn ($track) => $this->formatTrack($track)) : collect([]);

        } catch (\Exception $e) {
            Log::error('Deezer search failed', [
                'term' => $term ?? null,
                'error' => $e->getMessage(),
            ]);

            return collect([[
                'error' => true,
                'provider' => 'deezer',
                'message' => __('Deezer service is temporarily unavailable'),
                'status_code' => 500,
            ]]);
        }
    }

    public function getLiveTrackPreview($id)
    {
        // Cache the preview URL for 5 minutes to avoid excessive API calls
        // Even though URLs expire, caching reduces load and most URLs are valid for longer
        $cacheKey = "deezer_preview_url:{$id}";

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 300, function () use ($id) {
            try {
                $url = 'https://api.deezer.com/track/'.$id;
                // Retry up to 2 times with 100ms delay, timeout of 2 seconds per attempt
                $response = Http::retry(2, 100)->timeout(2)->get($url);

                if (! $response->successful()) {
                    Log::warning('Deezer getLiveTrackPreview failed', [
                        'track_id' => $id,
                        'status' => $response->status(),
                    ]);

                    return null;
                }

                $track = $response->collect();

                if (! ($track['readable'] ?? false)) {
                    Log::debug('Deezer track not readable', [
                        'track_id' => $id,
                    ]);

                    return null;
                }

                $previewUrl = $track['preview'] ?? null;

                if (! $previewUrl) {
                    Log::debug('Deezer track has no preview URL', [
                        'track_id' => $id,
                    ]);

                    return null;
                }

                // Verify URL length fits in database (should be ~266 chars with hdnea)
                if (strlen($previewUrl) > 2048) {
                    Log::warning('Deezer preview URL too long', [
                        'track_id' => $id,
                        'url_length' => strlen($previewUrl),
                    ]);
                }

                return $previewUrl;

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // Handle timeout/connection errors specifically
                Log::warning('Deezer getLiveTrackPreview connection error', [
                    'track_id' => $id,
                    'error' => $e->getMessage(),
                ]);

                return null;
            } catch (\Exception $e) {
                Log::error('Deezer getLiveTrackPreview exception', [
                    'track_id' => $id,
                    'error' => $e->getMessage(),
                ]);

                return null;
            }
        });
    }

    public function getReleaseDate($album)
    {
        $url = 'https://api.deezer.com/album/'.$album;
        $collection = Http::get($url)->collect();

        return Carbon::parse($collection['release_date'])->format('Y-m-d');
    }

    public function findPlaylistById(string $id): object
    {
        $url = 'https://api.deezer.com/playlist/'.$id;
        $playlist = Http::get($url)->object();

        if (isset($playlist->error)) {
            return (object) [
                'code' => $playlist->error->code,
                'message' => $playlist->error->message,
            ];
        }

        return (object) [
            'id' => $playlist->id,
            'name' => $playlist->title,
            'description' => $playlist->description,
            'tracks_count' => $playlist->nb_tracks,
            'image' => $playlist->picture_medium,
            'tracks' => $playlist->tracks->data,
        ];
    }

    public function importPlaylist(Playlist $playlist, $provider_playlist_id): int
    {
        try {
            $url = 'https://api.deezer.com/playlist/'.$provider_playlist_id.'/tracks';
            $importedTracks = [];
            $limit = 100;

            while ($url) {
                $response = Http::get($url.'?limit='.$limit);

                if (! $response->successful()) {
                    Log::error('Deezer playlist import failed', [
                        'playlist_id' => $playlist->id,
                        'provider_playlist_id' => $provider_playlist_id,
                        'error' => $response->body(),
                        'status' => $response->status(),
                    ]);
                    break;
                }

                $tracks = $response->json();
                foreach ($tracks['data'] as $track) {
                    // Use formatTrack with forStorage=true to get placeholder for storage
                    $formatedTrack = $this->formatTrack($track, true);
                    $importedTracks[] = ProcessImportTrack::dispatch($playlist, $formatedTrack)
                        ->onQueue('imports')
                        ->delay(now()->addSeconds(count($importedTracks) * 0.5)); // Rate limiting
                }
                $url = $tracks['next'] ?? null;
            }

            return count(array_filter($importedTracks));

        } catch (\Exception $e) {
            Log::error('Deezer playlist import failed', [
                'playlist_id' => $playlist->id,
                'provider_playlist_id' => $provider_playlist_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function formatTrack(array $track, bool $forStorage = false): object
    {
        // For search results: return real URL for immediate preview
        // For storage: return placeholder to avoid storing expiring URLs
        $previewUrl = $forStorage
            ? 'deezer://'.$track['id'] // Placeholder for storage - will be fetched live via getLiveTrackPreview()
            : ($track['preview'] ?? null); // Real URL for search results preview

        return (object) [
            'provider' => 'deezer',
            'provider_id' => $track['id'],
            'provider_url' => $track['link'],
            'provider_popularity' => $track['rank'],
            'artist_name' => $track['artist']['name'],
            'track_name' => $track['title'],
            'album_name' => $track['album']['title'],
            'preview_url' => $previewUrl,
            'release_date' => null, // $this->getReleaseDate($track['album']['id']), TOO SLOW!!
            'artwork_url' => $track['album']['cover_medium'],
        ];
    }
}
