<?php

namespace App\Console\Commands;

use App\Models\Round;
use App\Events\TrackEnded;
use Illuminate\Console\Command;

class Countdown extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track:countdown {round} {--sleep=10}';

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
        // Get the room model
        $round = Round::find($this->argument('round'));

        // Track play time
        sleep($this->option('sleep'));

        // Broadcast the TrackEnded event
        broadcast( new TrackEnded($round) );

        // Sleep before playing the next track
        sleep(2);

        // Play next
        $round->playNextTrack();
    }
}
