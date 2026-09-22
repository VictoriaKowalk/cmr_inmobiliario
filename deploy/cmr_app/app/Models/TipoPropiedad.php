<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoPropiedad extends Model
{
    use HasFactory;

    protected $table = 'tipos_propiedad';

    protected $fillable = [
        'nombre',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function propiedades(): HasMany
    {
        return $this->hasMany(Propiedad::class);
    }

    public function tasaciones(): HasMany
    {
        return $this->hasMany(Tasacion::class);
    }

    public function estaActivo(): bool
    {
        return $this->activo;
    }
}
