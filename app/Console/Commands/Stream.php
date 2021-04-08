<?php

namespace App\Console\Commands;

use App\Game;
use App\Track;
use App\Events\PlayTrack;
use App\Events\EndTrack;
use App\Events\EndGame;
use App\Events\NewGame;
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
    protected $tracks_by_game = 15; // 15

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
        if (\Schema::hasTable('games')) {
            $this->games = Game::where('public', 1)->get();
        }
        
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info('Games started');

        $this->getTracks($this->games);

    }

    public function getTracks($games)
    {

        $this->counter++;

        if($this->counter > $this->tracks_by_game) {

            $this->endGame();

        } else {

            foreach ($games as $game) {

                $track = $this->nextTrack($game);

                $track->hit = $track->hit + 1;
                $track->update();

                $track->counter = $this->counter;
                $track->total = $this->tracks_by_game;
                $this->tracks[] = $track->id;

                broadcast(new PlayTrack($track));

            }

            sleep(32);

            foreach ($games as $game) {

                broadcast(new EndTrack($game));

            }

            sleep(2);

            $this->getTracks($this->games);

        }

    }

    public function nextTrack(Game $game)
    {
        $track = Track::orderByRaw("RAND()")
                    ->where('game_id', $game->id)
                    ->whereNotIn('id', $this->tracks)
                    ->first();

        if(@file_get_contents($track->preview_url)) {

            return $track;

        } else {

            \App\Discord\Notification::send(
                "Suppression d'un extrait",
                "Le titre " . $track->track_name . " de " . $track->artist_name . " a été supprimé de " . $track->game->title,
                "danger"
            );

            $track->delete();
            
            return $this->nextTrack($game);

        }
    }

    public function endGame()
    {
        $this->tracks = [];
        $this->counter = 0;

        foreach ($this->games as $game) {
            broadcast(new EndGame($game));
        }

        sleep(15);

        $this->games = Game::where('public', 1)->get();

        foreach ($this->games as $game) {
            broadcast(new NewGame($game));
        }

        $this->getTracks($this->games);

    }


}
