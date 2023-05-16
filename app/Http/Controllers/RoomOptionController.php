<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomOptionController extends Controller
{

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'password' => ['nullable'],
            'tracks_by_round' => ['required', 'integer', 'min:1', 'max:100'],
            'track_duration' => ['required', 'integer', 'min:5', 'max:30'],
            'pause_between_tracks' => ['required', 'integer', 'min:0', 'max:60'],
            'pause_between_rounds' => ['required', 'integer', 'min:0', 'max:60'],
            'is_chat_active' => ['required', 'boolean'],
            'is_autostart' => ['required', 'boolean'],
            'is_random' => ['required', 'boolean'],
            'color' => ['nullable'],
        ]);

        $room->fill([
            'track_duration' => $validated['track_duration'],
            'pause_between_tracks' => $validated['pause_between_tracks'],
            'pause_between_rounds' => $validated['pause_between_rounds'],
            'is_chat_active' => $validated['is_chat_active'],
            'color' => $validated['color'] ?? null,
        ]);

        if($request->user()->subscribed()) {
            $room->fill([
                'tracks_by_round' => $validated['tracks_by_round'],
                'playback_rate' => $validated['playback_rate'],
                'is_autostart' => $validated['is_autostart'],
                'is_random' => $validated['is_random'],
            ]);
        }

        $room->password = $request->input('has_password') ? $request->input('password') : null;
        $room->save();

        return back()->with('success', __('Options updated'));
    }

}