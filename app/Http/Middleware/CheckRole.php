<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Maneja una solicitud entrante.
     *
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Si el usuario no está logueado o su rol no coincide con el requerido, lo mandamos al dashboard
        if (!$request->user() || $request->user()->role !== $role) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}