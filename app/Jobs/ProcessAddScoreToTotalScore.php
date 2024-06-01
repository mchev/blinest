<?php

namespace App\Jobs;

use App\Models\Score;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAddScoreToTotalScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Score $score)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $user = $this->score->user;
        $team = $this->score->team;
        $room = $this->score?->round?->room;

        if ($user && $room) {
            $user->totalScores()->updateOrCreate(
                ['room_id' => $room->id]
            )->increment('score', $this->score->score);

            if ($team) {
                $team->totalScores()->updateOrCreate(
                    ['room_id' => $room->id]
                )->increment('score', $this->score->score);
            }
        }
    }
}
