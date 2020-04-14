<?php

namespace App\Console\Commands;

use App\Game;
use App\Track;
use App\Events\NewTrack;
use App\Events\EndGame;
use Illuminate\Console\Command;

class Stream extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'play';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start broadcasting tracks';


    protected $games;

    /**
     * The numbers of tracks played in a game.
     *
     * @var int
     */
    protected $tracks_by_game = 15;

    /**
     * Init counter
     *
     * @var int
     */
    protected $counter = 0;

    /**
     * Init tracks
     *
     * @var array
     */
    protected $tracks = [];


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // $this->games = Game::where('public', true)->get();
        //$this->games = Game::has('tracks', '>', 49)->get();
        $this->games = Game::where('public', 1)->get();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->getTracks($this->games);

    }

    public function getTracks($games)
    {

        $this->counter++;

        if($this->counter > $this->tracks_by_game) {

            $this->endGame();

        } else {

            foreach ($games as $game) {

                $track = Track::inRandomOrder()->where('game_id', $game->id)->whereNotIn('id', $this->tracks)->first();
                $track->counter = $this->counter;
                $track->total = $this->tracks_by_game;
                $this->tracks[] = $track->id;

                broadcast(new NewTrack($track));

            }

            sleep(32);

            $this->getTracks($this->games);

        }

    }

    public function endGame()
    {
        $this->tracks = [];
        $this->counter = 0;
        //$this->games = Game::has('tracks', '>', 49)->get();
        $this->games = Game::where('public', 1)->get();

        broadcast(new EndGame());

        sleep(30);

        $this->getTracks($this->games);

    }


}
