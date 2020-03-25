<?php

namespace App\Http\Controllers;

use Auth;
use App\Game;
use App\Track;
use App\Events\NewTrack;


class StreamController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    	$game = Game::first();
        return view('stream', compact('game'));
    }

    public function track(Request $request)
    {

    	$track = Track::inRandomOrder()->where('game_id', $request->game_id)->first();

    	broadcast(new NewTrack($track));

    }

    public function init()
    {

    }

}