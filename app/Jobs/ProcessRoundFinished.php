<?php

namespace App\Jobs;

use App\Models\Room;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRoundFinished implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Room $room
    ) {}

    public function handle(): void
    {
        // Refresh the room model to ensure we have the latest data
        $this->room->refresh();

        // Check if we should start a new round
        if (! $this->room->is_playing && $this->room->subscriptions > 0 && $this->room->is_autostart) {
            StartRound::dispatch($this->room);
        }
    }
}
