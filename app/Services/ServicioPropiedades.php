<?php

namespace App\Services;

use App\Enums\EstadoOperacion;
use App\Models\Caracteristica;
use App\Models\Propiedad;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServicioPropiedades
{
    private const CAMPOS_BOOLEANOS = [
        'mostrar_direccion',
        'ubicacion_confirmada',
    ];

    public function crearPropiedad(array $datos): Propiedad
    {
        return DB::transaction(function () use ($datos): Propiedad {
            $datos['codigo_interno'] = $this->generarCodigoInterno();
            $propiedad = Propiedad::query()->create(
                $this->prepararDatosPropiedad($datos)
            );

            $this->sincronizarOperaciones(
                $propiedad,
                $datos['operaciones'] ?? []
            );
            $this->sincronizarCaracteristicas(
                $propiedad,
                $this->reunirCaracteristicas($datos)
            );

            return $propiedad->load(['operaciones', 'tipoPropiedad', 'ubicacion']);
        });
    }

    public function actualizarPropiedad(
        Propiedad $propiedad,
        array $datos
    ): Propiedad {
        return DB::transaction(function () use ($propiedad, $datos): Propiedad {
            $datos['codigo_interno'] = $propiedad->codigo_interno;
            $propiedad->update($this->prepararDatosPropiedad($datos, $propiedad));

            $this->sincronizarOperaciones(
                $propiedad,
                $datos['operaciones'] ?? []
            );
            $this->sincronizarCaracteristicas(
                $propiedad,
                $this->reunirCaracteristicas($datos)
            );

            return $propiedad->refresh()
                ->load(['operaciones', 'tipoPropiedad', 'ubicacion']);
        });
    }

    private function generarCodigoInterno(): string
    {
        $secuencia = DB::table('secuencias')
            ->where('clave', 'codigo_propiedad')
            ->lockForUpdate()
            ->first();

        $mayorExistente = Propiedad::withTrashed()
            ->where('codigo_interno', 'like', 'K%')
            ->get(['codigo_interno'])
            ->map(fn (Propiedad $propiedad) => (int) preg_replace(
                '/\D/',
                '',
                $propiedad->codigo_interno
            ))
            ->max() ?? 0;

        $siguiente = max((int) $secuencia->ultimo_numero, $mayorExistente) + 1;

        DB::table('secuencias')
            ->where('clave', 'codigo_propiedad')
            ->update([
                'ultimo_numero' => $siguiente,
                'updated_at' => now(),
            ]);

        return 'K'.$siguiente;
    }

    public function eliminarPropiedad(Propiedad $propiedad): void
    {
        $propiedad->delete();
    }

    public function restaurarPropiedad(Propiedad $propiedad): void
    {
        $propiedad->restore();
    }

    private function prepararDatosPropiedad(
        array $datos,
        ?Propiedad $propiedad = null
    ): array {
        $campos = [
            'tipo_propiedad_id',
            'ubicacion_id',
            'titulo',
            'codigo_interno',
            'expensas',
            'expensas_moneda',
            'descripcion_corta',
            'descripcion',
            'direccion',
            'direccion_normalizada',
            'ambientes',
            'dormitorios',
            'banios',
            'cocheras',
            'superficie_total',
            'superficie_cubierta',
            'superficie_descubierta',
            'superficie_terreno',
            'antiguedad',
            'orientacion',
            'latitud',
            'longitud',
            'proveedor_geocodificacion',
            'place_id',
        ];

        $resultado = Arr::only($datos, $campos);

        foreach (self::CAMPOS_BOOLEANOS as $campo) {
            $resultado[$campo] = (bool) ($datos[$campo] ?? false);
        }

        $resultado['slug'] = $this->generarSlugUnico(
            $datos['titulo'],
            $propiedad
        );

        return $resultado;
    }

    private function sincronizarOperaciones(
        Propiedad $propiedad,
        array $operaciones
    ): void {
        $tiposConservados = [];

        foreach ($operaciones as $operacion) {
            if (! (bool) ($operacion['activa'] ?? false)) {
                continue;
            }

            $tipoOperacion = $operacion['tipo_operacion'];
            $tiposConservados[] = $tipoOperacion;
            $estado = $operacion['estado'];
            $existente = $propiedad->operaciones()
                ->where('tipo_operacion', $tipoOperacion)
                ->first();

            $publicadaEn = $existente?->publicada_en;
            if ($estado === EstadoOperacion::PUBLICADA->value && ! $publicadaEn) {
                $publicadaEn = now();
            }

            $propiedad->operaciones()->updateOrCreate(
                ['tipo_operacion' => $tipoOperacion],
                [
                    'moneda' => $operacion['moneda'] ?: null,
                    'precio' => ($operacion['precio'] ?? '') !== ''
                        ? $operacion['precio']
                        : null,
                    'estado' => $estado,
                    'publicada_en' => $publicadaEn,
                ]
            );
        }

        $operacionesQuitadas = $propiedad->operaciones()
            ->whereNotIn('tipo_operacion', $tiposConservados)
            ->get();

        foreach ($operacionesQuitadas as $operacionQuitada) {
            if ($operacionQuitada->consultas()->exists()) {
                $operacionQuitada->update([
                    'estado' => EstadoOperacion::PAUSADA,
                ]);

                continue;
            }

            $operacionQuitada->delete();
        }
    }

    private function sincronizarCaracteristicas(
        Propiedad $propiedad,
        array $caracteristicas
    ): void {
        $identificadores = collect($caracteristicas)
            ->map(fn ($identificador) => (int) $identificador)
            ->filter(fn (int $identificador) => $identificador > 0)
            ->unique()
            ->values();

        $identificadoresValidos = Caracteristica::query()
            ->where('activa', true)
            ->whereKey($identificadores)
            ->pluck('id')
            ->all();

        $propiedad->caracteristicas()->sync($identificadoresValidos);
    }

    private function reunirCaracteristicas(array $datos): array
    {
        $caracteristicas = $datos['caracteristicas'] ?? [];

        if (! empty($datos['cartel_caracteristica_id'])) {
            $caracteristicas[] = $datos['cartel_caracteristica_id'];
        }

        return $caracteristicas;
    }

    private function generarSlugUnico(
        string $titulo,
        ?Propiedad $propiedad = null
    ): string {
        $base = Str::slug($titulo) ?: 'propiedad';
        $slug = $base;
        $numero = 2;

        while (Propiedad::withTrashed()
            ->where('slug', $slug)
            ->when($propiedad, fn ($consulta) => $consulta
                ->where('id', '!=', $propiedad->id))
            ->exists()) {
            $slug = "{$base}-{$numero}";
            $numero++;
        }

        return $slug;
    }
}
