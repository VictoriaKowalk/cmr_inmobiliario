<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HistorialOportunidad extends Model
{
    protected $table = 'historial_oportunidades';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['cambios' => 'array'];
    }

    public function oportunidad(): MorphTo
    {
        return $this->morphTo();
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }
}
