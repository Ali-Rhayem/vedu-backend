<?php

namespace App\Http\Controllers;

use App\Models\ChromeExtensionSummary;
use App\Http\Requests\StoreChromeExtensionSummaryRequest;
use App\Http\Requests\UpdateChromeExtensionSummaryRequest;
use App\Models\Chat;

class ChromeExtensionSummaryController extends Controller
{
    public function index()
    {
        $summaries = ChromeExtensionSummary::all();
        return response()->json([
            "summaries" => $summaries
        ], 200);
    }


    public function generateSummary(Chat $chat, MessageController $messageController)
    {
        $response = $messageController->index($chat->id);
        $messages = json_decode($response->getContent(), true);
    
        $chatContent = implode("\n", array_column($messages, 'message'));
    
        $openAIKey = config('services.openai.key');
        
        if (!$openAIKey) {
            return response()->json(['error' => 'OpenAI API key not found'], 500);
        }
    
        $client = new \GuzzleHttp\Client();
    
        try {
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $openAIKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant that summarizes chat conversations.'],
                        ['role' => 'user', 'content' => "Summarize this chat:\n" . $chatContent],
                    ],
                    'max_tokens' => 150,
                ],
            ]);
    
            $responseBody = json_decode($response->getBody(), true);
            $summary = $responseBody['choices'][0]['message']['content'];
    
            $summaryRecord = ChromeExtensionSummary::where('chat_id', $chat->id)->first();
    
            if ($summaryRecord) {
                $summaryRecord->update(['summary' => $summary]);
            } else {
                $summaryRecord = ChromeExtensionSummary::create([
                    'chat_id' => $chat->id,
                    'summary' => $summary,
                ]);
            }
    
            return response()->json([
                'summary' => $summaryRecord,
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json(['error' => 'Failed to generate summary'], 500);
        }
    }
    


    public function show(ChromeExtensionSummary $chromeExtensionSummary)
    {
        return response()->json([
            "summary" => $chromeExtensionSummary
        ]);
    }

    public function destroy(ChromeExtensionSummary $chromeExtensionSummary)
    {
        $chromeExtensionSummary->delete();
        return response()->json(['message' => 'Summary deleted successfully']);
    }
}
