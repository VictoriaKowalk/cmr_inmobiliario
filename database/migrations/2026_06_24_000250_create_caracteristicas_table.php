<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caracteristicas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('categoria', 30)->index();
            $table->boolean('activa')->default(true)->index();
            $table->timestamps();

            $table->unique(['nombre', 'categoria']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('caracteristicas');
    }
};
