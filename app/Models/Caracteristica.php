<?php

namespace App\Models;

use App\Enums\CategoriaCaracteristica;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Caracteristica extends Model
{
    use HasFactory;

    protected $table = 'caracteristicas';

    protected $fillable = [
        'nombre',
        'categoria',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'categoria' => CategoriaCaracteristica::class,
            'activa' => 'boolean',
        ];
    }

    public function propiedades(): BelongsToMany
    {
        return $this->belongsToMany(
            Propiedad::class,
            'caracteristica_propiedad'
        )->withTimestamps();
    }
}
