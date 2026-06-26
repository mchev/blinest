<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GuestJoinController extends Controller
{
    public function __invoke(Request $request, Room $room): RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('rooms.show', $room->slug);
        }

        $guestToken = (string) Str::uuid();
        $name = 'Guest-'.random_int(10000, 99999);
        $email = 'guest-'.Str::random(8).'@b.est';

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => null,
            'is_guest' => true,
            'guest_token' => $guestToken,
        ]);

        Auth::login($user);

        return redirect()->route('rooms.show', $room->slug);
    }
}
