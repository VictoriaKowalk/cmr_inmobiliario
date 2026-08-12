<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secuencias', function (Blueprint $table): void {
            $table->string('clave')->primary();
            $table->unsignedBigInteger('ultimo_numero')->default(0);
            $table->timestamps();
        });

        DB::table('secuencias')->insert([
            'clave' => 'codigo_propiedad',
            'ultimo_numero' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('secuencias');
    }
};
