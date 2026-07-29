<?php

namespace App\Models;

use App\Enums\EstadoOperacion;
use App\Enums\Moneda;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Propiedad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'propiedades';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'mostrar_direccion' => 'boolean',
            'expensas' => 'decimal:2',
            'expensas_moneda' => Moneda::class,
            'superficie_total' => 'decimal:2',
            'superficie_cubierta' => 'decimal:2',
            'superficie_descubierta' => 'decimal:2',
            'superficie_terreno' => 'decimal:2',
            'latitud' => 'decimal:7',
            'longitud' => 'decimal:7',
            'ubicacion_confirmada' => 'boolean',
        ];
    }

    public function tipoPropiedad(): BelongsTo
    {
        return $this->belongsTo(TipoPropiedad::class);
    }

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class);
    }

    public function operaciones(): HasMany
    {
        return $this->hasMany(OperacionPropiedad::class);
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(ImagenPropiedad::class)->orderBy('orden');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(VideoPropiedad::class)->orderBy('orden');
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class);
    }

    public function caracteristicas(): BelongsToMany
    {
        return $this->belongsToMany(
            Caracteristica::class,
            'caracteristica_propiedad'
        )->withTimestamps();
    }

    public function imagenPortada(): HasOne
    {
        return $this->hasOne(ImagenPropiedad::class)
            ->where('portada', true)
            ->orderBy('orden');
    }

    public function operacionesPublicadas(): HasMany
    {
        return $this->operaciones()
            ->where('estado', EstadoOperacion::PUBLICADA);
    }

    public function estaPublicada(): bool
    {
        return $this->operaciones()
            ->where('estado', EstadoOperacion::PUBLICADA)
            ->exists();
    }

    public function estaPausada(): bool
    {
        return ! $this->estaPublicada()
            && $this->operaciones()
                ->where('estado', EstadoOperacion::PAUSADA)
                ->exists();
    }

    public function scopePublicadas(Builder $consulta): Builder
    {
        return $consulta->whereHas(
            'operaciones',
            fn (Builder $operaciones) => $operaciones
                ->where('estado', EstadoOperacion::PUBLICADA)
        );
    }

    public function scopeDestacadas(Builder $consulta): Builder
    {
        return $consulta->whereHas(
            'caracteristicas',
            fn (Builder $caracteristicas) => $caracteristicas
                ->where('nombre', 'Propiedad destacada')
                ->where('categoria', 'observacion')
        );
    }

    public function tieneCaracteristica(string $nombre): bool
    {
        if ($this->relationLoaded('caracteristicas')) {
            return $this->caracteristicas->contains('nombre', $nombre);
        }

        return $this->caracteristicas()
            ->where('nombre', $nombre)
            ->exists();
    }

    public function estaDestacada(): bool
    {
        return $this->tieneCaracteristica('Propiedad destacada');
    }

    public function obtenerChecklistPublicacion(): array
    {
        $operaciones = $this->relationLoaded('operaciones')
            ? $this->operaciones
            : $this->operaciones()->get();

        $imagenes = $this->relationLoaded('imagenes')
            ? $this->imagenes
            : $this->imagenes()->get();

        $caracteristicas = $this->relationLoaded('caracteristicas')
            ? $this->caracteristicas
            : $this->caracteristicas()->get();

        $tieneOperacionPublicada = $operaciones
            ->contains(fn ($operacion) => $operacion->estado === EstadoOperacion::PUBLICADA);
        $tieneImagenes = $imagenes->isNotEmpty();
        $tienePortada = $imagenes->contains('portada', true)
            || $this->imagenPortada()->exists();
        $tienePrecioOConsultar = $operaciones
            ->isNotEmpty();
        $tieneCaracteristicas = $caracteristicas->isNotEmpty();

        return [
            [
                'texto' => 'Tiene al menos una operación publicada',
                'completo' => $tieneOperacionPublicada,
                'obligatorio' => true,
            ],
            [
                'texto' => 'Tiene ubicación seleccionada',
                'completo' => (bool) $this->ubicacion_id,
                'obligatorio' => true,
            ],
            [
                'texto' => 'Tiene imágenes cargadas',
                'completo' => $tieneImagenes,
                'obligatorio' => true,
            ],
            [
                'texto' => 'Tiene imagen de portada',
                'completo' => $tienePortada,
                'obligatorio' => true,
            ],
            [
                'texto' => 'Tiene descripción completa',
                'completo' => filled($this->descripcion),
                'obligatorio' => false,
            ],
            [
                'texto' => 'Tiene coordenadas confirmadas',
                'completo' => filled($this->latitud)
                    && filled($this->longitud)
                    && $this->ubicacion_confirmada,
                'obligatorio' => false,
            ],
            [
                'texto' => 'Tiene precio cargado o se mostrará como Consultar',
                'completo' => $tienePrecioOConsultar,
                'obligatorio' => false,
            ],
            [
                'texto' => 'Tiene características, servicios o amenities',
                'completo' => $tieneCaracteristicas,
                'obligatorio' => false,
            ],
        ];
    }

    public function cantidadPendientesChecklistPublicacion(): int
    {
        return collect($this->obtenerChecklistPublicacion())
            ->where('completo', false)
            ->count();
    }

    public function cantidadPendientesObligatoriosPublicacion(): int
    {
        return collect($this->obtenerChecklistPublicacion())
            ->where('obligatorio', true)
            ->where('completo', false)
            ->count();
    }

    public function estadoChecklistPublicacion(): string
    {
        $pendientesObligatorios = $this->cantidadPendientesObligatoriosPublicacion();
        $pendientes = $this->cantidadPendientesChecklistPublicacion();

        if ($pendientesObligatorios > 0) {
            return 'incompleta';
        }

        if ($pendientes > 0) {
            return 'publicada_incompleta';
        }

        return 'lista';
    }

    public function etiquetaChecklistPublicacion(): string
    {
        return match ($this->estadoChecklistPublicacion()) {
            'lista' => 'Lista',
            'publicada_incompleta' => 'Publicada incompleta',
            default => 'Incompleta',
        };
    }
}
