<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table): void {
            $table->string('telefono', 50)->nullable()->after('email');
            $table->string('celular', 50)->nullable()->after('telefono');
            $table->string('direccion', 255)->nullable()->after('celular');
            $table->string('dni', 20)->nullable()->unique()->after('direccion');
            $table->date('fecha_nacimiento')->nullable()->after('dni');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table): void {
            $table->dropUnique(['dni']);
            $table->dropColumn(['telefono', 'celular', 'direccion', 'dni', 'fecha_nacimiento']);
        });
    }
};
