<?php

namespace App\Http\Controllers\Api;

use App\Track;
use App\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItunesController extends Controller
{
 
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        $query = filter_var ( $request->q, FILTER_SANITIZE_STRING);
        $query = trim ( $query );
        $query = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $query);
        $query = str_replace(' ', '+', $query);

    	$url = 'https://itunes.apple.com/search?term=' . $query . '&country=fr&entity=musicTrack&limit=10&output=json';

        try {
            $json = json_decode(file_get_contents($url, false), true);
        }
        catch (Exception $e) {
            return response()->json($e->getMessage());
        }

        return response()->json($json);

    }

}