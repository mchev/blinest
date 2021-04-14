<?php

namespace App\Http\Controllers;

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

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function releases(Request $request)
    {

        $api = new \SpotifyWebAPI\SpotifyWebAPI();
        $api->setAccessToken($this->spotAuth());

        $releases = $api->getNewReleases([
            'country' => 'fr',
        ]);

        $releases = $releases->albums->items;

        return view('releases', compact('releases'));

    }

}