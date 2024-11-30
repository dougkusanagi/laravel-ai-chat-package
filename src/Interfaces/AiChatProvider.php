<?php

namespace dougkusanagi\LaravelAiChat\Interfaces;

use dougkusanagi\LaravelAiChat\DataAccessObjects\AiChatResponseDao;

interface AiChatProvider
{
    public function chat(string $message): AiChatResponseDao;
}
