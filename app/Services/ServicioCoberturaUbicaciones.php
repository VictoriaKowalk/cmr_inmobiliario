<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\Ubicacion;
use Illuminate\Database\Eloquent\Builder;

class ServicioCoberturaUbicaciones
{
    public function empresaActual(): Empresa
    {
        return Empresa::query()->firstOrCreate([], [
            'nombre_comercial' => config('app.name'),
            'zona_horaria' => config('app.timezone'),
        ]);
    }

    public function aplicarCobertura(Builder $consulta, ?Empresa $empresa = null): Builder
    {
        $zonas = ($empresa ?? $this->empresaActual())
            ->zonasCobertura()
            ->get(['ubicaciones.id', 'ubicaciones.nombre_completo']);

        if ($zonas->isEmpty()) {
            return $consulta;
        }

        return $consulta->where(function (Builder $ubicaciones) use ($zonas): void {
            foreach ($zonas as $zona) {
                $ubicaciones
                    ->orWhere('ubicaciones.id', $zona->id)
                    ->orWhere('ubicaciones.nombre_completo', 'like', $zona->nombre_completo.' | %');
            }
        });
    }

    public function incluye(int $ubicacionId): bool
    {
        $ubicacion = Ubicacion::query()->find($ubicacionId);

        if ($ubicacion === null) {
            return false;
        }

        $zonas = $this->empresaActual()
            ->zonasCobertura()
            ->get(['ubicaciones.id', 'ubicaciones.nombre_completo']);

        return $zonas->isEmpty() || $zonas->contains(
            fn (Ubicacion $zona): bool => $ubicacion->id === $zona->id
                || str_starts_with($ubicacion->nombre_completo, $zona->nombre_completo.' | ')
        );
    }
}
