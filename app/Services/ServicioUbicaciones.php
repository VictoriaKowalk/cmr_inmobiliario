<?php

namespace App\Services;

use App\Models\Ubicacion;

class ServicioUbicaciones
{
    public function crearUbicacion(array $datos): Ubicacion
    {
        return Ubicacion::query()->create([
            ...$datos,
            'nombre_completo' => $this->generarNombreCompleto($datos),
            'activa' => true,
        ]);
    }

    public function actualizarUbicacion(
        Ubicacion $ubicacion,
        array $datos
    ): Ubicacion {
        $ubicacion->update([
            ...$datos,
            'nombre_completo' => $this->generarNombreCompleto($datos),
        ]);

        return $ubicacion->refresh();
    }

    public function generarNombreCompleto(array $datos): string
    {
        return collect([
            $datos['pais'] ?? null,
            $datos['zona'] ?? null,
            $datos['localidad'] ?? null,
            $datos['categoria_barrio'] ?? null,
            $datos['barrio_principal'] ?? null,
            $datos['barrio'] ?? null,
        ])->filter(
            fn ($valor) => $valor !== null && $valor !== ''
        )->implode(' | ');
    }
}
