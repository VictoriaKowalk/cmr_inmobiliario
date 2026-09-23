<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tokko')->nullable()->unique()->after('codigo_georef');
            $table->string('ruta_tokko', 1000)->nullable()->after('id_tokko');
        });
    }

    public function down(): void
    {
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropUnique(['id_tokko']);
            $table->dropColumn(['id_tokko', 'ruta_tokko']);
        });
    }
};
