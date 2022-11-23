<?php

namespace App\Services\MusicProviders;

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
        $term = e(Request::get('term'));

        $response = $this->api->search($term, ['track','artist'], ['include_external' => 'audio', 'market' => 'FR']);
        $results = collect($response->tracks->items);

        $tracks = ($results) ? $results->where('is_playable')->where('preview_url')->map(function ($track) {
            return [
                'provider' => 'spotify',
                'provider_id' => $track->id,
                'provider_url' => $track->external_urls->spotify,
                'artist_name' => $track->artists[0]->name,
                'track_name' => $track->name,
                'album_name' => $track->album->name,
                'preview_url' => $track->preview_url,
                'release_date' => Carbon::parse($track->album->release_date)->format('Y-m-d'),
                'artwork_url' => $track->album->images[2]->url, // 64*64px
            ];
        }) : null;

        return $tracks;
    }

    public function addPlaylist(Request $request)
    {
        $results = $this->api->getPlaylist($request->q);

        return response()->json($results);
    }

    public function storePlaylist(Request $request)
    {
        $game = Game::find($request->params['game_id']);

        if (auth()->user()->isModerator($game) || auth()->user()->id == $game->user_id) {
            $tracks = [];
            $offset = 0;
            $run = true;
            $provider = 'spotify';
            $user_id = auth()->user()->id;
            $game_id = $game->id;

            $api = new \SpotifyWebAPI\SpotifyWebAPI();
            $api->setAccessToken($this->spotAuth());

            while ($run) {
                $playlistTracks = $api->getPlaylistTracks($request->playlist_id, ['offset' => $offset]);

                foreach ($playlistTracks->items as $track) {
                    if ($track->track->preview_url) {
                        $provider_item_id = $track->track->id;
                        $artist_name = $track->track->artists[0]->name;
                        $track_name = $track->track->name;
                        $artwork_url = $track->track->album->images[1]->url;
                        $preview_url = $track->track->preview_url;

                        StoreTrack::dispatch($user_id, $game_id, $provider_item_id, $provider, $artist_name, $track_name, $artwork_url, $preview_url);
                    }
                }

                if ($playlistTracks->next) {
                    $offset += 100;
                } else {
                    $run = false;

                    return response()->json(['success' =>  true]);
                }
            }

            return response()->json(['success' =>  false]);
        } else {
            return response()->json('Utilisateur non autorisé.');
        }
    }
}
