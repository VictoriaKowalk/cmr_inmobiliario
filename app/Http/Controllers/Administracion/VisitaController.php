<?php

namespace App\Http\Controllers\Administracion;

use App\Enums\EstadoSeguimiento;
use App\Enums\EstadoVisita;
use App\Enums\ResultadoVisita;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\GuardarVisitaRequest;
use App\Http\Requests\Administracion\RegistrarResultadoVisitaRequest;
use App\Models\Consulta;
use App\Models\Propiedad;
use App\Models\Tasacion;
use App\Models\Usuario;
use App\Models\Visita;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VisitaController extends Controller
{
    public function listar(Request $request): View
    {
        $desde = Carbon::parse($request->query('desde', now()->startOfMonth()->toDateString()))->startOfDay();
        $hasta = Carbon::parse($request->query('hasta', now()->endOfMonth()->toDateString()))->endOfDay();
        $asesorId = $request->query('asesor');
        $estado = $request->query('estado', 'todos');

        $visitas = Visita::query()
            ->with(['propiedad', 'asesor'])
            ->whereBetween('inicio', [$desde, $hasta])
            ->when($asesorId, fn ($query) => $query->where('asesor_id', $asesorId))
            ->when($estado !== 'todos', fn ($query) => $query->where('estado', $estado))
            ->orderBy('inicio')
            ->get()
            ->groupBy(fn (Visita $visita) => $visita->inicio->toDateString());

        return view('administracion.visitas.listar', [
            'visitasPorDia' => $visitas,
            'desde' => $desde,
            'hasta' => $hasta,
            'asesorId' => $asesorId,
            'estado' => $estado,
            'responsables' => $this->responsables(),
            'estadosVisita' => EstadoVisita::cases(),
        ]);
    }

    public function crear(Request $request): View
    {
        $consulta = $request->integer('consulta')
            ? Consulta::query()->find($request->integer('consulta'))
            : null;
        $tasacion = $request->integer('tasacion')
            ? Tasacion::query()->find($request->integer('tasacion'))
            : null;

        return view('administracion.visitas.crear', [
            'consulta' => $consulta,
            'tasacion' => $tasacion,
            'propiedades' => Propiedad::query()->orderBy('titulo')->get(),
            'responsables' => $this->responsables(),
            'estadosVisita' => [EstadoVisita::PENDIENTE, EstadoVisita::CONFIRMADA],
        ]);
    }

    public function guardar(GuardarVisitaRequest $request): RedirectResponse
    {
        $datos = $request->validated();
        $oportunidad = $this->obtenerOportunidad($datos);
        unset($datos['consulta_id'], $datos['tasacion_id']);
        $datos['fin'] = $datos['fin'] ?? Carbon::parse($datos['inicio'])->addHour();
        $this->validarConflictos($datos);

        $visita = new Visita($datos);
        if ($oportunidad) {
            $visita->oportunidad()->associate($oportunidad);
        }
        $visita->save();
        $this->registrarEvento($visita, $request->user(), 'creada', 'Se coordinó la visita.');

        if ($oportunidad && ! in_array($oportunidad->estado_seguimiento, [
            EstadoSeguimiento::GANADA,
            EstadoSeguimiento::PERDIDA,
            EstadoSeguimiento::CERRADA,
        ], true)) {
            $oportunidad->update(['estado_seguimiento' => EstadoSeguimiento::VISITA_COORDINADA]);
        }

        return redirect()->route('administracion.visitas.mostrar', $visita)
            ->with('estado', 'La visita se coordinó correctamente.');
    }

    public function mostrar(Visita $visita): View
    {
        return view('administracion.visitas.mostrar', [
            'visita' => $visita->load(['propiedad', 'asesor', 'oportunidad', 'historial.usuario']),
            'resultadosVisita' => ResultadoVisita::cases(),
        ]);
    }

    public function cambiarEstado(Request $request, Visita $visita): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::enum(EstadoVisita::class)],
            'motivo_cancelacion' => [
                Rule::requiredIf($request->input('estado') === EstadoVisita::CANCELADA->value),
                'nullable',
                'string',
                'max:500',
            ],
        ]);
        $visita->update($datos);
        $this->registrarEvento(
            $visita,
            $request->user(),
            'estado_actualizado',
            'La visita pasó a '.$visita->estado->etiqueta().'.'
        );

        return back()->with('estado', 'El estado de la visita se actualizó.');
    }

    public function registrarResultado(
        RegistrarResultadoVisitaRequest $request,
        Visita $visita
    ): RedirectResponse {
        $visita->update([
            ...$request->validated(),
            'estado' => EstadoVisita::REALIZADA,
        ]);
        $this->registrarEvento($visita, $request->user(), 'resultado', 'Se registró el resultado de la visita.');

        if ($visita->oportunidad) {
            $estado = match ($visita->resultado) {
                ResultadoVisita::INTERESADO,
                ResultadoVisita::PROPUESTA => EstadoSeguimiento::NEGOCIACION,
                ResultadoVisita::SEGUIMIENTO => EstadoSeguimiento::EN_SEGUIMIENTO,
                ResultadoVisita::NO_INTERESADO => EstadoSeguimiento::PERDIDA,
            };
            $visita->oportunidad->update([
                'estado_seguimiento' => $estado,
                'proxima_tarea' => $visita->proxima_accion,
                'proxima_tarea_en' => $visita->proxima_accion_en,
                'motivo_cierre' => $estado === EstadoSeguimiento::PERDIDA
                    ? ($visita->comentarios_resultado ?: 'Sin interés luego de la visita.')
                    : null,
                'cerrada_en' => $estado === EstadoSeguimiento::PERDIDA ? now() : null,
            ]);
        }

        return back()->with('estado', 'El resultado y la oportunidad fueron actualizados.');
    }

    private function validarConflictos(array $datos): void
    {
        $conflicto = Visita::query()
            ->whereNotIn('estado', [EstadoVisita::CANCELADA, EstadoVisita::REPROGRAMADA])
            ->where(fn ($query) => $query
                ->where('asesor_id', $datos['asesor_id'])
                ->orWhere('propiedad_id', $datos['propiedad_id']))
            ->where('inicio', '<', $datos['fin'])
            ->where('fin', '>', $datos['inicio'])
            ->exists();

        if ($conflicto) {
            throw ValidationException::withMessages([
                'inicio' => 'El asesor o la propiedad ya tienen una visita en ese horario.',
            ]);
        }
    }

    private function obtenerOportunidad(array $datos): Consulta|Tasacion|null
    {
        if (! empty($datos['consulta_id'])) {
            return Consulta::query()->findOrFail($datos['consulta_id']);
        }

        return ! empty($datos['tasacion_id'])
            ? Tasacion::query()->findOrFail($datos['tasacion_id'])
            : null;
    }

    private function registrarEvento(Visita $visita, ?Usuario $usuario, string $evento, string $descripcion): void
    {
        $visita->historial()->create([
            'usuario_id' => $usuario?->id,
            'evento' => $evento,
            'descripcion' => $descripcion,
        ]);
    }

    private function responsables()
    {
        return Usuario::query()->where('activo', true)->orderBy('nombre')->get();
    }
}
