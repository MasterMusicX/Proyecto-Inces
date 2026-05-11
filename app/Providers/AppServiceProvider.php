<?php
namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public function render(): View
    {
        return view('layouts.app');
    }
}
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use App\View\Components\AppLayout;    

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register GeminiService as singleton
        $this->app->singleton(\App\Services\GeminiService::class);
        $this->app->singleton(\App\Services\ChatbotService::class);
        $this->app->singleton(\App\Services\DocumentProcessorService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzar el componente app-layout para evitar errores de caché en producción
        Blade::component('app-layout', AppLayout::class);

        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}