<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSlugsSeeder extends Seeder
{
    public function run()
    {
        Room::chunk(500, function ($rooms) {
            foreach ($rooms as $room) {
                $room->update([
                    'slug' => $room->slugify($room->name),
                ]);
            }
        });
    }
}
