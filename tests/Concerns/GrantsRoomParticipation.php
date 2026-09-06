<?php

namespace Tests\Concerns;

use App\Models\Room;
use App\Models\User;
use App\Services\Rooms\RoomParticipationService;

trait GrantsRoomParticipation
{
    protected function grantRoomParticipation(Room $room, ?User $user = null): void
    {
        if ($user !== null) {
            $this->actingAs($user);
        }

        app(RoomParticipationService::class)->grantAccess($room, $user);
    }
}
