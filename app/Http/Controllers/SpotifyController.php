<?php

namespace App\Http\Controllers;

use Rennokki\Larafy\Larafy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpotifyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function releases(Request $request)
    {

        $api = new Larafy();
        $api->setMarket('FR')->setLocale('fr_FR');

        $limit = 16;
        $offset = 0;

        try {
            // Get new albums releases.
           $releases = $api->getNewReleases($limit, $offset);
        } catch(\Rennokki\Larafy\Exceptions\SpotifyAuthorizationException $e) {
            // invalid ID & Secret provided
            $e->getAPIResponse(); // Get the JSON API response.
        }

        $releases = $releases->items;

        return view('releases', compact('releases'));

    }

}