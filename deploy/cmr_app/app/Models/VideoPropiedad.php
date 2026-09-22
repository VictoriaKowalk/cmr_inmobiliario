<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoPropiedad extends Model
{
    use HasFactory;

    protected $table = 'videos_propiedad';

    protected $fillable = [
        'propiedad_id',
        'tipo',
        'titulo',
        'ruta',
        'url',
        'youtube_id',
        'orden',
    ];

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class);
    }

    public function esYoutube(): bool
    {
        return $this->tipo === 'youtube';
    }

    public function esArchivo(): bool
    {
        return $this->tipo === 'archivo';
    }

    public function obtenerUrlPublica(): ?string
    {
        if (! $this->ruta) {
            return null;
        }

        return '/storage/'.ltrim($this->ruta, '/');
    }

    public function obtenerUrlEmbedYoutube(): ?string
    {
        if (! $this->youtube_id) {
            return null;
        }

        return "https://www.youtube.com/embed/{$this->youtube_id}";
    }
}
