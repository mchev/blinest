<?php

namespace App\Console\Commands;

use App\Models\Round;
use App\Models\Track;
use App\Events\TrackEnded;
use Illuminate\Console\Command;

class TrackCountdown extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track:countdown {round} {--sleep=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a TrackEnded event after 30s.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get room and track models
        $round = Round::find($this->argument('round'));
        $track = Track::find($round->tracks[$round->current - 1]);

        // Pause while the track is playing
        sleep($this->option('sleep'));

        // Broadcast the TrackEnded event
        broadcast( new TrackEnded($round, $track) );

        // Pause before playing the next track
        sleep(2);

        // Play
        $round->playNextTrack();
    }
}
