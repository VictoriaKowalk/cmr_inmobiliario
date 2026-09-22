<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $grupos = [
            'cartel' => [
                'Tiene cartel',
                'Sin cartel',
            ],
            'observacion' => [
                'Oportunidad',
                'Acepta Lote',
                'Acepta Permuta',
                'Apto Credito',
                'Venta Con Renta',
            ],
            'preferencia_lote' => [
                'Al Rio',
                'Interno',
                'Al Golf',
                'Al lago',
                'Perimetral',
                'Lindero Interno',
            ],
            'amenity' => [
                'Aire Acondicionado individual',
                'Alarma',
                'Amoblado',
                'Calefacción',
                'Centro de deportes',
                'Gimnasio',
                'Hidromasaje',
                'Parrilla',
                'Quincho',
                'Sala de juegos',
                'Sauna',
                'Solarium',
                'SUM',
                'Cancha de Paddle',
                'Pileta',
                'Riego automático',
                'Seguridad Privada',
                'Luminoso',
                'Amarra',
                'Laundry',
                'Seguridad 24hs',
            ],
        ];

        foreach ($grupos as $categoria => $nombres) {
            foreach ($nombres as $nombre) {
                DB::table('caracteristicas')->updateOrInsert(
                    [
                        'nombre' => $nombre,
                        'categoria' => $categoria,
                    ],
                    [
                        'activa' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        $mapeos = [
            'apto_credito' => ['Apto Credito', 'observacion'],
            'parrilla' => ['Parrilla', 'amenity'],
            'pileta' => ['Pileta', 'amenity'],
            'seguridad' => ['Seguridad Privada', 'amenity'],
        ];

        foreach ($mapeos as $campo => [$nombre, $categoria]) {
            $caracteristicaId = DB::table('caracteristicas')
                ->where('nombre', $nombre)
                ->where('categoria', $categoria)
                ->value('id');

            DB::table('propiedades')
                ->where($campo, true)
                ->orderBy('id')
                ->each(function ($propiedad) use ($caracteristicaId): void {
                    DB::table('caracteristica_propiedad')->updateOrInsert(
                        [
                            'propiedad_id' => $propiedad->id,
                            'caracteristica_id' => $caracteristicaId,
                        ],
                        [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                });
        }
    }

    public function down(): void
    {
        $categorias = [
            'cartel',
            'observacion',
            'preferencia_lote',
            'amenity',
        ];

        $ids = DB::table('caracteristicas')
            ->whereIn('categoria', $categorias)
            ->pluck('id');

        DB::table('caracteristica_propiedad')
            ->whereIn('caracteristica_id', $ids)
            ->delete();

        DB::table('caracteristicas')
            ->whereIn('id', $ids)
            ->delete();
    }
};
