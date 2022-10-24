<?php

namespace Database\Seeders;

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
            CategoriesSeeder::class,
            ImportOldUsers::class,
            ImportOldGames::class,
            ImportOldModerators::class,
            ImportOldTracks::class,
            ImportOldScores::class,
        ]);
    }
}
