<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\GuardarUbicacionRequest;
use App\Models\Ubicacion;
use App\Services\ServicioUbicaciones;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UbicacionController extends Controller
{
    public function listar(Request $solicitud): View
    {
        $busqueda = trim((string) $solicitud->query('buscar'));
        $estado = $solicitud->query('estado', 'todas');

        $ubicaciones = Ubicacion::query()
            ->when(
                $busqueda !== '',
                fn ($consulta) => $consulta
                    ->where('nombre_completo', 'like', "%{$busqueda}%")
            )
            ->when(
                $estado === 'activas',
                fn ($consulta) => $consulta->where('activa', true)
            )
            ->when(
                $estado === 'inactivas',
                fn ($consulta) => $consulta->where('activa', false)
            )
            ->orderBy('nombre_completo')
            ->paginate(15)
            ->withQueryString();

        return view('administracion.ubicaciones.listar', [
            'ubicaciones' => $ubicaciones,
            'busqueda' => $busqueda,
            'estado' => $estado,
        ]);
    }

    public function crear(): View
    {
        return view(
            'administracion.ubicaciones.crear',
            $this->sugerenciasFormulario()
        );
    }

    public function guardar(
        GuardarUbicacionRequest $solicitud,
        ServicioUbicaciones $servicioUbicaciones
    ): RedirectResponse {
        $servicioUbicaciones->crearUbicacion($solicitud->validated());

        return redirect()
            ->route('administracion.ubicaciones.listar')
            ->with('estado', 'La ubicación se creó correctamente.');
    }

    public function editar(Ubicacion $ubicacion): View
    {
        return view('administracion.ubicaciones.editar', [
            'ubicacion' => $ubicacion,
            ...$this->sugerenciasFormulario(),
        ]);
    }

    public function actualizar(
        GuardarUbicacionRequest $solicitud,
        Ubicacion $ubicacion,
        ServicioUbicaciones $servicioUbicaciones
    ): RedirectResponse {
        $servicioUbicaciones->actualizarUbicacion(
            $ubicacion,
            $solicitud->validated()
        );

        return redirect()
            ->route('administracion.ubicaciones.listar')
            ->with('estado', 'La ubicación se actualizó correctamente.');
    }

    public function cambiarEstado(Ubicacion $ubicacion): RedirectResponse
    {
        $ubicacion->update([
            'activa' => ! $ubicacion->activa,
        ]);

        $mensaje = $ubicacion->activa
            ? 'La ubicación quedó activa.'
            : 'La ubicación quedó inactiva.';

        return back()->with('estado', $mensaje);
    }

    public function buscar(Request $solicitud): JsonResponse
    {
        $texto = trim((string) $solicitud->query('buscar'));

        if (mb_strlen($texto) < 2) {
            return response()->json([]);
        }

        $ubicaciones = Ubicacion::query()
            ->where('activa', true)
            ->where('nombre_completo', 'like', "%{$texto}%")
            ->orderBy('nombre_completo')
            ->limit(10)
            ->get(['id', 'nombre_completo']);

        return response()->json($ubicaciones);
    }

    private function sugerenciasFormulario(): array
    {
        $campos = [
            'pais',
            'zona',
            'localidad',
            'categoria_barrio',
            'barrio_principal',
            'barrio',
        ];

        return [
            'sugerenciasUbicacion' => collect($campos)->mapWithKeys(
                fn (string $campo) => [
                    $campo => Ubicacion::query()
                        ->whereNotNull($campo)
                        ->where($campo, '!=', '')
                        ->distinct()
                        ->orderBy($campo)
                        ->pluck($campo),
                ]
            ),
        ];
    }
}
