<?php

namespace App\Console\Commands;

use App\Jobs\ProcessRoomsMosaics;
use Illuminate\Console\Command;

class GenerateRoomsMosaics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooms:mosaics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automaticaly generate mosaics for each rooms';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ProcessRoomsMosaics::dispatch();
    }
}
