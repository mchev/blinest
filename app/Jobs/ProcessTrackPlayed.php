<?php

namespace App\Jobs;

use App\Models\Round;
use App\Models\Track;
use App\Jobs\ProcessTrackEnded;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTrackPlayed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $round;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Round $round)
    {
        $this->round = $round;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // Broadcast the TrackEnded event
        broadcast( new \App\Events\TrackEnded($this->round) );

        // Pause between next tracks
        ProcessTrackEnded::dispatch($this->round)
            ->delay(now()->addSeconds($this->round->room->pause_beteen_tracks));

    }
}
