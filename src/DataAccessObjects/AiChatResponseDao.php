<?php

namespace dougkusanagi\LaravelAiChat\DataAccessObjects;

class AiChatResponseDao
{
    public function __construct(
        private bool $success,
        private string $message,
        private ?string $query,
    ) {}

    public function asArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'query' => $this->query,
        ];
    }
}
