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

        // Get the latest round for this room
        $latestRound = $this->room->rounds()->latest()->first();

        // Check if the latest round is older than 30 minutes
        // This is to prevent starting new rounds if there's been no activity for a while
        if ($latestRound && $latestRound->created_at < now()->subMinutes(30)) {
            // If the latest round is older than 30 minutes, we don't start a new round
            return;
        }

        // Check if we should start a new round
        // Conditions:
        // 1. The room is not currently playing
        // 2. There are users in the room (user count > 0)
        // 3. The room is set to autostart
        if (! $this->room->is_playing && $this->room->user_count > 0 && $this->room->is_autostart) {
            // If all conditions are met, dispatch a job to start a new round
            StartRound::dispatch($this->room);
        }
    }
}
