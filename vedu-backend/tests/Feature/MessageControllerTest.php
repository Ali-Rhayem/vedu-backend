<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_messages_for_a_chat()
    {
        $chat = Chat::factory()->create();
        Message::factory(3)->create(['chat_id' => $chat->id]);

        $response = $this->getJson("/api/messages/{$chat->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'message', 'chat_id', 'sender_id'],
            ]);
    }

    /** @test */
    public function it_can_create_a_message()
    {
        $chat = Chat::factory()->create();
        $user = User::factory()->create();

        $response = $this->postJson('/api/messages', [
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'message' => 'Test message',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'chat_id' => $chat->id,
                'sender_id' => $user->id,
                'message' => 'Test message',
            ]);

        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'message' => 'Test message',
        ]);
    }

    // /** @test */
    // public function it_can_show_a_message()
    // {
    //     $chat = Chat::factory()->create();
    //     $message = Message::factory()->create([
    //         'chat_id' => $chat->id,
    //         'message' => 'Test message content',
    //     ]);

    //     // Fetch the message using the correct route
    //     $response = $this->getJson("/api/messages/chat/{$chat->id}");

    //     // Assert correct response
    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'id' => $message->id,
    //             'message' => $message->message,
    //         ]);
    // }


    /** @test */
    public function it_can_update_a_message()
    {
        $message = Message::factory()->create(['message' => 'Old message']);

        $response = $this->putJson("/api/messages/{$message->id}", [
            'message' => 'Updated message',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $message->id,
                'message' => 'Updated message',
            ]);

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'message' => 'Updated message',
        ]);
    }

    /** @test */
    public function it_can_delete_a_message()
    {
        $message = Message::factory()->create();

        $response = $this->deleteJson("/api/messages/{$message->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('messages', [
            'id' => $message->id,
        ]);
    }
}
