<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Procesa el intento de login. Si el usuario tiene 2FA activado,
     * lo redirige al challenge de doble factor en lugar del dashboard.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->two_factor_enabled) {
            Auth::logout();
            session(['2fa:user:id' => $user->id, '2fa:remember' => $request->boolean('remember')]);

            return redirect()->route('two-factor.challenge');
        }

        activity('sesiones')
            ->causedBy($user)
            ->withProperties(['ip' => $request->ip()])
            ->log('Inicio de sesión');

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        activity('sesiones')->causedBy(Auth::user())->log('Cierre de sesión');

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
