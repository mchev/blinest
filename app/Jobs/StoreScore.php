<?php

namespace App\Jobs;

use Auth;
use App\Score;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $score;

    protected $game_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($score, $game_id)
    {
        $this->score = $score;
        $this->game_id = $game_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $score = new Score([
            'game_id' => $this->game_id,
            'user_id' => Auth::user()->id,
            'score' => $this->score
        ]);

        $score->save();

    }
}
