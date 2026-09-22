<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReglaTipoUbicacion extends Model
{
    use HasFactory;

    protected $table = 'reglas_tipos_ubicacion';

    protected $fillable = [
        'tipo_ubicacion_padre_id',
        'tipo_ubicacion_hijo_id',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
        ];
    }

    public function tipoUbicacionPadre(): BelongsTo
    {
        return $this->belongsTo(TipoUbicacion::class, 'tipo_ubicacion_padre_id');
    }

    public function tipoUbicacionHijo(): BelongsTo
    {
        return $this->belongsTo(TipoUbicacion::class, 'tipo_ubicacion_hijo_id');
    }
}
