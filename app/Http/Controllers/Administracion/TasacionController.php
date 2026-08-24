<?php

namespace App\Http\Controllers\Administracion;

use App\Enums\EstadoSeguimiento;
use App\Enums\PrioridadOportunidad;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\ActualizarSeguimientoContactoRequest;
use App\Models\Tasacion;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TasacionController extends Controller
{
    public function listar(Request $solicitud): View
    {
        $busqueda = trim((string) $solicitud->query('buscar'));
        $estado = $solicitud->query('estado', 'todos');
        $lectura = $solicitud->query('lectura', 'todas');

        $tasaciones = Tasacion::query()
            ->with('tipoPropiedad')
            ->when($solicitud->user()->esAsesor(), fn ($consulta) => $consulta
                ->where('responsable_id', $solicitud->user()->id))
            ->when($busqueda !== '', function ($consulta) use ($busqueda) {
                $consulta->where(function ($subconsulta) use ($busqueda) {
                    $subconsulta
                        ->where('nombre', 'like', "%{$busqueda}%")
                        ->orWhere('email', 'like', "%{$busqueda}%")
                        ->orWhere('telefono', 'like', "%{$busqueda}%")
                        ->orWhere('ubicacion_texto', 'like', "%{$busqueda}%")
                        ->orWhere('direccion', 'like', "%{$busqueda}%")
                        ->orWhere('mensaje', 'like', "%{$busqueda}%")
                        ->orWhereHas('tipoPropiedad', fn ($tipos) => $tipos
                            ->where('nombre', 'like', "%{$busqueda}%"));
                });
            })
            ->when(
                $estado !== 'todos',
                fn ($consulta) => $consulta->where('estado_seguimiento', $estado)
            )
            ->when(
                $lectura === 'sin_leer',
                fn ($consulta) => $consulta->whereNull('leida_en')
            )
            ->when(
                $lectura === 'leidas',
                fn ($consulta) => $consulta->whereNotNull('leida_en')
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('administracion.tasaciones.listar', [
            'tasaciones' => $tasaciones,
            'busqueda' => $busqueda,
            'estado' => $estado,
            'lectura' => $lectura,
            'estadosSeguimiento' => EstadoSeguimiento::cases(),
        ]);
    }

    public function mostrar(Tasacion $tasacion): View
    {
        $this->autorizarAcceso($tasacion);
        $tasacion->marcarComoLeida();

        return view('administracion.tasaciones.mostrar', [
            'tasacion' => $tasacion->load([
                'tipoPropiedad',
                'responsable',
                'historialOportunidad.usuario',
            ]),
            'estadosSeguimiento' => EstadoSeguimiento::cases(),
            'prioridades' => PrioridadOportunidad::cases(),
            'responsables' => auth()->user()->esAsesor()
                ? Usuario::query()->whereKey(auth()->id())->get()
                : Usuario::query()->where('activo', true)->orderBy('nombre')->get(),
        ]);
    }

    public function actualizarSeguimiento(
        ActualizarSeguimientoContactoRequest $solicitud,
        Tasacion $tasacion
    ): RedirectResponse {
        $this->autorizarAcceso($tasacion);
        $datos = $solicitud->validated();
        if ($solicitud->user()->esAsesor()) {
            $datos['responsable_id'] = $solicitud->user()->id;
        }
        $tasacion->actualizarOportunidad($datos, $solicitud->user());

        return back()->with('estado', 'El seguimiento de la tasación se actualizó correctamente.');
    }

    private function autorizarAcceso(Tasacion $tasacion): void
    {
        abort_if(auth()->user()->esAsesor() && $tasacion->responsable_id !== auth()->id(), 403);
    }
}
