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
        // Tus alias de middleware registrados
        $middleware->alias([
            'role'         => \App\Http\Middleware\RoleMiddleware::class,
            'check.active' => \App\Http\Middleware\CheckActive::class,
        ]);
        
        // Middlewares globales para todas las rutas WEB
        $middleware->web(append: [
            \App\Http\Middleware\CheckActive::class,
            \App\Http\Middleware\PreventBackHistory::class, // <-- Aquí está el nuevo guardia
        ]);
    })
    ->withCommands([
        \App\Console\Commands\AnalyzeAllDocuments::class,
        \App\Console\Commands\LmsStats::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            if ($e->getStatusCode() === 403) {
                return response()->view('errors.403', [], 403);
            }
        });
    })
    ->create();