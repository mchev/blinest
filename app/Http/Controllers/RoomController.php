<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Playlist;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;

class RoomController extends Controller
{

    public function show(Room $room)
    {

        if($room->isPublic() && !$room->isPlaying())
            $room->startRound();

        return Inertia::render('Rooms/Show', [
            'room' => $room,
        ]);
    }

}
