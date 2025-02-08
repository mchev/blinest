<?php

namespace App\Services\MusicProviders;

use App\Jobs\ProcessImportTrack;
use App\Models\Playlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class SoundcloudService
{
    protected string $clientId;

    protected string $clientSecret;

    protected string $accessToken;

    public function __construct()
    {
        $this->clientId = config('services.soundcloud.client_id');
        $this->clientSecret = config('services.soundcloud.client_secret');
        $this->accessToken = $this->getAccessToken();
    }

    protected function getAccessToken(): string
    {
        return Cache::remember('soundcloud_access_token', 3500, function () {
            try {
                $response = Http::post('https://api.soundcloud.com/oauth2/token', [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]);

                if (! $response->successful()) {
                    throw new \Exception('Failed to get access token: '.$response->body());
                }

                return $response->json()['access_token'];
            } catch (\Exception $e) {
                Log::error('Soundcloud authentication failed', [
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        });
    }

    public function searchTrack()
    {
        try {
            $term = Request::get('term');
            if (empty($term)) {
                return null;
            }

            $query = urlencode(trim($term));
            $response = Http::withToken($this->accessToken)
                ->get('https://api.soundcloud.com/tracks', [
                    'q' => $query,
                    'limit' => 10,
                    'linked_partitioning' => true,
                    'access' => 'playable', // Only return playable tracks
                ]);

            if (! $response->successful()) {
                throw new \Exception('Search failed: '.$response->body());
            }

            $data = $response->json();

            return isset($data['collection'])
                ? collect($data['collection'])
                    ->filter(fn ($track) => $track['access'] === 'playable')
                    ->map(fn ($track) => $this->formatTrack($track))
                : null;

        } catch (\Exception $e) {
            Log::error('Soundcloud search failed', [
                'term' => $term ?? null,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function findPlaylistById(string $id): object
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->get("https://api.soundcloud.com/playlists/{$id}");

            if (! $response->successful()) {
                throw new \Exception('Playlist not found: '.$response->body());
            }

            $playlist = $response->json();

            return (object) [
                'id' => $playlist['id'],
                'name' => $playlist['title'],
                'description' => $playlist['description'],
                'tracks_count' => count($playlist['tracks']),
                'image' => $playlist['artwork_url'],
                'tracks' => $playlist['tracks'],
            ];
        } catch (\Exception $e) {
            return (object) [
                'code' => 404,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function importPlaylist(Playlist $playlist, string $provider_playlist_id): int
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->get("https://api.soundcloud.com/playlists/{$provider_playlist_id}");

            if (! $response->successful()) {
                throw new \Exception('Failed to import playlist: '.$response->body());
            }

            $data = $response->json();
            $importedTracks = [];

            foreach ($data['tracks'] as $track) {
                if ($track['access'] === 'playable') {
                    $formattedTrack = $this->formatTrack($track);
                    $importedTracks[] = ProcessImportTrack::dispatch($playlist, $formattedTrack)
                        ->onQueue('imports')
                        ->delay(now()->addSeconds(count($importedTracks) * 0.5));
                }
            }

            return count(array_filter($importedTracks));
        } catch (\Exception $e) {
            Log::error('Soundcloud playlist import failed', [
                'playlist_id' => $playlist->id,
                'provider_playlist_id' => $provider_playlist_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    protected function formatTrack(array $track): object
    {
        return (object) [
            'provider' => 'soundcloud',
            'provider_id' => $track['id'],
            'provider_url' => $track['permalink_url'],
            'provider_popularity' => $track['playback_count'] ?? 0,
            'artist_name' => $track['user']['username'],
            'track_name' => $track['title'],
            'album_name' => null,
            'preview_url' => $track['stream_url'] ?? $this->getStreamUrl($track['id']),
            'release_date' => Carbon::parse($track['created_at'])->format('Y-m-d'),
            'artwork_url' => $this->getArtworkUrl($track),
        ];
    }

    protected function getStreamUrl(string $trackId): string
    {
        $response = Http::withToken($this->accessToken)
            ->get("https://api.soundcloud.com/tracks/{$trackId}/stream");

        return $response->json()['url'] ?? '';
    }

    protected function getArtworkUrl(array $track): ?string
    {
        return $track['artwork_url']
            ?? $track['user']['avatar_url']
            ?? null;
    }
}
