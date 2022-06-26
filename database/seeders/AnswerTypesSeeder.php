<?php

namespace Database\Seeders;

use App\Models\AnswerType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'svg_icon' => '',
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
