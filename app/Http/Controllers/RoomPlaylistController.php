<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomPlaylistController extends Controller
{
    public function attach(Request $request, Room $room)
    {
        if (Auth::user()->isRoomOwner($room) || Auth::user()->isAdministrator()) {
            $request->validate([
                'playlist_id' => ['required', 'integer', 'exists:playlists,id'],
            ]);
            $room->playlists()->syncWithoutDetaching($request->playlist_id);

            return redirect()->back();
        }

        return abort(403, 'Unauthorized action.');
    }

    public function detach(Request $request, Room $room)
    {
        if (Auth::user()->isRoomOwner($room) || Auth::user()->isAdministrator()) {
            $request->validate([
                'playlist_id' => ['required', 'integer', 'exists:playlists,id'],
            ]);
            $room->playlists()->detach($request->playlist_id);

            return redirect()->back();
        }

        return abort(403, 'Unauthorized action.');
    }
}
