<?php

namespace App\Services\Rooms;

use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RoomParticipationService
{
    private function sessionKey(Room $room, int $userId): string
    {
        return "rooms.{$room->id}.users.{$userId}.can_play";
    }

    public function grantAccess(Room $room, ?User $user = null): void
    {
        $user ??= Auth::user();

        if ($user === null) {
            return;
        }

        Session::put($this->sessionKey($room, $user->id), true);
    }

    public function hasAccess(Room $room, ?User $user = null): bool
    {
        $user ??= Auth::user();

        if ($user === null) {
            return false;
        }

        return Session::get($this->sessionKey($room, $user->id)) === true;
    }

    public function ensureCanParticipate(Room $room, ?User $user = null): void
    {
        if (! $this->hasAccess($room, $user)) {
            abort(403, __('Unauthorized action'));
        }
    }
}
