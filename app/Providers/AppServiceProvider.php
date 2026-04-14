<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register GeminiService as singleton
        $this->app->singleton(\App\Services\GeminiService::class);
        $this->app->singleton(\App\Services\ChatbotService::class);
        $this->app->singleton(\App\Services\DocumentProcessorService::class);
    }

    public function boot(): void
    {
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
