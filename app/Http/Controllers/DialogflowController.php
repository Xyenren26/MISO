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
            // Call Google Gemini API instead of OpenAI
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . env('GEMINI_API_KEY'), [
                'contents' => [['parts' => [['text' => $queryText]]]]
            ]);
            \Log::info('Gemini API Response', $response->json()); // Log API response

            $responseText = $response->json('candidates.0.content.parts.0.text', 'Sorry, I do not understand.');
        } else {
            $responseText = "I understood your intent: $intentName.";
        }

        return response()->json([
            'fulfillmentText' => $responseText
        ]);
    }
}
