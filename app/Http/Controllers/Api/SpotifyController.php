<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Track;
use App\Game;
use App\Jobs\StoreTrack;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpotifyController extends Controller
{


    public function spotAuth()
    {

        $session = new \SpotifyWebAPI\Session( config('services.spotify.client_id'), config('services.spotify.client_secret') );
        $session->requestCredentialsToken();
        $accessToken = $session->getAccessToken();

        return $accessToken;

    }


    public function search(Request $request)
    {



        if (isset($request->q) && $request->q !== '') {

            $api = new \SpotifyWebAPI\SpotifyWebAPI();
            $api->setAccessToken($this->spotAuth());

            $results = $api->search($request->q, 'track', ['market' => 'FR']);

            return response()->json($results);

        }

    }


    public function addPlaylist(Request $request)
    {

        // TODO : Parse pagination to store more than 100
        
        $api = new \SpotifyWebAPI\SpotifyWebAPI();
        $api->setAccessToken($this->spotAuth());

        $results = $api->getPlaylist($request->q);

        return response()->json($results);


    }


    public function storePlaylist(Request $request)
    {

        $game = Game::find($request->params['game_id']);

        if (auth()->user()->isModerator($game) || auth()->user()->id == $game->user_id) {


            $tracks = [];
            $offset = 0;
            $run = true;
            $provider = "spotify";
            $user_id = auth()->user()->id;
            $game_id = $game->id;

            $api = new \SpotifyWebAPI\SpotifyWebAPI();
            $api->setAccessToken($this->spotAuth());

            while($run) {

                $playlistTracks = $api->getPlaylistTracks($request->playlist_id, ['offset' => $offset]);

                foreach ($playlistTracks->items as $track) {

                    if($track->track->preview_url) {

                        $provider_item_id = $track->track->id;
                        $artist_name = $track->track->artists[0]->name;
                        $track_name = $track->track->name;
                        $artwork_url = $track->track->album->images[1]->url;
                        $preview_url = $track->track->preview_url;

                        StoreTrack::dispatch($user_id, $game_id, $provider_item_id, $provider, $artist_name, $track_name, $artwork_url, $preview_url);

                    }

                }


                if($playlistTracks->next) {
                    $offset+= 100;
                } else {
                    $run = false;
                    return response()->json(['success' =>  true]);
                }

            }

            return response()->json(['success' =>  false]);

        } else {

            return response()->json("Utilisateur non autorisé.");
        }

    }

}