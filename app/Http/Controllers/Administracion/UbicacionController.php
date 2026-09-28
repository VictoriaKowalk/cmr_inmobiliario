<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\GuardarUbicacionRequest;
use App\Models\ReglaTipoUbicacion;
use App\Models\TipoUbicacion;
use App\Models\Ubicacion;
use App\Services\ServicioUbicaciones;
use App\Services\ServicioCoberturaUbicaciones;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UbicacionController extends Controller
{
    public function listar(
        Request $solicitud,
        ServicioCoberturaUbicaciones $cobertura
    ): View
    {
        $busqueda = trim((string) $solicitud->query('buscar'));
        $estado = $solicitud->query('estado', 'activas');
        $padre = $solicitud->filled('padre') ? Ubicacion::query()->with('padre', 'tipoUbicacion')->findOrFail($solicitud->integer('padre')) : null;
        $zonasTrabajo = $cobertura->empresaActual()
            ->zonasCobertura()
            ->with('tipoUbicacion')
            ->orderBy('nombre')
            ->get();

        $ubicaciones = Ubicacion::query()->with('tipoUbicacion')->withCount('hijos')
            ->when($busqueda !== '', fn ($q) => $q->where(fn ($s) => $s->where('nombre_completo', 'like', "%{$busqueda}%")->orWhere('nombre', 'like', "%{$busqueda}%")))
            ->when($busqueda === '' && $padre, fn ($q) => $q->where('ubicacion_padre_id', $padre->id))
            ->when($busqueda === '' && ! $padre && $zonasTrabajo->isEmpty(), fn ($q) => $q->whereNull('ubicacion_padre_id'))
            ->tap(fn ($q) => $cobertura->aplicarCobertura($q))
            ->when($estado === 'activas', fn ($q) => $q->where('activa', true))
            ->when($estado === 'inactivas', fn ($q) => $q->where('activa', false))
            ->orderBy('nombre_completo')->paginate(30)->withQueryString();

        return view('administracion.ubicaciones.listar', compact('ubicaciones', 'busqueda', 'estado', 'padre', 'zonasTrabajo'));
    }

    public function crear(Request $solicitud): View
    {
        $padre = $solicitud->filled('padre') ? Ubicacion::query()->with('tipoUbicacion')->findOrFail($solicitud->integer('padre')) : null;
        return view('administracion.ubicaciones.crear', ['padre' => $padre, 'tiposUbicacion' => $this->tiposPermitidos($padre)]);
    }

    public function guardar(GuardarUbicacionRequest $solicitud, ServicioUbicaciones $servicio): RedirectResponse
    {
        $ubicacion = $servicio->crearNodo($solicitud->validated());
        return redirect()->route('administracion.ubicaciones.listar', ['padre' => $ubicacion->ubicacion_padre_id])->with('estado', 'La ubicación se creó correctamente.');
    }

    public function editar(Ubicacion $ubicacion): View
    {
        return view('administracion.ubicaciones.editar', ['ubicacion' => $ubicacion->load('padre', 'tipoUbicacion')]);
    }

    public function actualizar(GuardarUbicacionRequest $solicitud, Ubicacion $ubicacion, ServicioUbicaciones $servicio): RedirectResponse
    {
        $servicio->actualizarNodo($ubicacion, $solicitud->validated('nombre'));
        return redirect()->route('administracion.ubicaciones.listar', ['padre' => $ubicacion->ubicacion_padre_id])->with('estado', 'La ubicación se actualizó correctamente.');
    }

    public function cambiarEstado(Ubicacion $ubicacion): RedirectResponse
    {
        $ubicacion->update(['activa' => ! $ubicacion->activa]);
        return back()->with('estado', $ubicacion->activa ? 'La ubicación quedó activa.' : 'La ubicación quedó inactiva.');
    }

    public function zonasDeTrabajo(ServicioCoberturaUbicaciones $cobertura): View
    {
        return view('administracion.ubicaciones.zonas-de-trabajo', [
            'empresa' => $cobertura->empresaActual()->load('zonasCobertura.tipoUbicacion'),
        ]);
    }

    public function guardarZonaDeTrabajo(Request $solicitud, ServicioCoberturaUbicaciones $cobertura): RedirectResponse
    {
        $datos = $solicitud->validate([
            'ubicacion_id' => ['required', 'integer', 'exists:ubicaciones,id'],
        ], [
            'ubicacion_id.required' => 'Elegí una ubicación para agregarla a tus zonas de trabajo.',
        ]);

        $empresa = $cobertura->empresaActual();
        $empresa->zonasCobertura()->syncWithoutDetaching([$datos['ubicacion_id']]);

        return redirect()->route('administracion.ubicaciones.zonas-de-trabajo')
            ->with('estado', 'La zona de trabajo se agregó correctamente.');
    }

    public function eliminarZonaDeTrabajo(Ubicacion $ubicacion, ServicioCoberturaUbicaciones $cobertura): RedirectResponse
    {
        $cobertura->empresaActual()->zonasCobertura()->detach($ubicacion->id);

        return redirect()->route('administracion.ubicaciones.zonas-de-trabajo')
            ->with('estado', 'La zona de trabajo se eliminó correctamente.');
    }

    public function buscar(Request $solicitud, ServicioCoberturaUbicaciones $cobertura): JsonResponse
    {
        $texto = trim((string) $solicitud->query('buscar'));
        $textoSinSeparadores = preg_replace('/[^\pL\pN]/u', '', mb_strtolower($texto));
        $terminosBusqueda = collect(preg_split('/[^\pL\pN]+/u', mb_strtolower($texto)))
            ->filter(fn (string $termino): bool => mb_strlen($termino) > 1)
            ->values();
        if (mb_strlen($texto) < 2) {
            return response()->json([]);
        }

        $coincideTexto = function ($consulta) use ($texto, $textoSinSeparadores, $terminosBusqueda): void {
            $consulta
                ->where('nombre_completo', 'like', "%{$texto}%")
                ->orWhere('nombre', 'like', "%{$texto}%")
                ->orWhereRaw(
                    "REPLACE(REPLACE(LOWER(nombre_normalizado), '.', ''), ' ', '') like ?",
                    ["%{$textoSinSeparadores}%"]
                )
                ->orWhere(function ($porTerminos) use ($terminosBusqueda): void {
                    foreach ($terminosBusqueda as $termino) {
                        $porTerminos->where('nombre_completo', 'like', "%{$termino}%");
                    }
                });
        };

        $coincidencias = Ubicacion::query()
            ->with(['tipoUbicacion:id,codigo,nombre', 'padre:id,nombre,nombre_normalizado'])
            ->where('activa', true)
            ->when(! $solicitud->boolean('cobertura'), fn ($ubicaciones) => $cobertura->aplicarCobertura($ubicaciones))
            ->where($coincideTexto)
            ->orderBy('nombre_completo')
            ->get(['id', 'ubicacion_padre_id', 'tipo_ubicacion_id', 'nombre', 'nombre_normalizado', 'nombre_completo']);

        $nivelesAdministrativos = Ubicacion::query()
            ->with(['tipoUbicacion:id,codigo,nombre', 'padre:id,nombre,nombre_normalizado'])
            ->where('activa', true)
            ->when(! $solicitud->boolean('cobertura'), fn ($ubicaciones) => $cobertura->aplicarCobertura($ubicaciones))
            ->whereHas('tipoUbicacion', fn ($tipos) => $tipos->whereIn('codigo', [
                'partido', 'municipio', 'departamento', 'comuna', 'zona_comercial', 'localidad',
            ]))
            ->where($coincideTexto)
            ->get(['id', 'ubicacion_padre_id', 'tipo_ubicacion_id', 'nombre', 'nombre_normalizado', 'nombre_completo']);

        // Una propiedad puede publicarse en cualquier nivel geográfico. No se
        // descartan partidos, municipios ni localidades cuando también existen barrios.
        $visibles = $coincidencias->concat($nivelesAdministrativos)->unique('id')->sort(function (Ubicacion $primera, Ubicacion $segunda) use ($texto): int {
            $normalizar = fn (string $valor): string => preg_replace('/[^\pL\pN]/u', '', mb_strtolower(trim($valor)));
            $textoNormalizado = $normalizar($texto);
            $coincidenciaExactaPrimera = $normalizar($primera->nombre) === $textoNormalizado ? 0 : 1;
            $coincidenciaExactaSegunda = $normalizar($segunda->nombre) === $textoNormalizado ? 0 : 1;

            if ($coincidenciaExactaPrimera !== $coincidenciaExactaSegunda) {
                return $coincidenciaExactaPrimera <=> $coincidenciaExactaSegunda;
            }

            $profundidadPrimera = substr_count($primera->nombre_completo, '|');
            $profundidadSegunda = substr_count($segunda->nombre_completo, '|');

            // Una vez ubicado el mejor nodo, se recorre el árbol por niveles:
            // padre, hijos y luego los descendientes de cada uno.
            return [$profundidadPrimera, $primera->nombre_completo] <=> [$profundidadSegunda, $segunda->nombre_completo];
        })->groupBy(function (Ubicacion $ubicacion): string {
            $partes = array_map('trim', explode('|', $ubicacion->nombre_completo));
            $provincia = $partes[1] ?? '';
            $nombre = preg_replace('/^(Partido|Municipio|Departamento|Comuna) de /iu', '', $ubicacion->nombre) ?: $ubicacion->nombre;

            return mb_strtolower($provincia.'|'.$nombre);
        })->map(fn ($grupo) => $grupo->first())->values();

        return response()->json($visibles
            ->map(fn (Ubicacion $ubicacion) => [
                'id' => $ubicacion->id,
                'nombre' => $ubicacion->nombre,
                'nombre_completo' => $ubicacion->nombre_completo,
                'nombre_mostrado' => $this->nombreParaSelector($ubicacion),
                'tipo' => $ubicacion->tipoUbicacion?->nombre,
                'ruta_mostrada' => $this->rutaParaSelector($ubicacion),
                'ruta_completa_mostrada' => $this->rutaCompletaParaSelector($ubicacion),
            ])
            ->take(10)
            ->values());
    }

    private function nombreParaSelector(Ubicacion $ubicacion): string
    {
        $esLocalidadCabecera = $ubicacion->tipoUbicacion?->codigo === 'localidad'
            && $ubicacion->padre?->nombre_normalizado === $ubicacion->nombre_normalizado;

        return $esLocalidadCabecera ? "{$ubicacion->nombre} (Centro)" : $ubicacion->nombre;
    }

    private function rutaParaSelector(Ubicacion $ubicacion): string
    {
        $partes = collect(explode('|', $ubicacion->nombre_completo))
            ->map(fn (string $parte) => trim($parte))
            ->filter()
            ->values();

        if (mb_strtolower((string) $partes->first()) === 'argentina') {
            $partes->shift();
        }

        if ($partes->last() === $ubicacion->nombre) {
            $partes->pop();
        }

        $partes = $partes
            ->map(fn (string $parte) => $this->simplificarParteDeRuta($parte));

        if ($partes->contains(fn (string $parte) => str_contains(mb_strtolower($parte), 'zona'))) {
            $partes = $partes->reject(fn (string $parte) => mb_strtolower($parte) === 'buenos aires');
        }

        return $partes
            ->reduce(function ($ruta, string $parte) {
                if ($ruta->last() !== $parte) {
                    $ruta->push($parte);
                }

                return $ruta;
            }, collect())
            ->implode(' | ');
    }

    private function simplificarParteDeRuta(string $parte): string
    {
        return preg_replace(
            '/^(Partido|Municipio|Departamento) de /iu',
            '',
            $parte
        ) ?: $parte;
    }

    private function rutaCompletaParaSelector(Ubicacion $ubicacion): string
    {
        $partes = collect(explode('|', $ubicacion->nombre_completo))
            ->map(fn (string $parte) => trim($parte))
            ->filter()
            ->values();

        if ($partes->last() === $ubicacion->nombre) {
            $partes->pop();
        }

        $partes->push($this->nombreParaSelector($ubicacion));

        return $partes->implode(' | ');
    }

    private function claveAdministrativa(Ubicacion $ubicacion): string
    {
        return mb_strtolower($this->rutaParaSelector($ubicacion).'|'.$ubicacion->nombre);
    }

    private function prioridadAdministrativa(Ubicacion $ubicacion): int
    {
        return match ($ubicacion->tipoUbicacion?->codigo) {
            'municipio' => 1,
            'partido' => 2,
            'departamento' => 3,
            'comuna' => 4,
            default => 5,
        };
    }

    private function tiposPermitidos(?Ubicacion $padre)
    {
        if (! $padre) return TipoUbicacion::query()->where('codigo', 'pais')->get();
        return TipoUbicacion::query()->where('activo', true)->whereIn('id', ReglaTipoUbicacion::query()
            ->where('tipo_ubicacion_padre_id', $padre->tipo_ubicacion_id)->where('activa', true)->pluck('tipo_ubicacion_hijo_id'))
            ->orderBy('orden')->get();
    }
}
