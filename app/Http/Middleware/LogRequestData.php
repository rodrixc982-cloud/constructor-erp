<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LogRequestData
{
    public function handle(Request $request, Closure $next)
    {
        // Guardar datos del request en sesión para que Activity Log los capture
        session([
            'request_ip' => $request->ip(),
            'request_user_agent' => $request->userAgent(),
            'request_url' => $request->fullUrl(),
            'request_method' => $request->method(),
        ]);

        return $next($request);
    }
}