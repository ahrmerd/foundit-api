<?php

namespace Database\Factories;

use App\Models\{Conversation, User};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'conversation_id' => Conversation::factory(),
            'message' => fake()->sentence()
        ];
    }
}
