<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = \App\Models\Message::class;

    public function definition(): array
    {
        return [
            'chat_id' => Chat::factory(),    // Foreign key to chats
            'sender_id' => User::factory(),  // Foreign key to users
            'message' => $this->faker->sentence,  // Generating a fake message
        ];
    }
}
