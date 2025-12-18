<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function __construct(
        private ChatbotService $chatbotService
    ) {}

    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'session_id' => ['nullable', 'string'],
            'language' => ['nullable', 'string', 'max:10'],
        ]);

        $response = $this->chatbotService->sendMessage(
            $validated['message'],
            $validated['session_id'] ?? session()->getId(),
            $validated['language'] ?? 'en'
        );

        return response()->json($response);
    }
}
