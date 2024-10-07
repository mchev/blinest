<?php

namespace App\Jobs;

use App\Events\TrackEnded;
use App\Models\Round;
use Illuminate\Bus\Queueable;
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
     */
    public function handle(): void
    {
        $this->round->load('room');

        if ($this->round->isPlaying()) {
            // Broadcast the TrackEnded event
            broadcast(new TrackEnded($this->round));

            // Check if this is the last track
            $isLastTrack = $this->round->current >= count($this->round->tracks);

            if ($isLastTrack) {
                // If it's the last track, process immediately
                ProcessTrackEnded::dispatch($this->round);
            } else {
                // If it's not the last track, add delay
                ProcessTrackEnded::dispatch($this->round)
                    ->delay(now()->addSeconds($this->round->room->pause_between_tracks));
            }
        }
    }
}
