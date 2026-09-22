<?php

namespace App\Models;

use App\Enums\EstadoVisita;
use App\Enums\ResultadoVisita;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Visita extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'inicio' => 'datetime',
            'fin' => 'datetime',
            'estado' => EstadoVisita::class,
            'resultado' => ResultadoVisita::class,
            'proxima_accion_en' => 'datetime',
            'recordatorio_cliente_enviado_en' => 'datetime',
            'recordatorio_asesor_enviado_en' => 'datetime',
        ];
    }

    public function oportunidad(): MorphTo
    {
        return $this->morphTo();
    }

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class)->withTrashed();
    }

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'asesor_id');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialVisita::class)->latest();
    }
}
