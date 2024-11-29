<?php

namespace dougkusanagi\LaravelAiChat\Services;

use dougkusanagi\LaravelAiChat\DataAccessObjects\AiChatResponseDao;
use dougkusanagi\LaravelAiChat\Interfaces\AiChatProvider;
use dougkusanagi\LaravelAiChat\Services\Database\QueryExecutionService;
use dougkusanagi\LaravelAiChat\Services\Prompt\PromptService;
use Illuminate\Support\Facades\Log;

class AiChatService
{
    public function __construct(
        private AiChatProvider $provider,
        private PromptService $promptService,
        private QueryExecutionService $queryExecutionService
    ) {}

    public function chat(string $message, ?string $query = null): AiChatResponseDao
    {
        $queryResult = null;

        if ($query !== null) {
            try {
                $queryResult = $this->queryExecutionService->execute($query);
            } catch (\Exception $e) {
                Log::error('Query execution failed: ' . $e->getMessage());
                return new AiChatResponseDao(
                    success: false,
                    message: 'Failed to execute database query. Please try again.',
                    query: null
                );
            }
        }

        $prompt = $this->promptService->generate($message, $queryResult);
        return $this->provider->chat($prompt);
    }
}
