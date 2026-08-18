<?php

namespace App\Services\Rooms;

use App\Models\Room;

class OfficialRoomRegistry
{
    /**
     * @return list<string>
     */
    public function slugs(): array
    {
        return config('official_room_seo.slugs', []);
    }

    public function isOfficialSeoRoom(Room $room): bool
    {
        if (! $room->is_public || filled($room->password)) {
            return false;
        }

        return in_array($room->slug, $this->slugs(), true);
    }
}
