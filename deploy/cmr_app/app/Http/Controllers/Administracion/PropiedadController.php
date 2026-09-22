<?php

namespace App\Http\Controllers\Administracion;

use App\Enums\CategoriaCaracteristica;
use App\Enums\EstadoOperacion;
use App\Enums\Moneda;
use App\Enums\Orientacion;
use App\Enums\TipoOperacion;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\ActualizarOperacionRequest;
use App\Http\Requests\Administracion\GuardarPropiedadRequest;
use App\Models\Caracteristica;
use App\Models\OperacionPropiedad;
use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Services\ServicioImagenesPropiedad;
use App\Services\ServicioPropiedades;
use App\Services\ServicioVideosPropiedad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class PropiedadController extends Controller
{
    public function listar(Request $solicitud): View
    {
        $busqueda = trim((string) $solicitud->query('buscar'));
        $tipoOperacion = $solicitud->query('tipo_operacion');
        $estado = $solicitud->query('estado');
        $revision = $solicitud->query('revision', 'todas');
        $visibilidad = $solicitud->query('visibilidad', 'activas');

        $consulta = Propiedad::query()
            ->with([
                'tipoPropiedad',
                'ubicacion',
                'operaciones',
                'imagenes',
                'imagenPortada',
                'caracteristicas',
            ])
            ->when($visibilidad === 'eliminadas', fn ($query) => $query->onlyTrashed())
            ->when(
                $busqueda !== '',
                fn ($query) => $query->where(function ($subconsulta) use ($busqueda) {
                    $subconsulta
                        ->where('codigo_interno', 'like', "%{$busqueda}%")
                        ->orWhere('titulo', 'like', "%{$busqueda}%")
                        ->orWhereHas('ubicacion', fn ($ubicaciones) => $ubicaciones
                            ->where('nombre_completo', 'like', "%{$busqueda}%"));
                })
            )
            ->when(
                $tipoOperacion,
                fn ($query) => $query->whereHas(
                    'operaciones',
                    fn ($operaciones) => $operaciones
                        ->where('tipo_operacion', $tipoOperacion)
                )
            )
            ->when(
                $estado,
                fn ($query) => $query->whereHas(
                    'operaciones',
                    fn ($operaciones) => $operaciones->where('estado', $estado)
                )
            )
            ->when(
                $revision === 'sin_imagen',
                fn ($query) => $query
                    ->whereHas(
                        'operaciones',
                        fn ($operaciones) => $operaciones
                            ->where('estado', EstadoOperacion::PUBLICADA->value)
                    )
                    ->whereDoesntHave('imagenes')
            )
            ->when(
                $revision === 'sin_portada',
                fn ($query) => $query
                    ->whereHas(
                        'operaciones',
                        fn ($operaciones) => $operaciones
                            ->where('estado', EstadoOperacion::PUBLICADA->value)
                    )
                    ->whereDoesntHave('imagenPortada')
            )
            ->when(
                $revision === 'sin_precio',
                fn ($query) => $query->whereHas(
                    'operaciones',
                    fn ($operaciones) => $operaciones
                        ->where('estado', EstadoOperacion::PUBLICADA->value)
                        ->whereNull('precio')
                )
            )
            ->when(
                $revision === 'sin_operacion_publicada',
                fn ($query) => $query->whereDoesntHave(
                    'operaciones',
                    fn ($operaciones) => $operaciones
                        ->where('estado', EstadoOperacion::PUBLICADA->value)
                )
            )
            ->when(
                $revision === 'destacadas',
                fn ($query) => $query->destacadas()
            )
            ->latest();

        return view('administracion.propiedades.listar', [
            'propiedades' => $consulta->paginate(12)->withQueryString(),
            'busqueda' => $busqueda,
            'tipoOperacionSeleccionado' => $tipoOperacion,
            'estadoSeleccionado' => $estado,
            'revision' => $revision,
            'visibilidad' => $visibilidad,
            'tiposOperacion' => TipoOperacion::cases(),
            'estadosOperacion' => EstadoOperacion::cases(),
        ]);
    }

    public function crear(): View
    {
        return view('administracion.propiedades.crear', $this->datosFormulario());
    }

    public function guardar(
        GuardarPropiedadRequest $solicitud,
        ServicioPropiedades $servicioPropiedades,
        ServicioImagenesPropiedad $servicioImagenes,
        ServicioVideosPropiedad $servicioVideos
    ): RedirectResponse {
        $propiedadId = null;

        try {
            $propiedad = DB::transaction(function () use (
                $solicitud,
                $servicioPropiedades,
                $servicioImagenes,
                $servicioVideos,
                &$propiedadId
            ): Propiedad {
                $propiedad = $servicioPropiedades->crearPropiedad(
                    $solicitud->safe()->except([
                        'imagenes',
                        'video_titulo',
                        'youtube_url',
                    ])
                );
                $propiedadId = $propiedad->id;

                if ($solicitud->hasFile('imagenes')) {
                    $servicioImagenes->guardarImagenes(
                        $propiedad,
                        $solicitud->file('imagenes')
                    );
                }

                if ($solicitud->filled('youtube_url')) {
                    $servicioVideos->guardarVideoDesdeYoutube(
                        $propiedad,
                        $solicitud->validated('youtube_url'),
                        $solicitud->validated('video_titulo')
                    );
                }

                return $propiedad;
            });
        } catch (Throwable $error) {
            if ($propiedadId) {
                Storage::disk('public')->deleteDirectory(
                    "propiedades/{$propiedadId}"
                );
            }

            report($error);

            return back()
                ->withErrors([
                    'guardado' => 'No se pudo crear la propiedad. Revisá los archivos e intentá nuevamente.',
                ])
                ->withInput();
        }

        return redirect()
            ->route('administracion.propiedades.editar', $propiedad)
            ->with('estado', 'La propiedad se creó correctamente.');
    }

    public function mostrar(Propiedad $propiedad): View
    {
        return view('administracion.propiedades.mostrar', [
            'propiedad' => $propiedad->load([
                'tipoPropiedad',
                'ubicacion',
                'operaciones',
                'imagenes',
                'videos',
                'caracteristicas',
            ]),
        ]);
    }

    public function editar(Propiedad $propiedad): View
    {
        return view('administracion.propiedades.editar', [
            ...$this->datosFormulario($propiedad),
            'propiedad' => $propiedad->load([
                'operaciones',
                'imagenes',
                'videos',
                'caracteristicas',
            ]),
        ]);
    }

    public function actualizar(
        GuardarPropiedadRequest $solicitud,
        Propiedad $propiedad,
        ServicioPropiedades $servicioPropiedades
    ): RedirectResponse {
        $servicioPropiedades->actualizarPropiedad(
            $propiedad,
            $solicitud->validated()
        );

        return back()->with('estado', 'La propiedad se actualizó correctamente.');
    }

    public function cambiarDestacada(Propiedad $propiedad): RedirectResponse
    {
        $caracteristica = Caracteristica::query()
            ->where('nombre', 'Propiedad destacada')
            ->where('categoria', CategoriaCaracteristica::OBSERVACION)
            ->firstOrFail();

        $estaDestacada = $propiedad->caracteristicas()
            ->whereKey($caracteristica->id)
            ->exists();

        if ($estaDestacada) {
            $propiedad->caracteristicas()->detach($caracteristica->id);
        } else {
            $propiedad->caracteristicas()->syncWithoutDetaching([
                $caracteristica->id,
            ]);
        }

        return back()->with(
            'estado',
            $estaDestacada
                ? 'La propiedad dejó de estar destacada.'
                : 'La propiedad quedó destacada.'
        );
    }

    public function cambiarEstadoOperacion(
        Request $solicitud,
        Propiedad $propiedad,
        OperacionPropiedad $operacion
    ): RedirectResponse {
        abort_unless($operacion->propiedad_id === $propiedad->id, 404);

        $datos = $solicitud->validate([
            'estado' => [
                'required',
                Rule::in(array_column(EstadoOperacion::cases(), 'value')),
            ],
        ]);

        $estado = $datos['estado'];

        if ($operacion->tipo_operacion === TipoOperacion::VENTA
            && $estado === EstadoOperacion::ALQUILADA->value) {
            return back()->withErrors([
                'estado_operacion' => 'Una operacion de venta no puede marcarse como alquilada.',
            ]);
        }

        if ($operacion->tipo_operacion !== TipoOperacion::VENTA
            && $estado === EstadoOperacion::VENDIDA->value) {
            return back()->withErrors([
                'estado_operacion' => 'Una operacion de alquiler no puede marcarse como vendida.',
            ]);
        }

        $operacion->update([
            'estado' => $estado,
            'publicada_en' => $estado === EstadoOperacion::PUBLICADA->value
                ? ($operacion->publicada_en ?? now())
                : $operacion->publicada_en,
        ]);

        return back()->with('estado', 'El estado de la operacion se actualizo correctamente.');
    }

    public function actualizarOperacion(
        ActualizarOperacionRequest $solicitud,
        Propiedad $propiedad,
        OperacionPropiedad $operacion
    ): RedirectResponse {
        abort_unless($operacion->propiedad_id === $propiedad->id, 404);

        $datos = $solicitud->validated();
        $datos['precio'] = $solicitud->filled('precio')
            ? $datos['precio']
            : null;
        $datos['moneda'] = $solicitud->filled('moneda')
            ? $datos['moneda']
            : null;

        if ($datos['estado'] === EstadoOperacion::PUBLICADA->value
            && $operacion->publicada_en === null) {
            $datos['publicada_en'] = now();
        }

        $operacion->update($datos);

        return back()->with(
            'estado',
            'La operación se actualizó correctamente.'
        );
    }

    public function eliminar(
        Propiedad $propiedad,
        ServicioPropiedades $servicioPropiedades
    ): RedirectResponse {
        $servicioPropiedades->eliminarPropiedad($propiedad);

        return redirect()
            ->route('administracion.propiedades.listar')
            ->with('estado', 'La propiedad se eliminó del panel activo.');
    }

    public function restaurar(
        int $propiedad,
        ServicioPropiedades $servicioPropiedades
    ): RedirectResponse {
        $registro = Propiedad::onlyTrashed()->findOrFail($propiedad);
        $servicioPropiedades->restaurarPropiedad($registro);

        return back()->with('estado', 'La propiedad se restauró correctamente.');
    }

    private function datosFormulario(?Propiedad $propiedad = null): array
    {
        return [
            'tiposPropiedad' => TipoPropiedad::query()
                ->where(function ($consulta) use ($propiedad) {
                    $consulta->where('activo', true)
                        ->when(
                            $propiedad,
                            fn ($subconsulta) => $subconsulta
                                ->orWhere('id', $propiedad->tipo_propiedad_id)
                        );
                })
                ->orderBy('nombre')
                ->get(),
            'tiposOperacion' => TipoOperacion::cases(),
            'estadosOperacion' => EstadoOperacion::cases(),
            'monedas' => Moneda::cases(),
            'orientaciones' => Orientacion::cases(),
            'servicios' => Caracteristica::query()
                ->where('activa', true)
                ->where('categoria', CategoriaCaracteristica::SERVICIO)
                ->orderBy('nombre')
                ->get(),
            'ambientesCaracteristicas' => Caracteristica::query()
                ->where('activa', true)
                ->where('categoria', CategoriaCaracteristica::AMBIENTE)
                ->orderBy('nombre')
                ->get(),
            'opcionesCartel' => Caracteristica::query()
                ->where('activa', true)
                ->where('categoria', CategoriaCaracteristica::CARTEL)
                ->orderBy('nombre')
                ->get(),
            'observacionesCaracteristicas' => Caracteristica::query()
                ->where('activa', true)
                ->where('categoria', CategoriaCaracteristica::OBSERVACION)
                ->orderBy('nombre')
                ->get(),
            'preferenciasLote' => Caracteristica::query()
                ->where('activa', true)
                ->where('categoria', CategoriaCaracteristica::PREFERENCIA_LOTE)
                ->orderBy('nombre')
                ->get(),
            'amenitiesCaracteristicas' => Caracteristica::query()
                ->where('activa', true)
                ->where('categoria', CategoriaCaracteristica::AMENITY)
                ->orderBy('nombre')
                ->get(),
            'adicionalesCaracteristicas' => Caracteristica::query()
                ->where('activa', true)
                ->where('categoria', CategoriaCaracteristica::ADICIONAL)
                ->orderBy('nombre')
                ->get(),
        ];
    }
}
