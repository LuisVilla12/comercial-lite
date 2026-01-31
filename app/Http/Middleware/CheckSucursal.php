<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSucursal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

    // Admin entra a todo
    if ($user->tipo === 1) {
        return $next($request);
    }

    $sucursal = $request->route('sucursal'); //  Modelo Sucursal

    if ($user->sucursal_id !== $sucursal->id) {
        abort(403, 'No tienes acceso a esta sucursal');
    }

    return $next($request);
    }
}
