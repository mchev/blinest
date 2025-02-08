<?php

namespace App\Services\MusicProviders;

use App\Jobs\ProcessImportTrack;
use App\Models\Playlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class YouTubeMusicService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.youtube.api_key');
    }

    public function searchTrack()
    {
        try {
            $term = Request::get('term');
            if (empty($term)) {
                return null;
            }

            $query = urlencode(trim($term));
            $response = Http::withHeaders([
                'Referer' => config('app.url'),
            ])->get('https://www.googleapis.com/youtube/v3/search', [
                'key' => $this->apiKey,
                'part' => 'snippet',
                'q' => $query.' music',
                'type' => 'video',
                'videoCategoryId' => '10', // Music category
                'maxResults' => 10,
                'videoEmbeddable' => true,
                'fields' => 'items(id/videoId,snippet/title,snippet/channelTitle,snippet/publishedAt,snippet/thumbnails)',
            ]);

            if (! $response->successful()) {
                throw new \Exception('Search failed: '.$response->body());
            }

            $data = $response->json();

            return isset($data['items'])
                ? collect($data['items'])
                    ->map(fn ($track) => $this->formatTrack($track))
                : null;

        } catch (\Exception $e) {
            Log::error('YouTube Music search failed', [
                'term' => $term ?? null,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function findPlaylistById(string $id): object
    {
        try {
            $response = Http::get('https://www.googleapis.com/youtube/v3/playlists', [
                'key' => $this->apiKey,
                'part' => 'snippet,contentDetails',
                'id' => $id,
            ]);

            if (! $response->successful()) {
                throw new \Exception('Playlist not found: '.$response->body());
            }

            $data = $response->json();
            $playlist = $data['items'][0] ?? null;

            if (! $playlist) {
                throw new \Exception('Playlist not found');
            }

            return (object) [
                'id' => $playlist['id'],
                'name' => $playlist['snippet']['title'],
                'description' => $playlist['snippet']['description'],
                'tracks_count' => $playlist['contentDetails']['itemCount'],
                'image' => $playlist['snippet']['thumbnails']['medium']['url'] ?? null,
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
            $importedTracks = [];
            $pageToken = null;

            do {
                $response = Http::get('https://www.googleapis.com/youtube/v3/playlistItems', [
                    'key' => $this->apiKey,
                    'part' => 'snippet',
                    'playlistId' => $provider_playlist_id,
                    'maxResults' => 50,
                    'pageToken' => $pageToken,
                ]);

                if (! $response->successful()) {
                    throw new \Exception('Failed to import playlist: '.$response->body());
                }

                $data = $response->json();

                foreach ($data['items'] as $item) {
                    $formattedTrack = $this->formatTrack($item);
                    $importedTracks[] = ProcessImportTrack::dispatch($playlist, $formattedTrack)
                        ->onQueue('imports')
                        ->delay(now()->addSeconds(count($importedTracks) * 0.5));
                }

                $pageToken = $data['nextPageToken'] ?? null;
            } while ($pageToken);

            return count(array_filter($importedTracks));
        } catch (\Exception $e) {
            Log::error('YouTube Music playlist import failed', [
                'playlist_id' => $playlist->id,
                'provider_playlist_id' => $provider_playlist_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    protected function formatTrack(array $track): object
    {
        // Extract artist and track name from video title
        $title = $track['snippet']['title'];
        $parts = explode(' - ', $title, 2);
        $artistName = count($parts) > 1 ? trim($parts[0]) : $track['snippet']['channelTitle'];
        $trackName = count($parts) > 1 ? trim($parts[1]) : $title;

        $videoId = $track['id']['videoId'] ?? $track['snippet']['resourceId']['videoId'];

        return (object) [
            'provider' => 'youtube',
            'provider_id' => $videoId,
            'provider_url' => 'https://www.youtube.com/watch?v='.$videoId,
            'provider_popularity' => 0, // YouTube API doesn't provide view count in basic search
            'artist_name' => $artistName,
            'track_name' => $trackName,
            'album_name' => null,
            'preview_url' => $videoId,
            'release_date' => Carbon::parse($track['snippet']['publishedAt'])->format('Y-m-d'),
            'artwork_url' => $track['snippet']['thumbnails']['default']['url'] ?? null,
        ];
    }
}
