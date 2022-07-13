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
        $count = OldUser::count();
        $bar = $this->command->getOutput()->createProgressBar($count);

        OldUser::chunk(500, function ($users) use ($bar) {
            $users = $users->map(function ($user) {
                $scores = $user->scores()
                    ->whereRelation('game', function ($query) {
                        $query->has('tracks');
                    })
                    ->groupBy('game_id')
                    ->selectRaw('user_id, game_id as round_id, SUM(score) as score')
                    ->get();

                $rounds = $user->scores()
                    ->whereRelation('game', function ($query) {
                        $query->has('tracks');
                    })
                    ->groupBy('game_id')
                    ->selectRaw('game_id as id, game_id as room_id, updated_at as finished_at')
                    ->get();

                Round::upsert(
                    $rounds->toArray(),
                    ['id'],
                    ['room_id', 'finished_at']
                );

                Score::upsert(
                    $scores->toArray(),
                    ['id'],
                    ['user_id', 'round_id', 'score']
                );
            });

            $bar->advance(500);
        });

        $bar->finish();
        $this->command->line('');
    }
}
