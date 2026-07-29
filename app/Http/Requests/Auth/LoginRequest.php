<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Intenta autenticar la solicitud, aplicando el bloqueo por
     * intentos fallidos (configurable vía AUTH_MAX_LOGIN_ATTEMPTS
     * y AUTH_LOCKOUT_MINUTES en el .env).
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = User::where('email', $this->input('email'))->first();

        if ($user && $user->isLocked()) {
            throw ValidationException::withMessages([
                'email' => __('Tu cuenta está bloqueada temporalmente por múltiples intentos fallidos. Intenta de nuevo más tarde.'),
            ]);
        }

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            $this->registrarIntentoFallido($user);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        if ($user && ! $user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('Tu cuenta ha sido desactivada. Contacta al administrador.'),
            ]);
        }

        // Login correcto: reiniciar contador de intentos fallidos.
        RateLimiter::clear($this->throttleKey());
        $user?->forceFill(['failed_login_attempts' => 0, 'locked_until' => null])->saveQuietly();
    }

    /**
     * Incrementa el contador de intentos fallidos del usuario y lo
     * bloquea temporalmente si supera el máximo configurado.
     */
    protected function registrarIntentoFallido(?User $user): void
    {
        if (! $user) {
            return;
        }

        $max = (int) config('constructor.auth.max_login_attempts', env('AUTH_MAX_LOGIN_ATTEMPTS', 5));
        $minutos = (int) config('constructor.auth.lockout_minutes', env('AUTH_LOCKOUT_MINUTES', 15));

        $user->increment('failed_login_attempts');

        if ($user->failed_login_attempts >= $max) {
            $user->forceFill([
                'locked_until' => now()->addMinutes($minutos),
            ])->save();
        }
    }

    /**
     * Aplica el rate limiter general (por IP + email) antes del bloqueo por usuario.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
