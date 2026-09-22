<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id();
            $table->string('pais', 100)->index();
            $table->string('zona', 150)->nullable()->index();
            $table->string('localidad', 150)->nullable()->index();
            $table->string('categoria_barrio', 150)->nullable();
            $table->string('barrio_principal', 150)->nullable()->index();
            $table->string('barrio', 150)->nullable()->index();
            $table->string('nombre_completo', 700);
            $table->boolean('activa')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicaciones');
    }
};
