<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\Category;
use App\Models\Room;
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

        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'is_admin' => true,
        ]);

        User::factory(50)->create();
        Team::factory(5)->create();
        Category::factory(4)->create();
        Room::factory(25)->create();

        foreach(Team::all() as $team) {
            $user = User::find($team->user_id);
            $user->team_id = $team->id;
            $user->update();
        };

    }

}
