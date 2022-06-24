<?php

namespace App\Console\Commands;

use App\Models\Room;
use Illuminate\Console\Command;

class RoundCountdown extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'round:countdown {room} {--sleep=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a new round after 10s.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get the room model
        $room = Room::find($this->argument('room'));

        // Pause (displaying round results)
        sleep($this->option('sleep'));

        // Start a new round
        if($room->isPublic() && !$room->isPlaying())
            $room->startRound();
    }
}
