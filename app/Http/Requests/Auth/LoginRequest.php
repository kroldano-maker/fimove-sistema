<?php

namespace App\Http\Requests\Auth;

use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotBlocked();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            $this->registrarIntentoFallido();

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $usuario = Auth::user();

        if (! $usuario->estado) {
            Auth::logout();

            Bitacora::registrar(
                'Acceso denegado',
                'Intento de inicio de sesión con cuenta desactivada',
                $usuario->id,
                $usuario->email
            );

            throw ValidationException::withMessages([
                'email' => 'Esta cuenta está desactivada. Contactá al administrador del sistema.',
            ]);
        }

        $usuario->forceFill([
            'intentos_fallidos' => 0,
            'bloqueado_hasta' => null,
        ])->save();

        Bitacora::registrar('Inicio de sesión', 'Inicio de sesión exitoso', $usuario->id, $usuario->email);
    }

    /**
     * Verifica que la cuenta no esté bloqueada temporalmente por intentos fallidos.
     *
     * @throws ValidationException
     */
    protected function ensureIsNotBlocked(): void
    {
        $usuario = User::where('email', $this->string('email'))->first();

        if ($usuario && $usuario->bloqueado_hasta && $usuario->bloqueado_hasta->isFuture()) {
            $segundos = now()->diffInSeconds($usuario->bloqueado_hasta);

            Bitacora::registrar(
                'Acceso bloqueado',
                'Intento de inicio de sesión mientras la cuenta está bloqueada temporalmente',
                $usuario->id,
                $usuario->email
            );

            throw ValidationException::withMessages([
                'email' => 'Demasiados intentos fallidos. Tu cuenta está bloqueada temporalmente, intentá de nuevo en ' . max(1, ceil($segundos / 60)) . ' minuto(s).',
            ]);
        }
    }

    /**
     * Registra un intento fallido y bloquea la cuenta si llega al límite.
     */
    protected function registrarIntentoFallido(): void
    {
        $usuario = User::where('email', $this->string('email'))->first();

        Bitacora::registrar(
            'Inicio de sesión fallido',
            'Contraseña incorrecta',
            $usuario->id ?? null,
            $this->string('email')
        );

        if (! $usuario) {
            return;
        }

        $usuario->increment('intentos_fallidos');

        if ($usuario->intentos_fallidos >= 5) {
            $usuario->forceFill([
                'bloqueado_hasta' => now()->addMinutes(5),
            ])->save();

            Bitacora::registrar(
                'Bloqueo temporal',
                'Cuenta bloqueada 5 minutos por múltiples intentos fallidos',
                $usuario->id,
                $usuario->email
            );
        }
    }
}