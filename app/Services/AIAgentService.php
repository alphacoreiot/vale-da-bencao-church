<?php

namespace App\Services;

use App\Models\Section;
use App\Models\AIConversation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIAgentService
{
    /**
     * Process a user message and get AI response.
     */
    public function chat(
        Section $section,
        string $message,
        string $userSession
    ): array {
        // Get or create conversation
        $conversation = AIConversation::firstOrCreate(
            [
                'section_id' => $section->id,
                'user_session' => $userSession,
            ],
            [
                'messages' => [],
                'context' => $this->buildContext($section),
            ]
        );

        // Add user message
        $conversation->addMessage('user', $message);

        // Get AI response
        $response = $this->getAIResponse($section, $conversation);

        // Add assistant message
        $conversation->addMessage('assistant', $response);

        return [
            'message' => $response,
            'conversation_id' => $conversation->id,
        ];
    }

    /**
     * Get AI response from the configured provider.
     */
    private function getAIResponse(Section $section, AIConversation $conversation): string
    {
        $config = $section->getAiAgentConfig();

        if (!$config['enabled']) {
            return 'Desculpe, o assistente de IA não está disponível para esta seção no momento.';
        }

        $provider = config('ai.default_provider', 'openai');

        try {
            return match ($provider) {
                'openai' => $this->getOpenAIResponse($config, $conversation),
                'claude' => $this->getClaudeResponse($config, $conversation),
                'local' => $this->getLocalResponse($config, $conversation),
                default => $this->getFallbackResponse(),
            };
        } catch (\Exception $e) {
            Log::error('AI Agent error: ' . $e->getMessage());
            return $this->getFallbackResponse();
        }
    }

    /**
     * Get response from OpenAI API.
     */
    private function getOpenAIResponse(array $config, AIConversation $conversation): string
    {
        $apiKey = config('ai.openai_api_key');

        if (!$apiKey) {
            return $this->getFallbackResponse();
        }

        $messages = $this->formatMessagesForAPI($config, $conversation);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => config('ai.model', 'gpt-3.5-turbo'),
            'messages' => $messages,
            'max_tokens' => config('ai.max_tokens', 500),
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? $this->getFallbackResponse();
        }

        throw new \Exception('OpenAI API request failed: ' . $response->status());
    }

    /**
     * Get response from Claude API.
     */
    private function getClaudeResponse(array $config, AIConversation $conversation): string
    {
        $apiKey = config('ai.claude_api_key');

        if (!$apiKey) {
            return $this->getFallbackResponse();
        }

        $messages = $this->formatMessagesForAPI($config, $conversation);

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'Content-Type' => 'application/json',
            'anthropic-version' => '2023-06-01',
        ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
            'model' => config('ai.claude_model', 'claude-3-sonnet-20240229'),
            'messages' => $messages,
            'max_tokens' => config('ai.max_tokens', 500),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['content'][0]['text'] ?? $this->getFallbackResponse();
        }

        throw new \Exception('Claude API request failed: ' . $response->status());
    }

    /**
     * Get response from local model (e.g., Ollama).
     */
    private function getLocalResponse(array $config, AIConversation $conversation): string
    {
        $endpoint = config('ai.local_endpoint');

        if (!$endpoint) {
            return $this->getFallbackResponse();
        }

        $messages = $this->formatMessagesForAPI($config, $conversation);
        $prompt = $this->convertMessagesToPrompt($messages);

        $response = Http::timeout(30)->post($endpoint, [
            'model' => config('ai.local_model', 'llama2'),
            'prompt' => $prompt,
            'stream' => false,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['response'] ?? $this->getFallbackResponse();
        }

        throw new \Exception('Local AI request failed: ' . $response->status());
    }

    /**
     * Format messages for API.
     */
    private function formatMessagesForAPI(array $config, AIConversation $conversation): array
    {
        $messages = [
            [
                'role' => 'system',
                'content' => $config['prompts']['system'] . "\n\n" . $config['prompts']['context'],
            ],
        ];

        foreach ($conversation->messages as $msg) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['content'],
            ];
        }

        return $messages;
    }

    /**
     * Convert messages to a simple prompt string.
     */
    private function convertMessagesToPrompt(array $messages): string
    {
        $prompt = '';
        foreach ($messages as $message) {
            $role = ucfirst($message['role']);
            $prompt .= "{$role}: {$message['content']}\n\n";
        }
        $prompt .= "Assistant: ";
        return $prompt;
    }

    /**
     * Build context for the AI agent.
     */
    private function buildContext(Section $section): array
    {
        return [
            'section_name' => $section->name,
            'section_description' => $section->description,
            'published_contents_count' => $section->publishedContents()->count(),
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Get fallback response when AI is unavailable.
     */
    private function getFallbackResponse(): string
    {
        return 'Desculpe, estou com dificuldades para processar sua mensagem no momento. Por favor, tente novamente mais tarde ou entre em contato conosco diretamente.';
    }

    /**
     * Clear conversation history.
     */
    public function clearConversation(string $userSession, Section $section): bool
    {
        return AIConversation::where('user_session', $userSession)
            ->where('section_id', $section->id)
            ->delete() > 0;
    }

    /**
     * Get conversation history.
     */
    public function getConversationHistory(string $userSession, Section $section): ?AIConversation
    {
        return AIConversation::where('user_session', $userSession)
            ->where('section_id', $section->id)
            ->first();
    }
}
