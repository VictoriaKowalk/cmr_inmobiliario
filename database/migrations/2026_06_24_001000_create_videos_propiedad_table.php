<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos_propiedad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propiedad_id')
                ->constrained('propiedades')
                ->cascadeOnDelete();
            $table->string('tipo', 30);
            $table->string('titulo')->nullable();
            $table->string('ruta', 500)->nullable();
            $table->string('url', 500)->nullable();
            $table->string('youtube_id', 50)->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamps();

            $table->index(['propiedad_id', 'orden']);
            $table->index(['propiedad_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos_propiedad');
    }
};
