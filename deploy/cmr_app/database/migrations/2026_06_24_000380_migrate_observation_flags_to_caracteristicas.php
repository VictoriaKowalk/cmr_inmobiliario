<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $mapeos = [
            'destacada' => 'Propiedad destacada',
            'apto_profesional' => 'Apto profesional',
            'acepta_mascotas' => 'Acepta mascotas',
        ];

        foreach ($mapeos as $campo => $nombre) {
            DB::table('caracteristicas')->updateOrInsert(
                [
                    'nombre' => $nombre,
                    'categoria' => 'observacion',
                ],
                [
                    'activa' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $caracteristicaId = DB::table('caracteristicas')
                ->where('nombre', $nombre)
                ->where('categoria', 'observacion')
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

        Schema::table('propiedades', function (Blueprint $table) {
            $table->dropIndex(['destacada']);
            $table->dropColumn([
                'destacada',
                'apto_profesional',
                'acepta_mascotas',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->boolean('destacada')->default(false)->index();
            $table->boolean('apto_profesional')->default(false);
            $table->boolean('acepta_mascotas')->default(false);
        });

        $mapeos = [
            'destacada' => 'Propiedad destacada',
            'apto_profesional' => 'Apto profesional',
            'acepta_mascotas' => 'Acepta mascotas',
        ];

        foreach ($mapeos as $campo => $nombre) {
            $caracteristicaId = DB::table('caracteristicas')
                ->where('nombre', $nombre)
                ->where('categoria', 'observacion')
                ->value('id');

            $propiedadIds = DB::table('caracteristica_propiedad')
                ->where('caracteristica_id', $caracteristicaId)
                ->pluck('propiedad_id');

            DB::table('propiedades')
                ->whereIn('id', $propiedadIds)
                ->update([$campo => true]);
        }
    }
};
