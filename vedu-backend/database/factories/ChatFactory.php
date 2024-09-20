<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    protected $model = Chat::class;

    public function definition()
    {
        return [
            'course_id' => Course::factory(),
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
        ];
    }
}
