<?php

namespace Database\Factories;

use App\Models\{Category, Item, Location, User};
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
            'location_id' => Location::factory(),
            'status' => fake()->randomElement(Item::STATUSES()),
            'user_id' => User::factory(),
        ];
    }
}
