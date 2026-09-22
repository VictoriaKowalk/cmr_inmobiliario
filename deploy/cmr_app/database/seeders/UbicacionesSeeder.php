<?php

namespace Database\Seeders;

use App\Models\Ubicacion;
use Illuminate\Database\Seeder;

class UbicacionesSeeder extends Seeder
{
    public function run(): void
    {
        $ubicaciones = [
            [
                'pais' => 'Argentina',
                'zona' => 'G.B.A. Zona Norte',
                'localidad' => 'Tigre',
                'categoria_barrio' => 'Countries/B.Cerrado (Tigre)',
                'barrio_principal' => 'Nordelta',
                'barrio' => 'El Yacht',
            ],
        ];

        foreach ($ubicaciones as $datos) {
            $nombreCompleto = collect($datos)->filter()->implode(' | ');

            Ubicacion::query()->updateOrCreate(
                ['nombre_completo' => $nombreCompleto],
                [...$datos, 'activa' => true],
            );
        }
    }
}
