<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use Spatie\Sitemap\SitemapGenerator;
use App\Game;
use App\Round;
use App\Effect;
use App\Track;
use App\Score;
use App\Events\UpdateScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomGameController extends Controller
{

    /**
     * Instantiate a new GameController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Game $game)
    {

        return view('games.custom.index', compact('game'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function start(Request $request, Game $game)
    {

        if($request->get('random') == 1) {
            $tracks = Track::inRandomOrder()->where('game_id', $game->id)->limit($request->get('tracks_number'))->get();
        } else {
            $tracks = Track::where('game_id', $game->id)->orderBy('created_at')->limit($request->get('tracks_number'))->get();
        }

        return response()->json($tracks);

    }

}
