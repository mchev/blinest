<?php

namespace App\Console\Commands;

use App\Jobs\ProcessResetUnfinishedRounds;
use Illuminate\Console\Command;

class ResetRounds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rounds:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset rounds stucked in playing status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        ProcessResetUnfinishedRounds::dispatch();
        $this->info('Rounds has been correctly reseted!');

        return Command::SUCCESS;
    }
}
