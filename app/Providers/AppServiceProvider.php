<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Aquí mantenemos vivos tus servicios de IA (Gemini, Chatbot, etc.)
        $this->app->singleton(\App\Services\GeminiService::class);
        $this->app->singleton(\App\Services\ChatbotService::class);
        $this->app->singleton(\App\Services\DocumentProcessorService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 🔥 EL ANTÍDOTO PARA RAILWAY: Forzar HTTPS en producción 🔥
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}