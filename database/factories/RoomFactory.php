<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'user_id' => User::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'description' => $this->faker->optional()->text,
            //'photo_path' => $this->faker->imageUrl(360, 360, 'animals', true, 'cats'),
            'color' => $this->faker->safeHexColor,
            'is_public' => rand(0, 1),
            'is_active' => rand(0, 1),
            'password' => $this->faker->optional()->password,
            'deleted_at' => $this->faker->optional()->dateTime,
        ];
    }
}
