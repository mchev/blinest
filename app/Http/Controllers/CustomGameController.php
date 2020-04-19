<?php

namespace App\Http\Controllers;

use Auth;
use App\Game;
use App\Track;
use App\Score;
use URLShortener;
use App\Events\PlayTrack;
use App\Events\PauseTrack;
use App\Events\ResumeTrack;
use App\Events\StopGame;
use Illuminate\Http\Request;

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

        //dd(auth()->guest(), $request->session());
        if ($game->public) {

            return redirect('/parties/' . $game->slug);

        } else {

            if ( $game->password !== '' ) {

                if( $request->get('password') == $game->password ) {

                    return view('games.custom.index', compact('game'));

                } else {

                    return view('games.custom.password', compact('game'));

                }

            } else {

                return view('games.custom.index', compact('game'));

            }

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request, Game $game)
    {

        if($request->get('random') == 1) {
            $tracks = Track::inRandomOrder()->where('game_id', $game->id)->limit($request->get('tracks_number'))->get();
        } else {
            $tracks = Track::where('game_id', $game->id)->orderBy('created_at')->limit($request->get('tracks_number'))->get();
        }

        return response()->json($tracks->toArray());

    }

    public function play(Track $track)
    {
        broadcast(new PlayTrack($track));
    }

    public function pause(Track $track)
    {
        broadcast(new PauseTrack($track));
    }

    public function resume(Track $track)
    {
        broadcast(new ResumeTrack($track));
    }

    public function stop(Game $game)
    {
        broadcast(new StopGame($game));
    }

    public function password(Request $request, Game $game)
    {
        if( auth()->user()->id == $game->user_id ) {
            $game->password = $request->get('password');
            $game->update();
        }
    }

}
