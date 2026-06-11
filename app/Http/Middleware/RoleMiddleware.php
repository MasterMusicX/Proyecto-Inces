<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Seguridad extra: Si por alguna razón no hay usuario logueado, pafuera al login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // 2. Comprobamos si el rol del usuario ESTÁ en la lista permitida
        if (!in_array($request->user()->role, $roles)) {
            // Si es un estudiante intentando entrar a la zona de profesor, le sacamos la tarjeta roja
            abort(403, '¡Epa! No tienes permisos de instructor o administrador para acceder a esta sección.');
        }

        // 3. Todo fino, lo dejamos pasar
        return $next($request);
    }
}