<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\JsonResponse;

class RoomBookmarkController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Room $room): JsonResponse
    {
        auth()->user()->bookmarkedRooms()->syncWithoutDetaching([$room->id]);

        return response()->json(['is_bookmarked' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room): JsonResponse
    {
        auth()->user()->bookmarkedRooms()->detach($room);

        return response()->json(['is_bookmarked' => false]);
    }
}
