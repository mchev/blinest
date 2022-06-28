<?php

namespace Database\Seeders;

use App\Models\AnswerType;
use Illuminate\Database\Seeder;

class AnswerTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AnswerType::create([
            'name' => 'Artist',
            'pronoun' => 'the',
            'svg_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-mic-fill" viewBox="0 0 16 16">
  <path d="M5 3a3 3 0 0 1 6 0v5a3 3 0 0 1-6 0V3z"/>
  <path d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5z"/>
</svg>',
        ]);
        AnswerType::create([
            'name' => 'Title',
            'pronoun' => 'the',
            'svg_icon' => '',
        ]);
        AnswerType::create([
            'name' => 'Album',
            'pronoun' => 'the',
            'svg_icon' => '',
        ]);
        AnswerType::create([
            'name' => 'Year',
            'pronoun' => 'the',
            'svg_icon' => '',
        ]);
        AnswerType::create([
            'name' => 'Featuring',
            'pronoun' => 'the',
            'svg_icon' => '',
        ]);
        AnswerType::create([
            'name' => 'Movie',
            'pronoun' => 'the',
            'svg_icon' => '',
        ]);
        AnswerType::create([
            'name' => 'Show',
            'pronoun' => 'the',
            'svg_icon' => '',
        ]);
        AnswerType::create([
            'name' => 'Anime',
            'pronoun' => 'the',
            'svg_icon' => '',
        ]);
        AnswerType::create([
            'name' => 'Cartoon',
            'pronoun' => 'the',
            'svg_icon' => '',
        ]);
        AnswerType::create([
            'name' => 'Brand',
            'pronoun' => 'the',
            'svg_icon' => '',
        ]);
        AnswerType::create([
            'name' => 'Acronym',
            'pronoun' => 'the',
            'svg_icon' => '',
        ]);
    }
}
