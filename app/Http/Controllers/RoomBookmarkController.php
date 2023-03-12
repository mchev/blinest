<?php

namespace App\Http\Controllers;

use App\Models\Room;

class RoomBookmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Room $room)
    {
        auth()->user()->bookmarkedRooms()->attach($room);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        auth()->user()->bookmarkedRooms()->detach($room);

        return redirect()->back();
    }
}
