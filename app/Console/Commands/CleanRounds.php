<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessCleanRounds;

class CleanRounds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rounds:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Delete rounds where doesn't have scores";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ProcessCleanRounds::dispatch();
        $this->info('Rounds without scores were correctly deleted!');
        return Command::SUCCESS;
    }
}
