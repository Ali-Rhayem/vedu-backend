<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopicFactory extends Factory
{
    protected $model = \App\Models\Topic::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word, 
            'course_id' => Course::factory(),
        ];
    }
}
