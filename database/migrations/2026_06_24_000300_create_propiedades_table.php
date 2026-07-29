<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propiedades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_propiedad_id')
                ->constrained('tipos_propiedad')
                ->restrictOnDelete();
            $table->foreignId('ubicacion_id')
                ->constrained('ubicaciones')
                ->restrictOnDelete();
            $table->string('titulo', 180)->index();
            $table->string('slug', 200)->unique();
            $table->string('codigo_interno', 50)->unique();
            $table->boolean('destacada')->default(false)->index();
            $table->decimal('expensas', 15, 2)->unsigned()->nullable();
            $table->string('descripcion_corta', 500)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('direccion')->nullable();
            $table->boolean('mostrar_direccion')->default(false);
            $table->unsignedSmallInteger('ambientes')->nullable()->index();
            $table->unsignedSmallInteger('dormitorios')->nullable()->index();
            $table->unsignedSmallInteger('banios')->nullable();
            $table->unsignedSmallInteger('cocheras')->nullable();
            $table->decimal('superficie_total', 12, 2)->unsigned()->nullable();
            $table->decimal('superficie_cubierta', 12, 2)->unsigned()->nullable();
            $table->decimal('superficie_descubierta', 12, 2)->unsigned()->nullable();
            $table->decimal('superficie_terreno', 12, 2)->unsigned()->nullable();
            $table->unsignedSmallInteger('antiguedad')->nullable();
            $table->string('orientacion', 50)->nullable();
            $table->boolean('apto_credito')->default(false)->index();
            $table->boolean('apto_profesional')->default(false);
            $table->boolean('acepta_mascotas')->default(false);
            $table->boolean('parrilla')->default(false);
            $table->boolean('pileta')->default(false);
            $table->boolean('seguridad')->default(false);
            $table->text('amenities')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propiedades');
    }
};
