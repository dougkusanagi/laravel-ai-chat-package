<?php

use dougkusanagi\LaravelAiChat\Http\Controllers\AiChatController;

Route::post('/api/ai-chat', [AiChatController::class, 'chat']);
