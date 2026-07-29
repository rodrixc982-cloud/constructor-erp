<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cierra la sesión automáticamente si un Super Administrador
 * desactiva la cuenta del usuario mientras tenía una sesión abierta.
 */
class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && ! Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();

            return redirect()->route('login')->withErrors([
                'email' => __('Tu cuenta ha sido desactivada. Contacta al administrador.'),
            ]);
        }

        return $next($request);
    }
}
