<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropUnique(['nombre_completo']);
        });

        DB::table('ubicaciones')
            ->select(['id', 'nombre_completo'])
            ->orderBy('id')
            ->each(function (object $ubicacion): void {
                $rutaLimpia = preg_replace(
                    '/ \\[(Departamento|Municipio): [^\\]]+\\]/',
                    '',
                    $ubicacion->nombre_completo
                );

                if ($rutaLimpia !== $ubicacion->nombre_completo) {
                    DB::table('ubicaciones')
                        ->where('id', $ubicacion->id)
                        ->update(['nombre_completo' => $rutaLimpia]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->unique('nombre_completo');
        });
    }
};
