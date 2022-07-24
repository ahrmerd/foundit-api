<?php

namespace Database\Factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private function getState()
    {
        $states = State::query()->get();
        $state = ($states->isEmpty()) ? State::factory() : $states->random(1)->first()->id;
        return $state;
    }
    public function definition()
    {
        return [
            'name' => fake()->word(),
            'state_id' => $this->getState(),
        ];
    }
}
