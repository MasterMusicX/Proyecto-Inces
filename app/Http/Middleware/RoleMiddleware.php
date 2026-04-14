<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    // El secreto es poner el ...$roles al final para que reciba uno o varios
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!in_array($request->user()->role, $roles)) {
            return redirect('login');
        }

        // Comprobamos si el rol del usuario está en la lista permitida
        if (!in_array($request->user()->role, $roles)){
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}