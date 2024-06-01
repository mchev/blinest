<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
