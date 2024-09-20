<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssignmentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_assignments()
    {
        Assignment::factory(3)->create();

        $response = $this->getJson('/api/assignments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'assignments' => [
                    '*' => ['id', 'title', 'description', 'course_id', 'topic_id', 'due_date', 'grade'],
                ],
            ]);
    }

    /** @test */
    public function it_can_create_an_assignment()
    {
        $course = Course::factory()->create();
        $topic = Topic::factory()->create(['course_id' => $course->id]);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/assignments', [
            'course_id' => $course->id,
            'topic_id' => $topic->id,
            'title' => 'Test Assignment',
            'description' => 'This is a test assignment.',
            'due_date' => now()->addWeek()->toDateTimeString(),
            'grade' => 90,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'assignment' => ['id', 'title', 'description', 'course_id', 'topic_id', 'due_date', 'grade'],
            ]);

        $this->assertDatabaseHas('assignments', ['title' => 'Test Assignment']);
    }

    /** @test */
    public function it_can_show_an_assignment()
    {
        $assignment = Assignment::factory()->create();

        $response = $this->getJson("/api/assignments/{$assignment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'assignment' => [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'description' => $assignment->description,
                ],
            ]);
    }

    /** @test */
    public function it_can_update_an_assignment()
    {
        $assignment = Assignment::factory()->create([
            'title' => 'Old Title',
        ]);

        $response = $this->putJson("/api/assignments/{$assignment->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated description.',
            'due_date' => now()->addWeek()->toDateTimeString(),
            'grade' => 95,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'assignment' => [
                    'title' => 'Updated Title',
                    'description' => 'Updated description.',
                    'grade' => 95,
                ],
            ]);

        $this->assertDatabaseHas('assignments', ['title' => 'Updated Title']);
    }

    /** @test */
    public function it_can_delete_an_assignment()
    {
        $assignment = Assignment::factory()->create();

        $response = $this->deleteJson("/api/assignments/{$assignment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Assignment deleted successfully.',
            ]);

        $this->assertDatabaseMissing('assignments', ['id' => $assignment->id]);
    }

    /** @test */
    public function it_can_get_assignments_for_a_course()
    {
        $course = Course::factory()->create();
        Assignment::factory(3)->create(['course_id' => $course->id]);

        $response = $this->getJson("/api/assignments/course/{$course->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'assignments' => [
                    '*' => ['id', 'title', 'description', 'course_id'],
                ],
            ]);
    }


    /** @test */
    public function it_can_get_assignments_grouped_by_topic()
    {
        $course = Course::factory()->create();
        $topic = Topic::factory()->create(['course_id' => $course->id]);
        Assignment::factory(2)->create(['topic_id' => $topic->id]);

        $response = $this->getJson("/api/assignments/course/{$course->id}/by-topic");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'topics' => [
                    $topic->name => [
                        'id',
                        'assignments' => [
                            '*' => ['id', 'title', 'description'],
                        ],
                    ],
                ],
            ]);
    }
}
