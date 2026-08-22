<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Services\Profiles\ProfileCacheService;
use Illuminate\Http\JsonResponse;

class RoomBookmarkController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Room $room): JsonResponse
    {
        $user = auth()->user();
        $user->bookmarkedRooms()->syncWithoutDetaching([$room->id]);
        app(ProfileCacheService::class)->forget($user);

        return response()->json(['is_bookmarked' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room): JsonResponse
    {
        $user = auth()->user();
        $user->bookmarkedRooms()->detach($room);
        app(ProfileCacheService::class)->forget($user);

        return response()->json(['is_bookmarked' => false]);
    }
}
