<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operaciones_propiedad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propiedad_id')
                ->constrained('propiedades')
                ->cascadeOnDelete();
            $table->string('tipo_operacion', 30);
            $table->string('moneda', 10)->nullable();
            $table->decimal('precio', 15, 2)->unsigned()->nullable()->index();
            $table->string('estado', 30)->default('pausada')->index();
            $table->timestamp('publicada_en')->nullable()->index();
            $table->timestamps();

            $table->unique(['propiedad_id', 'tipo_operacion']);
            $table->index(['tipo_operacion', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operaciones_propiedad');
    }
};
