<?php

namespace App\Console\Commands;

use App\Models\Score;
use Illuminate\Console\Command;
use App\Jobs\ProcessAddScoreToTotalScore;

class MergeScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scores:merge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sum all scores in total_scores table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $count = Score::has('user')->has('round')->count();
        $bar = $this->getOutput()->createProgressBar($count);

        Score::has('user')->has('round')->chunk(5000, function ($scores) use ($bar) {
            $scores->each(fn ($score) => ProcessAddScoreToTotalScore::dispatchNow($score));
            $bar->advance(5000);
        });

        $bar->finish();

        return Command::SUCCESS;
    }
}
