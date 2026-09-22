<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->unique(
                ['tipo_ubicacion_id', 'codigo_georef'],
                'ubicaciones_tipo_codigo_georef_unico'
            );
        });
    }

    public function down(): void
    {
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropUnique('ubicaciones_tipo_codigo_georef_unico');
        });
    }
};
