<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->index();
            $table->string('email')->nullable()->index();
            $table->string('telefono', 50)->nullable()->index();
            $table->foreignId('tipo_propiedad_id')
                ->nullable()
                ->constrained('tipos_propiedad')
                ->restrictOnDelete();
            $table->string('ubicacion_texto', 500);
            $table->string('direccion')->nullable();
            $table->text('mensaje')->nullable();
            $table->string('estado_seguimiento', 30)->default('nueva')->index();
            $table->text('notas_internas')->nullable();
            $table->timestamp('leida_en')->nullable();
            $table->timestamp('atendida_en')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasaciones');
    }
};
