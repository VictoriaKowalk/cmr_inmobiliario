<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Empresa extends Model
{
    protected $fillable = [
        'nombre_comercial',
        'razon_social',
        'email',
        'telefono',
        'whatsapp',
        'direccion',
        'zona_horaria',
        'logo_ruta',
    ];

    public function logoUrl(): ?string
    {
        return $this->logo_ruta
            ? Storage::disk('public')->url($this->logo_ruta)
            : null;
    }

    public function iniciales(): string
    {
        return collect(preg_split('/\s+/', trim($this->nombre_comercial)))
            ->filter()
            ->take(2)
            ->map(fn (string $palabra) => mb_strtoupper(mb_substr($palabra, 0, 1)))
            ->implode('');
    }
}
