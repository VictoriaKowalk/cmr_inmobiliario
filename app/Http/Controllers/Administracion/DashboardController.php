<?php

namespace App\Http\Controllers\Administracion;

use App\Enums\EstadoOperacion;
use App\Enums\EstadoSeguimiento;
use App\Enums\EstadoVisita;
use App\Http\Controllers\Controller;
use App\Models\Consulta;
use App\Models\OperacionPropiedad;
use App\Models\Propiedad;
use App\Models\Tasacion;
use App\Models\Usuario;
use App\Models\Visita;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function mostrar(Request $request): View
    {
        $periodo = $request->validate([
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);
        $desde = Carbon::parse($periodo['desde'] ?? now()->subDays(29)->toDateString())
            ->startOfDay();
        $hasta = Carbon::parse($periodo['hasta'] ?? now()->toDateString())
            ->endOfDay();
        $consultasSinLeer = Consulta::query()
            ->whereNull('leida_en')
            ->count();
        $tasacionesSinLeer = Tasacion::query()
            ->whereNull('leida_en')
            ->count();

        $cantidadPublicadas = Propiedad::query()
            ->whereHas('operaciones', fn ($consulta) => $consulta
                ->where('estado', EstadoOperacion::PUBLICADA))
            ->count();

        $cantidadPausadas = Propiedad::query()
            ->whereDoesntHave('operaciones', fn ($consulta) => $consulta
                ->where('estado', EstadoOperacion::PUBLICADA))
            ->whereHas('operaciones', fn ($consulta) => $consulta
                ->where('estado', EstadoOperacion::PAUSADA))
            ->count();

        $proximasVisitas = Visita::query()
            ->with(['propiedad', 'asesor'])
            ->where('inicio', '>=', now())
            ->where('estado', '!=', EstadoVisita::CANCELADA->value)
            ->orderBy('inicio')
            ->limit(5)
            ->get();

        $tareasVencidas = Consulta::query()
            ->whereNotNull('proxima_tarea_en')
            ->where('proxima_tarea_en', '<', now())
            ->whereNotIn('estado_seguimiento', [
                EstadoSeguimiento::GANADA->value,
                EstadoSeguimiento::PERDIDA->value,
                EstadoSeguimiento::CERRADA->value,
            ])
            ->count() + Tasacion::query()
            ->whereNotNull('proxima_tarea_en')
            ->where('proxima_tarea_en', '<', now())
            ->whereNotIn('estado_seguimiento', [
                EstadoSeguimiento::GANADA->value,
                EstadoSeguimiento::PERDIDA->value,
                EstadoSeguimiento::CERRADA->value,
            ])
            ->count();

        return view('administracion.dashboard.mostrar', [
            ...$this->indicadoresComerciales($desde, $hasta),
            'desde' => $desde,
            'hasta' => $hasta,
            'proximasVisitas' => $proximasVisitas,
            'tareasVencidas' => $tareasVencidas,
            'cantidadPublicadas' => $cantidadPublicadas,
            'cantidadPausadas' => $cantidadPausadas,
            'cantidadDestacadas' => Propiedad::query()->destacadas()->count(),
            'consultasNuevas' => Consulta::query()
                ->where('estado_seguimiento', EstadoSeguimiento::NUEVA->value)
                ->count(),
            'tasacionesNuevas' => Tasacion::query()
                ->where('estado_seguimiento', EstadoSeguimiento::NUEVA->value)
                ->count(),
            'consultasEnSeguimiento' => Consulta::query()
                ->where('estado_seguimiento', EstadoSeguimiento::EN_SEGUIMIENTO->value)
                ->count(),
            'tasacionesEnSeguimiento' => Tasacion::query()
                ->where('estado_seguimiento', EstadoSeguimiento::EN_SEGUIMIENTO->value)
                ->count(),
            'consultasSinLeer' => $consultasSinLeer,
            'tasacionesSinLeer' => $tasacionesSinLeer,
            'contactosSinLeer' => $consultasSinLeer + $tasacionesSinLeer,
            'propiedadesPublicadasSinImagen' => Propiedad::query()
                ->publicadas()
                ->whereDoesntHave('imagenes')
                ->count(),
            'propiedadesPublicadasSinPortada' => Propiedad::query()
                ->publicadas()
                ->whereDoesntHave('imagenPortada')
                ->count(),
            'operacionesPublicadasSinPrecio' => OperacionPropiedad::query()
                ->where('estado', EstadoOperacion::PUBLICADA->value)
                ->whereNull('precio')
                ->count(),
            'propiedadesSinOperacionPublicada' => Propiedad::query()
                ->whereDoesntHave('operaciones', fn ($operaciones) => $operaciones
                    ->where('estado', EstadoOperacion::PUBLICADA->value))
                ->count(),
            'ultimasConsultas' => Consulta::query()
                ->with(['propiedad', 'operacionPropiedad'])
                ->latest()
                ->limit(5)
                ->get(),
            'ultimasTasaciones' => Tasacion::query()
                ->with('tipoPropiedad')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }

    protected function indicadoresComerciales(Carbon $desde, Carbon $hasta): array
    {
        $consultas = Consulta::query()
            ->with(['propiedad', 'responsable'])
            ->whereBetween('created_at', [$desde, $hasta])
            ->get();
        $tasaciones = Tasacion::query()
            ->with('responsable')
            ->whereBetween('created_at', [$desde, $hasta])
            ->get();
        $visitas = Visita::query()
            ->with(['asesor', 'oportunidad'])
            ->whereBetween('inicio', [$desde, $hasta])
            ->get();
        $contactos = $consultas->concat($tasaciones);

        $respondidos = $contactos->filter(fn ($contacto) => $contacto->atendida_en !== null);
        $promedioRespuesta = $respondidos->isEmpty()
            ? null
            : (int) round($respondidos->average(
                fn ($contacto) => $contacto->created_at->diffInMinutes($contacto->atendida_en)
            ));

        $clavesOportunidadesConVisita = $visitas
            ->filter(fn (Visita $visita) => $visita->oportunidad_type && $visita->oportunidad_id)
            ->map(fn (Visita $visita) => $visita->oportunidad_type.':'.$visita->oportunidad_id)
            ->unique();
        $oportunidadesConVisita = $clavesOportunidadesConVisita->count();
        $oportunidadesGanadas = $contactos
            ->filter(fn ($contacto) => $contacto->estado_seguimiento === EstadoSeguimiento::GANADA
                && $clavesOportunidadesConVisita->contains($contacto::class.':'.$contacto->id))
            ->count();

        $demandaPropiedades = $consultas
            ->whereNotNull('propiedad_id')
            ->groupBy('propiedad_id')
            ->map(fn (Collection $items) => [
                'propiedad' => $items->first()->propiedad,
                'consultas' => $items->count(),
            ])
            ->sortByDesc('consultas')
            ->take(5)
            ->values();

        $asesores = Usuario::query()->where('activo', true)->get();
        $rendimientoAsesores = $asesores->map(function (Usuario $asesor) use ($contactos, $visitas) {
            $oportunidades = $contactos->where('responsable_id', $asesor->id);

            return [
                'asesor' => $asesor,
                'oportunidades' => $oportunidades->count(),
                'visitas' => $visitas->where('asesor_id', $asesor->id)->count(),
                'ganadas' => $oportunidades
                    ->where('estado_seguimiento', EstadoSeguimiento::GANADA)
                    ->count(),
            ];
        })->sortByDesc(fn (array $fila) => [$fila['ganadas'], $fila['visitas']])->values();

        $motivosPerdida = $contactos
            ->filter(fn ($contacto) => $contacto->estado_seguimiento === EstadoSeguimiento::PERDIDA)
            ->groupBy(fn ($contacto) => $contacto->motivo_cierre ?: 'Sin motivo especificado')
            ->map->count()
            ->sortDesc()
            ->take(5);

        $inicioTendencia = $desde->copy()->max($hasta->copy()->subDays(29))->startOfDay();
        $contactosPorDia = $contactos->groupBy(
            fn ($contacto) => $contacto->created_at->toDateString()
        );
        $visitasPorDia = $visitas->groupBy(
            fn (Visita $visita) => $visita->inicio->toDateString()
        );
        $tendenciaDiaria = collect(CarbonPeriod::create($inicioTendencia, $hasta))
            ->map(fn (Carbon $dia) => [
                'fecha' => $dia->copy(),
                'contactos' => $contactosPorDia->get($dia->toDateString(), collect())->count(),
                'visitas' => $visitasPorDia->get($dia->toDateString(), collect())->count(),
            ]);

        $estadosContactados = [
            EstadoSeguimiento::CONTACTADA,
            EstadoSeguimiento::VISITA_COORDINADA,
            EstadoSeguimiento::NEGOCIACION,
            EstadoSeguimiento::GANADA,
            EstadoSeguimiento::PERDIDA,
            EstadoSeguimiento::CERRADA,
        ];
        $embudoComercial = [
            ['etapa' => 'Contactos', 'cantidad' => $contactos->count()],
            [
                'etapa' => 'Contactados',
                'cantidad' => $contactos
                    ->filter(fn ($contacto) => in_array($contacto->estado_seguimiento, $estadosContactados, true))
                    ->count(),
            ],
            ['etapa' => 'Con visita', 'cantidad' => $oportunidadesConVisita],
            [
                'etapa' => 'Negociación',
                'cantidad' => $contactos
                    ->filter(fn ($contacto) => in_array($contacto->estado_seguimiento, [
                        EstadoSeguimiento::NEGOCIACION,
                        EstadoSeguimiento::GANADA,
                    ], true))
                    ->count(),
            ],
            ['etapa' => 'Ganadas', 'cantidad' => $oportunidadesGanadas],
        ];

        $operacionesPublicadas = OperacionPropiedad::query()
            ->whereBetween('publicada_en', [$desde, $hasta])
            ->whereNotNull('publicada_en')
            ->get();
        $promedioPublicacion = $operacionesPublicadas->isEmpty()
            ? null
            : round($operacionesPublicadas->average(
                fn (OperacionPropiedad $operacion) => $operacion->created_at
                    ->diffInHours($operacion->publicada_en) / 24
            ), 1);

        return [
            'consultasPeriodo' => $consultas->count(),
            'tasacionesPeriodo' => $tasaciones->count(),
            'contactosPeriodo' => $contactos->count(),
            'canales' => [
                'Consulta general' => $consultas->whereNull('propiedad_id')->count(),
                'Consulta por propiedad' => $consultas->whereNotNull('propiedad_id')->count(),
                'Tasación' => $tasaciones->count(),
            ],
            'promedioPrimeraRespuestaMinutos' => $promedioRespuesta,
            'conversionConsultaVisita' => $contactos->isEmpty()
                ? 0
                : round($oportunidadesConVisita * 100 / $contactos->count(), 1),
            'conversionVisitaOperacion' => $oportunidadesConVisita === 0
                ? 0
                : round($oportunidadesGanadas * 100 / $oportunidadesConVisita, 1),
            'visitasPeriodo' => $visitas->count(),
            'operacionesGanadas' => $oportunidadesGanadas,
            'demandaPropiedades' => $demandaPropiedades,
            'rendimientoAsesores' => $rendimientoAsesores,
            'motivosPerdida' => $motivosPerdida,
            'promedioPublicacionDias' => $promedioPublicacion,
            'tendenciaDiaria' => $tendenciaDiaria,
            'maximoTendencia' => max(
                1,
                (int) $tendenciaDiaria->max(fn (array $dia) => max($dia['contactos'], $dia['visitas']))
            ),
            'embudoComercial' => $embudoComercial,
            'maximoEmbudo' => max(1, $contactos->count()),
            'maximoDemanda' => max(1, (int) $demandaPropiedades->max('consultas')),
            'maximoAsesor' => max(
                1,
                (int) $rendimientoAsesores->max(
                    fn (array $fila) => max($fila['oportunidades'], $fila['visitas'], $fila['ganadas'])
                )
            ),
        ];
    }
}
