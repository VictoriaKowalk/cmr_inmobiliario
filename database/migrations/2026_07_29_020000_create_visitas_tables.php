<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitas', function (Blueprint $table): void {
            $table->id();
            $table->nullableMorphs('oportunidad');
            $table->foreignId('propiedad_id')->constrained('propiedades')->restrictOnDelete();
            $table->foreignId('asesor_id')->constrained('usuarios')->restrictOnDelete();
            $table->string('interesado_nombre', 150);
            $table->string('interesado_email')->nullable();
            $table->string('interesado_telefono', 50)->nullable();
            $table->dateTime('inicio')->index();
            $table->dateTime('fin')->index();
            $table->string('estado', 30)->default('pendiente')->index();
            $table->string('lugar')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('motivo_cancelacion', 500)->nullable();
            $table->string('resultado', 30)->nullable();
            $table->text('comentarios_resultado')->nullable();
            $table->string('proxima_accion')->nullable();
            $table->dateTime('proxima_accion_en')->nullable();
            $table->timestamp('recordatorio_cliente_enviado_en')->nullable();
            $table->timestamp('recordatorio_asesor_enviado_en')->nullable();
            $table->timestamps();
        });

        Schema::create('historial_visitas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('visita_id')->constrained('visitas')->cascadeOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('evento', 50);
            $table->text('descripcion');
            $table->json('datos')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_visitas');
        Schema::dropIfExists('visitas');
    }
};
