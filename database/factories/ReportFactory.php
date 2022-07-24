<?php

namespace Database\Factories;

use App\Models\{Report, User};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->word(),
            'description' => fake()->sentence(10),
            'user_id' => User::factory(),
            'type' => fake()->randomElement(Report::TYPE),
            'status' => fake()->randomElement(Report::STATUS),
        ];
    }
}
