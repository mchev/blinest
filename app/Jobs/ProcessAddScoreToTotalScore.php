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
     */
    public function handle(): void
    {
        $user = $this->score->user;
        $team = $this->score->team;
        $room = $this->score?->round?->room;
        $score = $this->score->score;

        if ($user && $room) {
            $user->totalScores()->updateOrCreate(
                ['room_id' => $room->id]
            )->increment('score', $score);

            if ($room->isPublic()) {
                UpdateUserLevel::dispatch($user);
            }

            if ($team) {
                $team->totalScores()->updateOrCreate(
                    ['room_id' => $room->id]
                )->increment('score', $score);
            }

        }
    }
}
