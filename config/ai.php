<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | Supported: "openai", "claude", "local"
    |
    */
    'default_provider' => env('AI_PROVIDER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI Configuration
    |--------------------------------------------------------------------------
    */
    'openai_api_key' => env('OPENAI_API_KEY'),
    'model' => env('AI_MODEL', 'gpt-3.5-turbo'),

    /*
    |--------------------------------------------------------------------------
    | Claude Configuration
    |--------------------------------------------------------------------------
    */
    'claude_api_key' => env('CLAUDE_API_KEY'),
    'claude_model' => env('CLAUDE_MODEL', 'claude-3-sonnet-20240229'),

    /*
    |--------------------------------------------------------------------------
    | Local Model Configuration (Ollama, etc)
    |--------------------------------------------------------------------------
    */
    'local_endpoint' => env('AI_LOCAL_ENDPOINT', 'http://localhost:11434/api/generate'),
    'local_model' => env('AI_LOCAL_MODEL', 'llama2'),

    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    */
    'max_tokens' => env('AI_MAX_TOKENS', 500),
    'timeout' => env('AI_TIMEOUT', 30), // seconds
    'enabled' => env('AI_ENABLED', true),
];
