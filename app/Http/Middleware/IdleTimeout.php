<?php

namespace App\Http\Middleware;

use App\Models\Bitacora;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IdleTimeout
{
    protected int $minutos = 10;

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $ultimaActividad = $request->session()->get('ultima_actividad');

            if ($ultimaActividad && now()->diffInMinutes($ultimaActividad, true) >= $this->minutos) {
                $usuario = Auth::user();

                Bitacora::registrar(
                    'Sesión expirada',
                    'Cierre de sesión automático por inactividad',
                    $usuario->id,
                    $usuario->email
                );

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('status', 'Tu sesión expiró por inactividad. Iniciá sesión de nuevo.');
            }

            $request->session()->put('ultima_actividad', now());
        }

        return $next($request);
    }
}