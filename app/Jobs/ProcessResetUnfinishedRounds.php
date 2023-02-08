<?php

namespace App\Jobs;

use App\Models\Round;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessResetUnfinishedRounds implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // We assume that a round while not taking longer than an hour.
        $rounds = Round::where('is_playing', 1)
            ->where('created_at', '<=', now(config('app.timezone'))->subHour()->toDateTimeString())
            ->with('room')
            ->get();

        foreach ($rounds as $round) {
            $round->finished_at = now();
            $round->is_playing = 0;
            $round->update();

            $room = $round->room;
            $room->is_playing = 0;
            $room->update();
        }
    }
}
