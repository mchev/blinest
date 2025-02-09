<?php

namespace App\Services\MusicProviders;

use App\Jobs\ProcessImportTrack;
use App\Models\Playlist;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use SpotifyWebAPI\Session as SpotifySession;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIException;

class SpotifyService
{
    protected SpotifyWebAPI $api;

    public function __construct()
    {
        try {
            $this->api = new SpotifyWebAPI;
            $session = new SpotifySession(
                config('services.spotify.client_id'),
                config('services.spotify.client_secret')
            );
            $session->requestCredentialsToken();
            $this->api->setAccessToken($session->getAccessToken());
        } catch (\Exception $e) {
            Log::error('Spotify API initialization failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function searchTrack(): ?Collection
    {
        try {
            $term = Request::get('term');
            if (empty($term)) {
                return collect([]);
            }

            $response = $this->api->search($term, ['track', 'artist'], [
                'market' => config('services.spotify.market', 'FR'),
                'limit' => 50,
            ]);

            $filteredTracks = collect($response->tracks->items)
                ->filter(function ($track) {
                    return $track->is_playable
                        && ! empty($track->preview_url)
                        && filter_var($track->preview_url, FILTER_VALIDATE_URL);
                });

            // If there are results but none with preview URLs, return an error
            if ($response->tracks->total > 0 && $filteredTracks->isEmpty()) {
                return collect([[
                    'error' => true,
                    'provider' => 'spotify',
                    'message' => 'Spotify : Les pistes trouvées ne sont pas disponibles en streaming',
                    'status_code' => 404,
                ]]);
            }

            return $filteredTracks->map(fn ($track) => $this->formatTrack($track));

        } catch (SpotifyWebAPIException $e) {
            Log::error('Spotify search failed', [
                'term' => Request::get('term'),
                'error' => $e->getMessage(),
                'context' => [
                    'market' => config('services.spotify.market', 'FR'),
                ],
            ]);

            return collect([[
                'error' => true,
                'provider' => 'spotify',
                'message' => 'Spotify : '.$e->getMessage(),
                'status_code' => $e->getCode(),
            ]]);
        }
    }

    public function importPlaylist(Playlist $playlist, string $provider_playlist_id): int
    {
        try {
            $first = true;
            $offset = 0;
            $importedTracks = [];
            $batchSize = 100;

            while ($offset !== null || $first) {
                $playlistTracks = $this->api->getPlaylistTracks($provider_playlist_id, [
                    'offset' => $offset,
                    'limit' => $batchSize,
                ]);

                $tracks = collect($playlistTracks->items)
                    ->filter(fn ($item) => ! empty($item->track?->id))
                    ->map(fn ($item) => $this->formatTrack($item->track));

                foreach ($tracks as $formattedTrack) {
                    $importedTracks[] = ProcessImportTrack::dispatch($playlist, $formattedTrack)
                        ->onQueue('imports')
                        ->delay(now()->addSeconds(count($importedTracks) * 0.5)); // Rate limiting
                }

                $offset = $playlistTracks->next ? ($offset + $batchSize) : null;
                $first = false;
            }

            return count(array_filter($importedTracks));
        } catch (SpotifyWebAPIException $e) {
            Log::error('Spotify playlist import failed', [
                'playlist_id' => $playlist->id,
                'provider_playlist_id' => $provider_playlist_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function findPlaylistById(string $id): object
    {
        try {
            $playlist = $this->api->getPlaylist($id);

            return (object) [
                'id' => $playlist->id,
                'name' => $playlist->name,
                'description' => $playlist->description,
                'tracks_count' => $playlist->tracks->total,
                'image' => $playlist?->images[0]?->url,
                'tracks' => $playlist->tracks,
            ];
        } catch (\SpotifyWebAPI\SpotifyWebAPIException $e) {
            return (object) [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }

    public function formatTrack($track)
    {
        return (object) [
            'provider' => 'spotify',
            'provider_id' => $track->id,
            'provider_url' => $track->external_urls->spotify,
            'provider_popularity' => $track->popularity,
            'artist_name' => $track->artists[0]->name,
            'track_name' => $track->name,
            'album_name' => $track->album->name,
            'preview_url' => $track->preview_url,
            'release_date' => Carbon::parse($track->album->release_date)->format('Y-m-d'),
            'artwork_url' => $track->album->images[2]->url, // 64*64px
        ];
    }
}
