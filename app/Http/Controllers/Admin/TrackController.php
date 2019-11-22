<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Track;
use Illuminate\Http\Request;


class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $tracks = Track::orderBy('down_rate', 'desc')->paginate(20);

        return view('admin.tracks.index', ['tracks' => $tracks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function duplicates()
    {

        $tracks = Track::whereIn('id', function ( $query ) {
            $query->select('id')->from('tracks')->groupBy('track_name')->havingRaw('count(*) > 1');
        })->paginate(20);

        return view('admin.tracks.index', ['tracks' => $tracks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

        if($request->has('delete')){

            $track->delete();

        } else {

            $track->artist_name = $request->artist_name;
            $track->track_name = $request->track_name;
            $track->custom_answer = $request->custom_answer;
            $track->down_rate = $request->down_rate;
            $track->save();

        }

        return redirect('/admin/tracks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function destroy(Track $track)
    {
        $track->delete();

        return redirect('/admin/tracks');
    }
}
