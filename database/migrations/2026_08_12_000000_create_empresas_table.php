<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre_comercial', 150);
            $table->string('razon_social', 180)->nullable();
            $table->string('email')->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('whatsapp', 50)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('zona_horaria', 80)->default('America/Argentina/Buenos_Aires');
            $table->string('logo_ruta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
