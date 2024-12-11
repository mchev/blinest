<?php

namespace App\Services\MusicProviders;

use App\Jobs\ProcessImportTrack;
use App\Models\Playlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class SoundcloudMusicService
{
    protected string $clientId;

    public function __construct()
    {
        $this->clientId = config('services.soundcloud.client_id');
    }

    public function searchTrack()
    {
        try {
            $term = Request::get('term');

            $query = filter_var($term, FILTER_SANITIZE_STRING);
            $query = trim($query);
            $query = str_replace(' ', '+', $query);

            $url = 'https://api.soundcloud.com/tracks';

            $response = Http::get($url, [
                'client_id' => $this->clientId,
                'q' => $query,
                'limit' => 50,
                'linked_partitioning' => 1,
            ])->collect();

            return isset($response['collection'])
                ? collect($response['collection'])
                    ->filter(fn ($track) => ! empty($track['stream_url']))
                    ->map(fn ($track) => $this->formatTrack($track))
                : null;

        } catch (\Exception $e) {
            Log::error('Soundcloud search failed', [
                'term' => $term,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function findPlaylistById(string $id): object
    {
        try {
            $url = "https://api.soundcloud.com/playlists/{$id}";
            $playlist = Http::get($url, ['client_id' => $this->clientId])->object();

            return (object) [
                'id' => $playlist->id,
                'name' => $playlist->title,
                'description' => $playlist->description,
                'tracks_count' => count($playlist->tracks),
                'image' => $playlist->artwork_url,
                'tracks' => $playlist->tracks,
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
            $url = "https://api.soundcloud.com/playlists/{$provider_playlist_id}";
            $response = Http::get($url, ['client_id' => $this->clientId])->json();

            $importedTracks = [];

            foreach ($response['tracks'] as $track) {
                if (! empty($track['stream_url'])) {
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
            'album_name' => null, // SoundCloud doesn't have album concept
            'preview_url' => $track['stream_url']."?client_id={$this->clientId}",
            'release_date' => Carbon::parse($track['created_at'])->format('Y-m-d'),
            'artwork_url' => $track['artwork_url'] ?? $track['user']['avatar_url'],
        ];
    }
}
