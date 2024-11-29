<?php

namespace dougkusanagi\LaravelAiChat;

use dougkusanagi\LaravelAiChat\Database\QueryExecutionService;
use dougkusanagi\LaravelAiChat\Prompt\PromptService;
use dougkusanagi\LaravelAiChat\Services\AiChatService;
use dougkusanagi\LaravelAiChat\Interfaces\AiChatProvider;
use dougkusanagi\LaravelAiChat\Services\AiProviders\GeminiAiChatProvider;
use Illuminate\Support\ServiceProvider;

class AiChatServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->bind(AiChatProvider::class, GeminiAiChatProvider::class);

		$this->app->singleton(AiChatService::class, function ($app) {
			return new AiChatService(
				$app->make(AiChatProvider::class),
				$app->make(PromptService::class),
				$app->make(QueryExecutionService::class)
			);
		});

		$this->mergeConfigFrom(__DIR__ . '/../config/ai-chat.php', 'ai-chat');
	}

	public function boot(): void
	{
		$this->publishes([
			__DIR__ . '/../config/ai-chat.php' => config_path('ai-chat.php'),
		], 'ai-chat-config');

		$this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
	}
}
