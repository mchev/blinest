<?php

namespace Database\Seeders;

use App\Models\Old\OldUser;
use App\Models\Round;
use App\Models\Score;
use Illuminate\Database\Seeder;

class ImportOldScores extends Seeder
{
    public function run()
    {
        OldUser::chunk(500, function($users) {

            $users = $users->map(function($user) {

                $scores = $user->scores()->groupBy('game_id')->selectRaw('user_id, game_id as room_id, SUM(score) as total')->get();

                foreach($scores as $score) {
                    $round = Round::firstOrCreate(
                        ['room_id' => $score->room_id],
                        ['finished_at' => now()]
                    );
                    Score::create([
                        'user_id' => $score->user_id,
                        'round_id' => $round->id,
                        'score' => $score->total,
                    ]);
                }

            });

        });
       
    }
}
