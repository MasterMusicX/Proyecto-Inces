<?php
return [
    'gemini' => [
        'api_key'     => env('GEMINI_API_KEY'),
        'model'       => env('GEMINI_MODEL', 'gemini-pro'),
        'max_tokens'  => env('GEMINI_MAX_TOKENS', 2048),
        'temperature' => env('GEMINI_TEMPERATURE', 0.7),
    ],
];
