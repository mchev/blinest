<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function show(Room $room)
    {
        return Inertia::render('Rooms/Show', [
            'room' => $room,
        ]);
    }

    /**
     * Starting a round if no running
     */
    public function joined(Room $room)
    {
        if ($room->isPublic() && ! $room->isPlaying()) {
            $room->startRound();
        }
    }

    public function start(Room $room)
    {
        (Auth::user()->hasRoomControl($room))
            ? $room->startRound()
            : abort(403);
    }
}
