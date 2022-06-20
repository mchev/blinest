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
        //$team = Team::create(['name' => 'Acme Corporation Team']);

        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'is_admin' => true,
        ]);

        User::factory(5)->create();
        Category::factory(4)->create();
        Room::factory(25)->create();
/*

        Contact::factory(100)
            ->create(['account_id' => $team->id])
            ->each(function ($contact) use ($organizations) {
                $contact->update(['organization_id' => $organizations->random()->id]);
            });
*/
    }

}
