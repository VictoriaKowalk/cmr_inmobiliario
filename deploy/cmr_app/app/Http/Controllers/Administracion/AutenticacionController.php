<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\IngresarAdministradorRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AutenticacionController extends Controller
{
    public function mostrarIngreso(): View
    {
        return view('administracion.autenticacion.ingreso');
    }

    public function ingresar(
        IngresarAdministradorRequest $solicitud
    ): RedirectResponse {
        $credenciales = [
            'email' => $solicitud->string('email')->toString(),
            'password' => $solicitud->string('contrasenia')->toString(),
            'activo' => true,
        ];

        if (! Auth::attempt($credenciales, $solicitud->boolean('recordarme'))) {
            return back()
                ->withInput($solicitud->only('email'))
                ->withErrors([
                    'email' => 'El correo o la contraseña no son correctos.',
                ]);
        }

        $solicitud->session()->regenerate();
        $solicitud->user()->forceFill([
            'ultimo_acceso_en' => now(),
        ])->save();

        return redirect()->intended(route('administracion.dashboard'));
    }

    public function cerrarSesion(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('estado', 'La sesión se cerró correctamente.');
    }
}
