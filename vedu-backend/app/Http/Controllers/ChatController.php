<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Models\Chat;
use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::all();
        return response()->json([
            "Chats" => $chats
        ], 200);
    }


    public function store(StoreChatRequest $request)
    {
        $validated = $request->validate([
            'course_id' => 'nullable|exists:courses,id',
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'nullable|exists:users,id',
        ]);

        $chat = Chat::create($validated);

        return response()->json($chat, 201);
    }

    public function show(Chat $chat)
    {
        return response()->json([
            "course" => $chat
        ]);
    }


    public function update(UpdateChatRequest $request, Chat $chat)
    {
        $chat->update($request->validated());
        return response()->json([
            "Chat" => $chat
        ], 200);
    }

    public function destroy(Chat $chat)
    {
        $chat->delete();
        return response()->json(['message' => 'Chat deleted successfully']);
    }

    public function messages(Chat $chat)
    {
        return response()->json([
            "messages" => $chat->messages
        ]);
    }

    public function checkExistingChat(Request $request)
    {
        $chat = Chat::where(function ($query) use ($request) {
            $query->where('sender_id', $request->sender_id)
                ->where('receiver_id', $request->receiver_id)
                ->where('course_id', $request->course_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('sender_id', $request->receiver_id)
                ->where('receiver_id', $request->sender_id)
                ->where('course_id', $request->course_id);
        })->first();

        if ($chat) {
            return response()->json(['chat_id' => $chat->id]);
        } else {
            return response()->json(['chat_id' => null]);
        }
    }
}
