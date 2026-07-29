<?php

namespace App\Models;

use App\Enums\EstadoSeguimiento;
use App\Enums\PrioridadOportunidad;
use App\Models\Concerns\GestionaOportunidad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tasacion extends Model
{
    use GestionaOportunidad, HasFactory;

    protected $table = 'tasaciones';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'estado_seguimiento' => EstadoSeguimiento::class,
            'prioridad' => PrioridadOportunidad::class,
            'proxima_tarea_en' => 'datetime',
            'leida_en' => 'datetime',
            'atendida_en' => 'datetime',
            'cerrada_en' => 'datetime',
        ];
    }

    public function tipoPropiedad(): BelongsTo
    {
        return $this->belongsTo(TipoPropiedad::class);
    }

    public function marcarComoLeida(): void
    {
        if ($this->leida_en === null) {
            $this->update(['leida_en' => now()]);
        }
    }

    public function actualizarSeguimiento(
        EstadoSeguimiento $estado,
        ?string $notasInternas = null
    ): void {
        $datos = [
            'estado_seguimiento' => $estado,
            'notas_internas' => $notasInternas,
        ];

        if ($estado === EstadoSeguimiento::CONTACTADA && $this->atendida_en === null) {
            $datos['atendida_en'] = now();
        }

        $this->update($datos);
    }
}
