<?php

namespace App\Services\MusicProviders;

use App\Jobs\ProcessImportTrack;
use App\Models\Playlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class SpotifyService
{
    protected $api;

    public function __construct()
    {
        $this->api = new \SpotifyWebAPI\SpotifyWebAPI();
        $session = new \SpotifyWebAPI\Session(config('services.spotify.client_id'), config('services.spotify.client_secret'));
        $session->requestCredentialsToken();
        $this->api->setAccessToken($session->getAccessToken());
    }

    public function searchTrack()
    {
        $response = $this->api->search(Request::get('term'), ['track', 'artist'], ['include_external' => 'audio', 'market' => 'FR']);
        $results = collect($response->tracks->items);

        return ($results)
            ? $results->where('is_playable')->where('preview_url')->map(function ($track) {
                return $this->formatTrack($track);
            })
            : null;
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

    public function importPlaylist(Playlist $playlist, $provider_playlist_id): int
    {
        $first = true;
        $next = 0;
        $importedTracks = [];

        while ($next || $first) {
            $playlistTracks = $this->api->getPlaylistTracks($provider_playlist_id, ['offset' => $next]);
            foreach ($playlistTracks->items as $item) {
                if($item->track->id) {
                    $formatedTrack = $this->formatTrack($item->track);
                    $importedTracks[] = ProcessImportTrack::dispatch($playlist, $formatedTrack)->onQueue('imports');
                }
            }
            $next = $playlistTracks->next ? $next += 100 : null;
            $first = false;
        }

        return count(array_filter($importedTracks));
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
