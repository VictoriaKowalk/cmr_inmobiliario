<?php

namespace Database\Seeders;

use App\Models\TipoUbicacion;
use App\Models\Ubicacion;
use Illuminate\Database\Seeder;

class UbicacionesSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = TipoUbicacion::query()->pluck('id', 'codigo');
        $ruta = [
            ['pais', 'Argentina'],
            ['provincia', 'Buenos Aires'],
            ['zona_comercial', 'G.B.A. Zona Norte'],
            ['partido', 'Partido de Tigre'],
            ['municipio', 'Municipio de Tigre'],
            ['localidad', 'Nordelta'],
        ];

        $ubicacionPadreId = null;
        $nombresRuta = [];

        foreach ($ruta as [$codigoTipo, $nombre]) {
            $nombresRuta[] = $nombre;
            $nombreNormalizado = mb_strtolower(strtr($nombre, [
                'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n',
                'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u', 'Ü' => 'u', 'Ñ' => 'n',
            ]));

            $ubicacion = Ubicacion::query()->updateOrCreate(
                ['nombre_completo' => implode(' | ', $nombresRuta)],
                [
                    'ubicacion_padre_id' => $ubicacionPadreId,
                    'tipo_ubicacion_id' => $tipos[$codigoTipo],
                    'nombre' => $nombre,
                    'nombre_normalizado' => $nombreNormalizado,
                    'pais' => 'Argentina',
                    'origen' => 'semilla',
                    'activa' => true,
                ]
            );

            $ubicacionPadreId = $ubicacion->id;
        }
    }
}
