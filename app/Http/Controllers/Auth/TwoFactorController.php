<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

/**
 * Gestiona la activación, confirmación y verificación del 2FA (TOTP).
 * Usa google2fa-laravel para generar el secreto y validar los códigos.
 */
class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Pantalla de challenge de 2FA que se muestra después del login
     * cuando el usuario tiene la doble autenticación activada.
     */
    public function challenge(): View|RedirectResponse
    {
        $user = auth()->user();
        
        // Si no hay usuario autenticado o no tiene 2FA activo, redirigir
        if (!$user || !$user->two_factor_secret || !$user->two_factor_enabled) {
            return redirect()->route('dashboard');
        }

        return view('auth.two-factor-challenge');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);

        $user = auth()->user();

        if (!$user || !$user->two_factor_secret || !$user->two_factor_enabled) {
            return redirect()->route('dashboard');
        }

        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->input('code'));

        if (!$valid) {
            throw ValidationException::withMessages(['code' => __('El código ingresado no es válido.')]);
        }

        session(['two-factor-authenticated' => true]);

        activity('sesiones')->causedBy($user)->log('Verificación 2FA exitosa');

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Genera un nuevo secreto y muestra el QR para activar 2FA
     * desde la pantalla de Perfil.
     */
    public function enable(Request $request): View
    {
        $user = $request->user();
        
        // Generar nuevo secreto
        $secreto = $this->google2fa->generateSecretKey();
        
        // Guardar el secreto sin encriptar (o encriptado si prefieres)
        $user->two_factor_secret = $secreto;
        $user->save();

        // Generar URL del QR
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secreto
        );

        return view('profile.two-factor-enable', compact('qrCodeUrl', 'secreto'));
    }

    public function confirm(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);

        $user = $request->user();
        
        if (!$user->two_factor_secret) {
            throw ValidationException::withMessages(['code' => __('No hay secreto de 2FA configurado.')]);
        }

        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->input('code'));

        if (!$valid) {
            throw ValidationException::withMessages(['code' => __('Código inválido, intenta de nuevo.')]);
        }

        $user->two_factor_enabled = true;
        $user->two_factor_confirmed_at = now();
        $user->save();

        return redirect()->route('profile.edit')->with('status', '2FA activado correctamente.');
    }

    public function disable(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return redirect()->route('profile.edit')->with('status', '2FA desactivado correctamente.');
    }
}