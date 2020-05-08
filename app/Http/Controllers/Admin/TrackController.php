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

        $tracks = Track::count();

        return view('admin.tracks.index', ['tracks_count' => $tracks]);
    }

    /**       
    * Display a listing of the resource.
    *
    * @param  Illuminate\Http\Request $request
    * @return Response
    */
    public function list(Request $request)
    {
        if($request->ajax()){

            $request->order = (isset($request->order)) ? $request->order : 'down_rate';

            if ($request->q) {

                $tracks = Track::where('track_name', 'like', '%' . $request->q . '%')
                            ->orWhere('artist_name', 'like', '%' . $request->q . '%')
                            ->orderBy($request->order, 'DESC')
                            ->with('game')
                            ->paginate($request->paginate);
            } else {

                $tracks = Track::orderBy($request->order, 'DESC')
                            ->with('game')
                            ->paginate($request->paginate);
            }

            return response()->json($tracks);

        } else {

            return view('customers.index');

        }
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

        $track->artist_name = $request->artist_name;
        $track->track_name = $request->track_name;
        $track->custom_answer = $request->custom_answer;
        $track->down_rate = $request->down_rate;
        $track->update();

        return response()->json($track);
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
    }
}
