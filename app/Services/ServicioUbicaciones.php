<?php

namespace App\Services;

use App\Models\ReglaTipoUbicacion;
use App\Models\TipoUbicacion;
use App\Models\Ubicacion;
use Illuminate\Validation\ValidationException;

class ServicioUbicaciones
{
    public function crearNodo(array $datos): Ubicacion
    {
        $padreId = $datos['ubicacion_padre_id'] ?? null;
        $tipoId = $datos['tipo_ubicacion_id'];
        $nombre = trim((string) $datos['nombre']);

        if (! $this->tipoEsValidoParaPadre($padreId, $tipoId)) {
            throw ValidationException::withMessages([
                'tipo_ubicacion_id' => 'El tipo de ubicación no es válido para la ubicación padre elegida.',
            ]);
        }

        return Ubicacion::query()->create([
            'ubicacion_padre_id' => $padreId,
            'tipo_ubicacion_id' => $tipoId,
            'nombre' => $nombre,
            'nombre_normalizado' => $this->normalizarNombre($nombre),
            'codigo_georef' => $datos['codigo_georef'] ?? null,
            'origen' => $datos['origen'] ?? 'personalizado',
            'activa' => $datos['activa'] ?? true,
            'pais' => 'Argentina',
            'nombre_completo' => $this->generarRuta($padreId, $nombre),
        ]);
    }

    public function actualizarNodo(Ubicacion $ubicacion, string $nombre): Ubicacion
    {
        $nombre = trim($nombre);
        $ubicacion->update([
            'nombre' => $nombre,
            'nombre_normalizado' => $this->normalizarNombre($nombre),
            'nombre_completo' => $this->generarRuta($ubicacion->ubicacion_padre_id, $nombre),
        ]);

        $this->actualizarRutasHijas($ubicacion->refresh());

        return $ubicacion->refresh();
    }

    public function tipoEsValidoParaPadre(?int $ubicacionPadreId, int $tipoUbicacionId): bool
    {
        $tipo = TipoUbicacion::query()->findOrFail($tipoUbicacionId);

        if ($ubicacionPadreId === null) {
            return $tipo->codigo === 'pais';
        }

        $padre = Ubicacion::query()->findOrFail($ubicacionPadreId);

        return ReglaTipoUbicacion::query()
            ->where('tipo_ubicacion_padre_id', $padre->tipo_ubicacion_id)
            ->where('tipo_ubicacion_hijo_id', $tipoUbicacionId)
            ->where('activa', true)
            ->exists();
    }

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

    private function normalizarNombre(string $nombre): string
    {
        return mb_strtolower(
            strtr(trim($nombre), [
                'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n',
                'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u', 'Ü' => 'u', 'Ñ' => 'n',
            ])
        );
    }

    private function generarRuta(?int $padreId, string $nombre): string
    {
        if ($padreId === null) return $nombre;
        $padre = Ubicacion::query()->findOrFail($padreId);
        return "{$padre->nombre_completo} | {$nombre}";
    }

    private function actualizarRutasHijas(Ubicacion $ubicacion): void
    {
        $ubicacion->hijos()->each(function (Ubicacion $hija) use ($ubicacion): void {
            $hija->update(['nombre_completo' => "{$ubicacion->nombre_completo} | {$hija->nombre}"]);
            $this->actualizarRutasHijas($hija->refresh());
        });
    }
}
