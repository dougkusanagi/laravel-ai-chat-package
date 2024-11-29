<?php

namespace App\Services\AiProviders;

use dougkusanagi\LaravelAiChat\DataAccessObjects\AiChatResponseDao;
use dougkusanagi\LaravelAiChat\Interfaces\AiChatProvider;
use dougkusanagi\LaravelAiChat\Services\Response\ResponseValidationService;
use Illuminate\Support\Facades\Log;
use Gemini;

class GeminiAiChatProvider implements AiChatProvider
{
    public function __construct(
        private ResponseValidationService $responseValidator
    ) {}

    public function chat(string $prompt): AiChatResponseDao
    {
        try {
            $apiKey = $this->getApiKey();
            $client = Gemini::client($apiKey);

            $result = $client->geminiPro()->generateContent($prompt);
            $cleanedText = $this->responseValidator->cleanJsonResponse($result->text());
            $jsonResponse = json_decode($cleanedText, true);

            $this->responseValidator->validateJsonResponse($jsonResponse);

            return new AiChatResponseDao(
                success: true,
                message: $jsonResponse['chat_response'],
                query: $jsonResponse['sql_query']
            );
        } catch (\Throwable $th) {
            Log::error($th);
            return new AiChatResponseDao(
                success: false,
                message: $th->getMessage() ?: 'Invalid response from the AI provider. Please try again later.',
                query: null
            );
        }
    }

    private function getApiKey(): string
    {
        $apiKey = config('services.gemini.api_key') ?? getenv('GEMINI_API_KEY');

        if (!$apiKey) {
            throw new \Exception('Gemini API key is not configured. Please check your environment variables.');
        }

        return $apiKey;
    }
}
