<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_courses()
    {
        Course::factory(3)->create(); 

        $response = $this->getJson('/api/courses');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'courses' => [
                    '*' => ['id', 'name', 'description', 'class_code', 'owner_id'],
                ],
            ]);
    }

    /** @test */
    public function it_can_create_a_course()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/courses', [
            'name' => 'New Course',
            'description' => 'This is a test course.',
            'owner_id' => $user->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'course' => ['id', 'name', 'description', 'class_code', 'owner_id'],
            ]);

        $this->assertDatabaseHas('courses', ['name' => 'New Course']);
    }

    /** @test */
    public function it_can_show_a_single_course()
    {
        $course = Course::factory()->create();

        $response = $this->getJson("/api/courses/{$course->id}");

        $response->assertStatus(200)
            ->assertJson([
                'course' => [
                    'id' => $course->id,
                    'name' => $course->name,
                    'description' => $course->description,
                ],
            ]);
    }

    /** @test */
    public function it_can_update_a_course()
    {
        $course = Course::factory()->create([
            'name' => 'Old Course Name',
        ]);

        $response = $this->putJson("/api/courses/{$course->id}", [
            'name' => 'Updated Course Name',
            'description' => 'Updated description.',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'course' => [
                    'name' => 'Updated Course Name',
                    'description' => 'Updated description.',
                ],
            ]);

        $this->assertDatabaseHas('courses', ['name' => 'Updated Course Name']);
    }

    /** @test */
    public function it_can_delete_a_course()
    {
        $course = Course::factory()->create();

        $response = $this->deleteJson("/api/courses/{$course->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    /** @test */
    public function it_can_get_users_in_a_course()
    {
        $course = Course::factory()->create();
        $instructor = User::factory()->create();
        $student = User::factory()->create();

        $course->instructors()->attach($instructor->id);
        $course->students()->attach($student->id);

        $response = $this->getJson("/api/course/{$course->id}/users");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'course_id',
                'users' => [
                    '*' => ['id', 'name', 'email'],
                ],
            ]);
    }
}
