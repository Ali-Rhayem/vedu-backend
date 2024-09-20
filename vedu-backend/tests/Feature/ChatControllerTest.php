<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_chats()
    {
        Chat::factory(3)->create();

        $response = $this->getJson('/api/chats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'Chats' => [
                    '*' => ['id', 'course_id', 'sender_id', 'receiver_id'],
                ],
            ]);
    }

    /** @test */
    public function it_can_create_a_chat()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $course = Course::factory()->create();

        $response = $this->postJson('/api/chats', [
            'course_id' => $course->id,
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'course_id' => $course->id,
                'sender_id' => $user1->id,
                'receiver_id' => $user2->id,
            ]);

        $this->assertDatabaseHas('chats', [
            'course_id' => $course->id,
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
        ]);
    }

    /** @test */
    public function it_can_show_a_chat()
    {
        $chat = Chat::factory()->create();

        $response = $this->getJson("/api/chats/{$chat->id}");

        $response->assertStatus(200)
            ->assertJson([
                'course' => [
                    'id' => $chat->id,
                ],
            ]);
    }

    /** @test */
    public function it_can_update_a_chat()
    {
        $chat = Chat::factory()->create([
            'course_id' => null,
        ]);

        $course = Course::factory()->create();

        $response = $this->putJson("/api/chats/{$chat->id}", [
            'course_id' => $course->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'Chat' => [
                    'id' => $chat->id,
                    'course_id' => $course->id,
                ],
            ]);

        $this->assertDatabaseHas('chats', [
            'id' => $chat->id,
            'course_id' => $course->id,
        ]);
    }

    /** @test */
    public function it_can_delete_a_chat()
    {
        $chat = Chat::factory()->create();

        $response = $this->deleteJson("/api/chats/{$chat->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Chat deleted successfully',
            ]);

        $this->assertDatabaseMissing('chats', [
            'id' => $chat->id,
        ]);
    }

    /** @test */
    public function it_can_get_chat_messages()
    {
        $chat = Chat::factory()->create();
        $messages = $chat->messages()->createMany([
            ['message' => 'Hello there!', 'sender_id' => $chat->sender_id],
            ['message' => 'Hi back!', 'sender_id' => $chat->receiver_id],
        ]);

        $response = $this->getJson("/api/messages/{$chat->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'message', 'sender_id'],
            ]);
    }

    /** @test */
    public function it_can_check_if_chat_exists()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $course = Course::factory()->create();

        $chat = Chat::factory()->create([
            'course_id' => $course->id,
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
        ]);

        $response = $this->postJson('/api/chats/check-existing', [
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
            'course_id' => $course->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'chat_id' => $chat->id,
            ]);
    }
}
