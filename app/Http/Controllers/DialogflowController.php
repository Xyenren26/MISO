<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DialogflowController extends Controller
{
    public function handleWebhook(Request $request)
    {
        \Log::info('Dialogflow Webhook Called', $request->all()); // Debugging

        $queryText = $request->input('queryResult.queryText');
        $intentName = $request->input('queryResult.intent.displayName');

        if ($intentName === 'Default Fallback Intent') {
            $openAiApiKey = env('OPENAI_API_KEY');
            $openAiUrl = "https://api.openai.com/v1/chat/completions";
        
            $response = Http::withHeaders([
                'Authorization' => "Bearer $openAiApiKey",
                'Content-Type' => 'application/json'
            ])->post($openAiUrl, [
                'model' => 'gpt-4', // or 'gpt-3.5-turbo'
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $queryText]
                ]
            ]);

            // Log the OpenAI response
            \Log::info('OpenAI API Response', $response->json());

            if ($response->successful()) {
                $responseText = data_get($response->json(), 'choices.0.message.content', 'Sorry, I do not understand.');
            } else {
                $responseText = 'Sorry, I am currently unavailable.';
                \Log::error('OpenAI API Error', $response->json());
            }
        } else {
            $responseText = "I understood your intent: $intentName.";
        }

        return response()->json([
            'fulfillmentText' => $responseText
        ]);
    }
}
