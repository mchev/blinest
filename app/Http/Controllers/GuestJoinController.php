<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Services\Auth\GuestAuthService;
use Illuminate\Http\RedirectResponse;

class GuestJoinController extends Controller
{
    public function __invoke(Room $room, GuestAuthService $guestAuth): RedirectResponse
    {
        $guestAuth->ensureGuestSession();

        return redirect()->route('rooms.show', $room->slug);
    }
}
