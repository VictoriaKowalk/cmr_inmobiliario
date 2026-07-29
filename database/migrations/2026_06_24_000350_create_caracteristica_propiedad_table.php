<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caracteristica_propiedad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propiedad_id')
                ->constrained('propiedades')
                ->cascadeOnDelete();
            $table->foreignId('caracteristica_id')
                ->constrained('caracteristicas')
                ->restrictOnDelete();
            $table->timestamps();

            $table->unique(['propiedad_id', 'caracteristica_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caracteristica_propiedad');
    }
};
