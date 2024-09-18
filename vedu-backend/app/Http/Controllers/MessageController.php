<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Events\MessageSent;
use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;

class MessageController extends Controller
{
    public function index($chat_id)
    {
        $messages = Message::where('chat_id', $chat_id)->get();
        return response()->json($messages);
    }

    public function store(StoreMessageRequest $request)
    {
        $validated = $request->validated();

        $message = Message::create([
            'chat_id' => $validated['chat_id'],
            'sender_id' => $validated['sender_id'],
            'message' => $validated['message'],
        ]);

        broadcast(new ChatMessageSent($message))->toOthers();

        return response()->json($message, 201);
    }


    public function show(Message $message)
    {
        return response()->json($message);
    }


    public function update(UpdateMessageRequest $request, Message $message)
    {
        $validated = $request->validated();
        $message->update($validated);
        return response()->json($message);
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return response()->json(null, 204);
    }
}
