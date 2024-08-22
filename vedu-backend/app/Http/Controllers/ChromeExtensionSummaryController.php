<?php

namespace App\Http\Controllers;

use App\Models\ChromeExtensionSummary;
use App\Http\Requests\StoreChromeExtensionSummaryRequest;
use App\Http\Requests\UpdateChromeExtensionSummaryRequest;
use App\Models\Chat;

class ChromeExtensionSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $summaries = ChromeExtensionSummary::all();
        return response()->json([
            "summaries" => $summaries
        ], 200);
    }


    public function generateSummary(Chat $chat)
    {
        $messages = $chat->messages()->pluck('message')->toArray();
        $chatContent = implode("\n", $messages);

        $openAIKey = env('OPENAI_API_KEY');
        $client = new \GuzzleHttp\Client();

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
    public function store(StoreChromeExtensionSummaryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ChromeExtensionSummary $chromeExtensionSummary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChromeExtensionSummary $chromeExtensionSummary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChromeExtensionSummaryRequest $request, ChromeExtensionSummary $chromeExtensionSummary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChromeExtensionSummary $chromeExtensionSummary)
    {
        //
    }
}
