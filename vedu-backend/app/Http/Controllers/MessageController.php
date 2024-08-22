<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($chat_id)
    {
        $messages = Message::where('chat_id', $chat_id)->get();
        return response()->json($messages);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        $validated = $request->validated();

        // Create the message
        $message = Message::create([
            'chat_id' => $validated['chat_id'],
            'sender_id' => $validated['sender_id'],
            'message' => $validated['message'],
        ]);

        // Broadcast the message
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        return response()->json($message);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        $validated = $request->validated();
        $message->update($validated);
        return response()->json($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $message->delete();
        return response()->json(null, 204);
    }
}