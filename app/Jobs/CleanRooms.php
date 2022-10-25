<?php

namespace App\Jobs;

use App\Models\Room;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanRooms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rooms = Room::doesntHave('playlists')->whereDate('created_at', '<=', now()->subDays(30))->get();

        foreach ($rooms as $room) {
            $room->deletePhoto();
            $room->rounds()->delete();
            $room->moderators()->detach();
            $room->forceDelete();
        }

        $deletedRooms = Room::onlyTrashed()->get();

        foreach ($deletedRooms as $room) {
            $room->deletePhoto();
            $room->rounds()->delete();
            $room->moderators()->detach();
            $room->forceDelete();
        }
    }
}
