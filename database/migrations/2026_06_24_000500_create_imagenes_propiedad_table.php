<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imagenes_propiedad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propiedad_id')
                ->constrained('propiedades')
                ->cascadeOnDelete();
            $table->string('ruta', 500);
            $table->string('nombre_original')->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->boolean('portada')->default(false);
            $table->timestamps();

            $table->index(['propiedad_id', 'orden']);
            $table->index(['propiedad_id', 'portada']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imagenes_propiedad');
    }
};
