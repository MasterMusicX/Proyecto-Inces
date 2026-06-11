<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:      __DIR__.'/../routes/web.php',
        api:      __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health:   '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. Alias de middlewares (Para llamarlos en web.php cuando sea necesario)
        $middleware->alias([
            'role'         => \App\Http\Middleware\RoleMiddleware::class,
            'check.active' => \App\Http\Middleware\CheckActive::class,
        ]);
        
        // 2. Middlewares globales para todas las rutas WEB (Se aplican a TODO)
        $middleware->web(append: [
            // 🚨 Quitamos CheckActive de aquí para que no bloquee el Login/Register 🚨
            \App\Http\Middleware\PreventBackHistory::class, 
        ]);
        
    })
    ->withCommands([
        \App\Console\Commands\AnalyzeAllDocuments::class,
        \App\Console\Commands\LmsStats::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        
        // Manejo de errores personalizados (Página 403 bonita en vez de la de Laravel)
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            if ($e->getStatusCode() === 403) {
                return response()->view('errors.403', [], 403);
            }
        });
        
    })
    ->create();