<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['consultas', 'tasaciones'] as $tabla) {
            Schema::table($tabla, function (Blueprint $table): void {
                $table->foreignId('responsable_id')
                    ->nullable()
                    ->after('estado_seguimiento')
                    ->constrained('usuarios')
                    ->nullOnDelete();
                $table->string('prioridad', 20)
                    ->default('media')
                    ->after('responsable_id')
                    ->index();
                $table->string('proxima_tarea', 255)
                    ->nullable()
                    ->after('prioridad');
                $table->dateTime('proxima_tarea_en')
                    ->nullable()
                    ->after('proxima_tarea')
                    ->index();
                $table->string('motivo_cierre', 500)
                    ->nullable()
                    ->after('proxima_tarea_en');
                $table->timestamp('cerrada_en')
                    ->nullable()
                    ->after('motivo_cierre');
            });
        }

        Schema::create('historial_oportunidades', function (Blueprint $table): void {
            $table->id();
            $table->morphs('oportunidad');
            $table->foreignId('usuario_id')
                ->nullable()
                ->constrained('usuarios')
                ->nullOnDelete();
            $table->string('evento', 50)->index();
            $table->text('descripcion');
            $table->json('cambios')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_oportunidades');

        foreach (['consultas', 'tasaciones'] as $tabla) {
            Schema::table($tabla, function (Blueprint $table): void {
                $table->dropConstrainedForeignId('responsable_id');
                $table->dropColumn([
                    'prioridad',
                    'proxima_tarea',
                    'proxima_tarea_en',
                    'motivo_cierre',
                    'cerrada_en',
                ]);
            });
        }
    }
};
