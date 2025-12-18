<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    protected string $provider;

    protected ?string $apiKey;

    protected ?string $baseUrl;

    protected ?string $projectId;

    public function __construct()
    {
        $this->provider = config('services.safenest.chatbot.provider', 'dialogflow');
        $this->apiKey = config('services.safenest.chatbot.api_key');
        $this->baseUrl = config('services.safenest.chatbot.base_url');
        $this->projectId = config('services.safenest.chatbot.project_id');
    }

    public function sendMessage(string $message, ?string $sessionId = null, ?string $language = 'en'): array
    {
        if (! config('safenest.ai.chatbot.enabled')) {
            return [
                'success' => false,
                'message' => 'Chatbot service is currently disabled.',
                'error' => 'service_disabled',
            ];
        }

        $sessionId = $sessionId ?? session()->getId();

        if ($this->provider === 'dialogflow') {
            return $this->sendDialogflowMessage($message, $sessionId, $language);
        } elseif ($this->provider === 'openai' || $this->provider === 'chatgpt') {
            return $this->sendOpenAIMessage($message, $sessionId, $language);
        } else {
            return [
                'success' => false,
                'message' => 'Chatbot provider not configured. Please set SAFENEST_CHATBOT_PROVIDER in .env',
                'error' => 'provider_not_configured',
            ];
        }
    }

    protected function sendDialogflowMessage(string $message, string $sessionId, string $language): array
    {
        if (! $this->apiKey || ! $this->projectId) {
            Log::warning('Dialogflow API key or project ID not configured');

            return [
                'success' => false,
                'message' => 'Dialogflow API key or project ID not configured. Please set SAFENEST_CHATBOT_API_KEY and SAFENEST_CHATBOT_PROJECT_ID in .env',
                'error' => 'api_not_configured',
            ];
        }

        try {
            $url = "{$this->baseUrl}/v2/projects/{$this->projectId}/agent/sessions/{$sessionId}:detectIntent";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post($url, [
                'queryInput' => [
                    'text' => [
                        'text' => $message,
                        'languageCode' => $language,
                    ],
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $fulfillmentText = $data['queryResult']['fulfillmentText'] ?? 'I apologize, but I could not process your request.';
                $intent = $data['queryResult']['intent']['displayName'] ?? null;

                return [
                    'success' => true,
                    'message' => $fulfillmentText,
                    'intent' => $intent,
                    'confidence' => $data['queryResult']['intentDetectionConfidence'] ?? 0,
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to get response from Dialogflow API.',
                'error' => 'api_error',
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Dialogflow API error: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Error connecting to Dialogflow API: '.$e->getMessage(),
                'error' => 'connection_error',
            ];
        }
    }

    protected function sendOpenAIMessage(string $message, string $sessionId, string $language): array
    {
        if (! $this->apiKey) {
            Log::warning('OpenAI API key not configured');

            return [
                'success' => false,
                'message' => 'OpenAI API key not configured. Please set SAFENEST_CHATBOT_API_KEY in .env',
                'error' => 'api_not_configured',
            ];
        }

        try {
            $url = $this->baseUrl ?? 'https://api.openai.com/v1/chat/completions';

            $systemPrompt = 'You are a helpful security expert assistant for SafeNest, an AI-powered smart home security store. Help customers choose the best security devices, answer setup questions, and provide security guidance. Be friendly, professional, and knowledgeable about home security products.';

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post($url, [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $message],
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? 'I apologize, but I could not process your request.';

                return [
                    'success' => true,
                    'message' => $content,
                    'intent' => null,
                    'confidence' => 0.9,
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to get response from OpenAI API.',
                'error' => 'api_error',
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('OpenAI API error: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Error connecting to OpenAI API: '.$e->getMessage(),
                'error' => 'connection_error',
            ];
        }
    }
}
