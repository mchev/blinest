<?php

namespace App\Jobs;

use Auth;
use App\Game;
use App\Score;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;

    protected $game;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Request $request, Game $game)
    {
        $this->request = $request;
        $this->game = $game->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $score = new Score([
            'game_id' => $this->game->id,
            'user_id' => Auth::user()->id,
            'score' => $this->request->score
        ]);

        $score->save();

        return $score;
    }
}
