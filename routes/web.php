<?php

use Illuminate\Support\Facades\Route;
use dougkusanagi\LaravelAiChat\Http\Controllers\AiChatController;

Route::group([
    'prefix' => 'api',
    'middleware' => ['web', 'api'],
], function () {
    Route::post('/ai-chat', [AiChatController::class, 'chat'])->name('ai-chat.chat');
});
