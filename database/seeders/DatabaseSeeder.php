<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Room;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AnswerTypesSeeder::class,
            ImportOldUsers::class,
            ImportOldGames::class,
            ImportOldTracks::class,
            ImportOldScores::class,
        ]);

        Category::factory()->create([
            'name' => 'Genre musical',
        ]);
        Category::factory()->create([
            'name' => 'Les décennies',
        ]);
        Category::factory()->create([
            'name' => 'Sur les écrans',
        ]);
        Category::factory()->create([
            'name' => 'Divers',
        ]);

        // User::factory(50)->create();
        // Team::factory(5)->create();
        // Category::factory(4)->create();
        // Room::factory(50)->create();

        // foreach (Team::all() as $team) {
        //     $user = User::find($team->user_id);
        //     $user->team_id = $team->id;
        //     $user->update();
        // }
    }
}
