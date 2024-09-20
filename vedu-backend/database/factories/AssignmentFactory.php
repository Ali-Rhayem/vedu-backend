<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    protected $model = Assignment::class;

    public function definition()
    {
        return [
            'course_id' => Course::factory(),
            'topic_id' => Topic::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'grade' => $this->faker->numberBetween(50, 100),
        ];
    }
}
