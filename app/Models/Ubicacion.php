<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ubicacion extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones';

    protected $fillable = [
        'ubicacion_padre_id',
        'tipo_ubicacion_id',
        'nombre',
        'nombre_normalizado',
        'codigo_georef',
        'id_tokko',
        'ruta_tokko',
        'origen',
        'pais',
        'zona',
        'localidad',
        'categoria_barrio',
        'barrio_principal',
        'barrio',
        'nombre_completo',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
        ];
    }

    public function propiedades(): HasMany
    {
        return $this->hasMany(Propiedad::class);
    }

    public function padre(): BelongsTo
    {
        return $this->belongsTo(self::class, 'ubicacion_padre_id');
    }

    public function hijos(): HasMany
    {
        return $this->hasMany(self::class, 'ubicacion_padre_id')
            ->orderBy('nombre');
    }

    public function tipoUbicacion(): BelongsTo
    {
        return $this->belongsTo(TipoUbicacion::class);
    }

    public function generarNombreCompleto(): string
    {
        return collect([
            $this->pais,
            $this->zona,
            $this->localidad,
            $this->categoria_barrio,
            $this->barrio_principal,
            $this->barrio,
        ])->filter()->implode(' | ');
    }

    public function estaActiva(): bool
    {
        return $this->activa;
    }
}
