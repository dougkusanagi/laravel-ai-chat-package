<?php

namespace App\Services\Response;

class ResponseValidationService
{
	public function cleanJsonResponse(string $resultText): string
	{
		$cleanedText = trim(
			str_replace(["\n", "\r"], '', $resultText)
		);
		return trim($cleanedText, '"\'');
	}

	public function validateJsonResponse(?array $jsonResponse): void
	{
		if (is_null($jsonResponse)) {
			throw new \Exception('Invalid response from the AI provider. Please try again.');
		}

		if (!is_array($jsonResponse)) {
			throw new \Exception('Invalid response from the AI provider. Please try again.');
		}

		if (!array_key_exists('chat_response', $jsonResponse)) {
			throw new \Exception('Invalid response from the AI provider. Please try again.');
		}

		if (!array_key_exists('sql_query', $jsonResponse)) {
			throw new \Exception('Invalid response from the AI provider. Please try again.');
		}
	}
}
