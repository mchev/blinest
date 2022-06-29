<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomModeratorController extends Controller
{
    public function attach(Request $request, Room $room)
    {
        if (Auth::user()->id == $room->owner->id || Auth::user()->isAdministrator) {
            $room->moderators()->attach($request->user_id);
        } else {
            abort(403, 'Unauthorized action.');
        }

        return redirect()->back();
    }

    public function detach(Request $request, Room $room)
    {
        if (Auth::user()->id == $room->owner->id || Auth::user()->isAdministrator) {
            $room->moderators()->detach($request->user_id);
        } else {
            abort(403, 'Unauthorized action.');
        }

        return redirect()->back();
    }
}
