<?php

namespace Database\Factories;

use App\Models\{Category, User};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->unique()->word(),
            'description' => fake()->sentence(10),
            'category_id' => Category::factory(),
            'status' => fake()->numberBetween(0, 4),
            'user_id' => User::factory(),
        ];
    }
}
