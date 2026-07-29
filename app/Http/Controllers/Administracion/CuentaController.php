<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\ActualizarContraseniaRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CuentaController extends Controller
{
    public function editar(): View
    {
        return view('administracion.cuenta.editar');
    }

    public function actualizarContrasenia(
        ActualizarContraseniaRequest $solicitud
    ): RedirectResponse {
        $solicitud->user()->update([
            'contrasenia' => Hash::make(
                $solicitud->string('contrasenia')->toString()
            ),
        ]);

        $solicitud->session()->regenerate();

        return back()->with(
            'estado',
            'La contraseña se actualizó correctamente.'
        );
    }
}
