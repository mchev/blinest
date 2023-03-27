<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistModeratorController extends Controller
{
    public function attach(Request $request, Playlist $playlist)
    {
        if (Auth::user()->id === $playlist->owner->id || Auth::user()->isAdministrator()) {
            $playlist->moderators()->syncWithoutDetaching($request->user_id);

            return redirect()->back();
        }

        abort(403, __('Unauthorized action'));
    }

    public function detach(Request $request, Playlist $playlist)
    {
        if (Auth::user()->id === $playlist->owner->id || Auth::user()->isAdministrator()) {
            $playlist->moderators()->detach($request->user_id);

            return redirect()->back();
        }

        abort(403, __('Unauthorized action'));
    }
}
