<?php

namespace dougkusanagi\LaravelAiChat\Http\Controllers;

use Illuminate\Routing\Controller;
use dougkusanagi\LaravelAiChat\Services\AiChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    public function __construct(
        private AiChatService $aiChatService
    ) {}

    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'query' => 'nullable|string',
        ]);

        try {
            $response = $this->aiChatService
                ->chat($validated['message'], $validated['query'] ?? null)
                ->asArray();

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('AI Chat error: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'query' => null
            ], 500);
        }
    }
}
