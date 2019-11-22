<?php

namespace App\Http\Controllers;

use Auth;
use App\Game;
use App\Track;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Game $game)
    {

        $tracks = $game->tracks()->paginate(20);
        return response()->json($tracks);
    }

    /**
     * Search tracks from a game.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, Game $game)
    {

        $tracks = $game->tracks()->where(function ($query) use ($request) {
                $query->where('artist_name', 'like', '%' . $request->q . '%')
                      ->orWhere('track_name', 'like', '%' . $request->q . '%')
                      ->orWhere('custom_answer', 'like', '%' . $request->q . '%');
            })
            ->paginate(20);

        return response()->json($tracks);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Game $game)
    {

        if (!Track::where('provider_item_id', $request->provider_item_id)->where('game_id', $game->id)->exists()) {

            $track = new Track([
                'user_id' => Auth::user()->id,
                'game_id' => $game->id,
                'provider_item_id' => $request->provider_item_id,
                'provider' => $request->provider,
                'artist_name' => $request->artist_name,
                'track_name' => $request->track_name,
                'artwork_url' => $request->artwork_url,
                'preview_url' => $request->preview_url,
                'filetype' => $request->filetype
            ]);

            $track->save();

        }

        return response()->json($track);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function show(Track $track)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function edit(Track $track)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Track $track)
    {
        //
    }

    /**
     * Store a custom anwser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function updateCustomAnwser(Request $request, Track $track)
    {

        $track->custom_answer = $request->custom_answer;
        $track->save();

        return response()->json($track);
    }   


    /**
     * Update track rate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function updateTrackRate(Request $request, Track $track)
    {

        $track->down_rate = $track->down_rate + 1;
        $track->save();

        return response()->json($track);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Game  $game
     * @param  \App\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game, Track $track)
    {
        $track->delete();
    }
}
