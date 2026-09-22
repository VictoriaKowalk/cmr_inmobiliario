<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoUbicacion extends Model
{
    use HasFactory;

    protected $table = 'tipos_ubicacion';

    protected $fillable = [
        'codigo',
        'nombre',
        'orden',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function ubicaciones(): HasMany
    {
        return $this->hasMany(Ubicacion::class);
    }

    public function reglasComoPadre(): HasMany
    {
        return $this->hasMany(ReglaTipoUbicacion::class, 'tipo_ubicacion_padre_id');
    }
}
