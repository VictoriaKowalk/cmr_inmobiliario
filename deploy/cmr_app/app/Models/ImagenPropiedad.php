<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagenPropiedad extends Model
{
    use HasFactory;

    protected $table = 'imagenes_propiedad';

    protected $fillable = [
        'propiedad_id',
        'ruta',
        'nombre_original',
        'orden',
        'portada',
    ];

    protected function casts(): array
    {
        return [
            'portada' => 'boolean',
        ];
    }

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class);
    }

    public function esPortada(): bool
    {
        return $this->portada;
    }

    public function obtenerUrlPublica(): string
    {
        return '/storage/'.ltrim($this->ruta, '/');
    }
}
