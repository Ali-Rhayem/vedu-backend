<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\ChatMessageSent;
use App\Models\Chat;

class TestBroadcastListener extends Command
{
    protected $signature = 'test:broadcast-listener';

    protected $description = 'Test broadcasting events';

    public function handle()
    {
        $chat = Chat::first();

        if ($chat) {
            broadcast(new ChatMessageSent($chat));
            $this->info('Broadcast event sent successfully.');
        } else {
            $this->error('No chat instance found to broadcast.');
        }
    }
}
