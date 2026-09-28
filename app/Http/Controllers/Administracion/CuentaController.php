<?php

namespace App\Http\Controllers\Administracion;

use App\Enums\EstadoSeguimiento;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\ActualizarContraseniaRequest;
use App\Models\Caracteristica;
use App\Models\Consulta;
use App\Models\Empresa;
use App\Models\Propiedad;
use App\Models\Tasacion;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use App\Models\Usuario;
use App\Models\Visita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CuentaController extends Controller
{
    public function editar(): View
    {
        $estadosCerrados = [
            EstadoSeguimiento::GANADA->value,
            EstadoSeguimiento::PERDIDA->value,
            EstadoSeguimiento::CERRADA->value,
        ];

        return view('administracion.cuenta.editar', [
            'empresa' => Empresa::query()->firstOrCreate([], [
                'nombre_comercial' => config('app.name'),
            ]),
            'administradoresActivos' => Usuario::query()->where('activo', true)->count(),
            'propiedadesTotales' => Propiedad::query()->count(),
            'tiposPropiedadActivos' => TipoPropiedad::query()->where('activo', true)->count(),
            'oportunidadesAbiertas' => Consulta::query()
                ->whereNotIn('estado_seguimiento', $estadosCerrados)
                ->count() + Tasacion::query()
                ->whereNotIn('estado_seguimiento', $estadosCerrados)
                ->count(),
            'visitasProximas' => Visita::query()->where('inicio', '>=', now())->count(),
            'ubicacionesActivas' => Ubicacion::query()->where('activa', true)->count(),
            'caracteristicasActivas' => Caracteristica::query()->where('activa', true)->count(),
        ]);
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
