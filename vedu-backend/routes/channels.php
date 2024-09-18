<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Chat;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    // return (int) $user->id === (int) $id;
    return true;
});

Broadcast::channel('chat.{chat_id}', function ($user, $chat_id) {
    // return Chat::where('id', $chat_id)
    //     ->where(function ($query) use ($user) {
    //         $query->where('sender_id', $user->id)
    //             ->orWhere('receiver_id', $user->id);
    //     })
    //     ->exists();
    return true;
});