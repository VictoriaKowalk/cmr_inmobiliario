<?php

namespace App\Models;

use App\Enums\EstadoOperacion;
use App\Enums\Moneda;
use App\Enums\TipoOperacion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OperacionPropiedad extends Model
{
    use HasFactory;

    protected $table = 'operaciones_propiedad';

    protected $fillable = [
        'propiedad_id',
        'tipo_operacion',
        'moneda',
        'precio',
        'estado',
        'publicada_en',
    ];

    protected function casts(): array
    {
        return [
            'tipo_operacion' => TipoOperacion::class,
            'moneda' => Moneda::class,
            'estado' => EstadoOperacion::class,
            'precio' => 'decimal:2',
            'publicada_en' => 'datetime',
        ];
    }

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class);
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class);
    }

    public function estaPublicada(): bool
    {
        return $this->estado === EstadoOperacion::PUBLICADA;
    }

    public function publicar(): void
    {
        $this->update([
            'estado' => EstadoOperacion::PUBLICADA,
            'publicada_en' => $this->publicada_en ?? now(),
        ]);
    }

    public function pausar(): void
    {
        $this->update(['estado' => EstadoOperacion::PAUSADA]);
    }

    public function marcarComoVendida(): void
    {
        $this->update(['estado' => EstadoOperacion::VENDIDA]);
    }

    public function marcarComoAlquilada(): void
    {
        $this->update(['estado' => EstadoOperacion::ALQUILADA]);
    }
}
