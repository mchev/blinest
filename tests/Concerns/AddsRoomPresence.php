<?php

namespace Tests\Concerns;

use App\Models\Room;
use App\Models\User;
use App\Services\RoomPresenceService;

trait AddsRoomPresence
{
    protected function addUserToRoomPresence(Room $room, User $user): void
    {
        app(RoomPresenceService::class)->addMember($room, $user);
    }
}
