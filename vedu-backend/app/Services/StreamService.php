<?php


namespace App\Services;

use GetStream\StreamChat\Client as StreamChatClient;

class StreamService
{
    protected $client;

    public function __construct()
    {
        $apiKey = env('STREAM_API_KEY');
        $apiSecret = env('STREAM_API_SECRET');

        $this->client = new StreamChatClient($apiKey, $apiSecret);
    }

    public function generateToken($userId)
    {

        $token = $this->client->createToken($userId);

        return $token;
    }
}
