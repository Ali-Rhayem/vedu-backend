<?php

namespace App\Events;

use App\Models\Message; 
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChatMessageSent implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $message; 

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Message  $message
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        // Broadcast on the private channel for the chat
        return new Channel('chat.' . $this->message->chat_id);
    }

    public function broadcastAs(){
        return 'message.sent';
    }
}
