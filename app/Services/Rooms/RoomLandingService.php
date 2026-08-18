<?php

namespace App\Services\Rooms;

use App\Models\Room;
use App\Services\RoomPresenceService;

class RoomLandingService
{
    public function __construct(
        private OfficialRoomRegistry $officialRooms,
        private RoomPresenceService $presence,
    ) {}

    /**
     * @return list<array{label: string, href: string|null}>
     */
    public function breadcrumbs(Room $room): array
    {
        $items = [
            ['label' => __('Home'), 'href' => route('home')],
        ];

        if ($room->category?->slug) {
            $items[] = [
                'label' => __($room->category->name),
                'href' => route('categories.show', $room->category->slug),
            ];
        }

        $items[] = ['label' => $room->name, 'href' => null];

        return $items;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function similarRooms(Room $room, int $limit = 4): array
    {
        if ($room->category_id === null) {
            return [];
        }

        return Room::query()
            ->isPublic()
            ->whereNull('password')
            ->where('category_id', $room->category_id)
            ->whereKeyNot($room->id)
            ->whereIn('slug', $this->officialRooms->slugs())
            ->orderBy('name')
            ->limit($limit)
            ->get()
            ->map(fn (Room $similar) => $similar->toHomepageArray())
            ->values()
            ->all();
    }

    public function playersOnline(Room $room): int
    {
        return $this->presence->getMemberCount($room);
    }
}
