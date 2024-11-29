<?php

namespace App\Services\Prompt;

use dougkusanagi\LaravelAiChat\Services\Database\DatabaseSchemaService;

class PromptService
{
    public function __construct(
        private DatabaseSchemaService $schemaService
    ) {}

    public function generate(string $message, ?string $queryResult = null): string
    {
        $dbSchema = $this->schemaService->getSchema();
        $appName = config('app.name');

        $prompt = $this->getBasePrompt($appName, $dbSchema, $message);

        if ($queryResult !== null) {
            $prompt .= $this->getQueryResultPrompt($queryResult);
        }

        return $prompt;
    }

    private function getBasePrompt(string $appName, string $dbSchema, string $message): string
    {
        return <<<MARKDOWN
            You are a helpful assistant that answers questions about an application called "$appName".

            You must always respond in the same language as the user's message. Detect the language from the user's message and maintain that language in your response.

            You have access to the following database schema:
            <db_schema>$dbSchema</db_schema>

            User's message:
            <user_message>$message</user_message>

            Your response must be a valid JSON object in exactly this format (without any additional text):
            {"chat_response": "your response here", "sql_query": "your SQL query here or null"}

            Important rules for your response:
            1. Always use the same language as the user's message in your response
            2. Only include a SQL query if the user's question specifically requires database information
            3. For general questions or non-database queries, set "sql_query" to null
            4. Never include multiple queries, only one query per response is allowed

            Examples:
            1. For database questions (e.g., "How many users are registered?"):
               - Respond with a message like "I'll check the database for you"
               - Include the necessary SQL query

            2. For general questions (e.g., "What can you help me with?"):
               - Respond with a direct answer
               - Set sql_query to null

            3. For greetings or casual conversation:
               - Respond naturally
               - Set sql_query to null
        MARKDOWN;
    }

    private function getQueryResultPrompt(string $queryResult): string
    {
        return <<<MARKDOWN
            Query results:
            <query_results>$queryResult</query_results>

            Now, provide a complete and informative response based on these query results.
            Make sure to set 'sql_query' to null in your response as we already have the data.
            Remember to maintain the same language as used in the user's original message.
        MARKDOWN;
    }
}
