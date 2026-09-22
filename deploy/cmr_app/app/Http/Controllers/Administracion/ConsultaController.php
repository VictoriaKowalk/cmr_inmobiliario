<?php

namespace App\Http\Controllers\Administracion;

use App\Enums\EstadoSeguimiento;
use App\Enums\PrioridadOportunidad;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\ActualizarSeguimientoContactoRequest;
use App\Models\Consulta;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultaController extends Controller
{
    public function listar(Request $solicitud): View
    {
        $busqueda = trim((string) $solicitud->query('buscar'));
        $estado = $solicitud->query('estado', 'todos');
        $lectura = $solicitud->query('lectura', 'todas');

        $consultas = Consulta::query()
            ->with(['propiedad', 'operacionPropiedad'])
            ->when($solicitud->user()->esAsesor(), fn ($consulta) => $consulta
                ->where('responsable_id', $solicitud->user()->id))
            ->when($busqueda !== '', function ($consulta) use ($busqueda) {
                $consulta->where(function ($subconsulta) use ($busqueda) {
                    $subconsulta
                        ->where('nombre', 'like', "%{$busqueda}%")
                        ->orWhere('email', 'like', "%{$busqueda}%")
                        ->orWhere('telefono', 'like', "%{$busqueda}%")
                        ->orWhere('mensaje', 'like', "%{$busqueda}%")
                        ->orWhereHas('propiedad', function ($propiedades) use ($busqueda) {
                            $propiedades
                                ->where('codigo_interno', 'like', "%{$busqueda}%")
                                ->orWhere('titulo', 'like', "%{$busqueda}%");
                        });
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

        return view('administracion.consultas.listar', [
            'consultas' => $consultas,
            'busqueda' => $busqueda,
            'estado' => $estado,
            'lectura' => $lectura,
            'estadosSeguimiento' => EstadoSeguimiento::cases(),
        ]);
    }

    public function mostrar(Consulta $consulta): View
    {
        $this->autorizarAcceso($consulta);
        $consulta->marcarComoLeida();

        return view('administracion.consultas.mostrar', [
            'consulta' => $consulta->load([
                'propiedad',
                'operacionPropiedad',
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
        Consulta $consulta
    ): RedirectResponse {
        $this->autorizarAcceso($consulta);
        $datos = $solicitud->validated();
        if ($solicitud->user()->esAsesor()) {
            $datos['responsable_id'] = $solicitud->user()->id;
        }
        $consulta->actualizarOportunidad($datos, $solicitud->user());

        return back()->with('estado', 'El seguimiento de la consulta se actualizó correctamente.');
    }

    private function autorizarAcceso(Consulta $consulta): void
    {
        abort_if(auth()->user()->esAsesor() && $consulta->responsable_id !== auth()->id(), 403);
    }
}
