<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Track;
use App\Game;
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

        $api = new \SpotifyWebAPI\SpotifyWebAPI();
        $api->setAccessToken($this->spotAuth());

        $results = $api->getPlaylist($request->q);

        return response()->json($results);

    }


    public function storePlaylist(Request $request)
    {

        $game = Game::find($request->params['game_id']);

        if (auth()->user()->isModerator($game) ||auth()->user()->id == $game->user_id) {

            try {

                $tracks = [];


                foreach ($request->tracks as $track) {

                    if($track['track']['preview_url']) {

                        $item = new Track([
                            'user_id' => Auth::user()->id,
                            'game_id' => $request->params['game_id'],
                            'provider_item_id' => $track['track']['id'],
                            'provider' => $request->params['provider'],
                            'artist_name' => $track['track']['artists'][0]['name'],
                            'track_name' => $track['track']['name'],
                            'artwork_url' => $track['track']['album']['images'][1]['url'],
                            'preview_url' => $track['track']['preview_url'],
                        ]);


                        $item->save();

                        $tracks[] = $item;

                    }

                }

                return response()->json($tracks);

            }
            catch (Exception $e) {
                return response()->json($e->getMessage());
            }

            return response()->json($json);

        } else {

            return response()->json("Utilisateur non autorisé.");
        }

    }

}