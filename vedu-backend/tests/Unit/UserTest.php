<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_has_many_courses()
    {
        $user = User::factory()->create();

        $course = Course::factory()->create(['owner_id' => $user->id]);

        $this->assertInstanceOf(Course::class, $user->courses->first());
    }
}
