<?php

namespace App\Models\Concerns;

use App\Enums\EstadoSeguimiento;
use App\Models\HistorialOportunidad;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait GestionaOportunidad
{
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'responsable_id');
    }

    public function historialOportunidad(): MorphMany
    {
        return $this->morphMany(HistorialOportunidad::class, 'oportunidad')->latest();
    }

    public function actualizarOportunidad(array $datos, ?Usuario $usuario): void
    {
        $estadoAnterior = $this->estado_seguimiento;
        $responsableAnterior = $this->responsable?->nombreCompleto();
        $prioridadAnterior = $this->prioridad;

        $estado = EstadoSeguimiento::from($datos['estado_seguimiento']);
        $datos['atendida_en'] = $this->atendida_en;
        $datos['cerrada_en'] = $this->cerrada_en;

        if ($estado === EstadoSeguimiento::CONTACTADA && $this->atendida_en === null) {
            $datos['atendida_en'] = now();
        }

        if (in_array($estado, [
            EstadoSeguimiento::GANADA,
            EstadoSeguimiento::PERDIDA,
            EstadoSeguimiento::CERRADA,
        ], true)) {
            $datos['cerrada_en'] ??= now();
            $datos['proxima_tarea'] = null;
            $datos['proxima_tarea_en'] = null;
        } else {
            $datos['cerrada_en'] = null;
            $datos['motivo_cierre'] = null;
        }

        $this->update($datos);
        $this->load('responsable');

        $cambios = array_filter([
            'estado' => $estadoAnterior !== $this->estado_seguimiento
                ? [$estadoAnterior->etiqueta(), $this->estado_seguimiento->etiqueta()]
                : null,
            'responsable' => $responsableAnterior !== $this->responsable?->nombreCompleto()
                ? [$responsableAnterior ?: 'Sin asignar', $this->responsable?->nombreCompleto() ?: 'Sin asignar']
                : null,
            'prioridad' => $prioridadAnterior !== $this->prioridad
                ? [$prioridadAnterior->etiqueta(), $this->prioridad->etiqueta()]
                : null,
        ]);

        $this->historialOportunidad()->create([
            'usuario_id' => $usuario?->id,
            'evento' => 'seguimiento_actualizado',
            'descripcion' => $cambios
                ? 'Se actualizó la oportunidad comercial.'
                : 'Se actualizaron la tarea o las notas de la oportunidad.',
            'cambios' => $cambios ?: null,
        ]);
    }

    public function inicializarOportunidad(): void
    {
        $this->historialOportunidad()->create([
            'evento' => 'oportunidad_creada',
            'descripcion' => 'La oportunidad se creó desde el formulario público.',
        ]);
    }
}
