<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->string('direccion_normalizada')->nullable()->after('direccion');
            $table->string('proveedor_geocodificacion', 50)->nullable()->after('longitud');
            $table->string('place_id', 255)->nullable()->after('proveedor_geocodificacion');
            $table->boolean('ubicacion_confirmada')->default(false)->after('place_id');
        });
    }

    public function down(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->dropColumn([
                'direccion_normalizada',
                'proveedor_geocodificacion',
                'place_id',
                'ubicacion_confirmada',
            ]);
        });
    }
};
