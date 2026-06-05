<?php

return [

    // ==========================================
    // 🔥 MOTOR DE INTELIGENCIA ARTIFICIAL (GROQ / LLAMA 3)
    // ==========================================
    'groq' => [
        'api_key'     => env('GROQ_API_KEY'),
        'model'       => env('GROQ_MODEL', 'llama3-8b-8192'),
        'max_tokens'  => env('GROQ_MAX_TOKENS', 800),
        'temperature' => env('GROQ_TEMPERATURE', 0.1), // Temperatura baja (0.1) para que el bot del INCES sea estricto y no invente cosas
    ],

    // ==========================================
    // 🔥 GESTIÓN DE IMÁGENES EN LA NUBE (IMGBB)
    // ==========================================
    'imgbb' => [
        'key' => env('IMGBB_API_KEY'),
    ],

];