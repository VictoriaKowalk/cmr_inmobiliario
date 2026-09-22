<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->string('expensas_moneda', 10)->nullable()->after('expensas');
        });

        DB::table('propiedades')
            ->whereNotNull('expensas')
            ->whereNull('expensas_moneda')
            ->update(['expensas_moneda' => 'ARS']);
    }

    public function down(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->dropColumn('expensas_moneda');
        });
    }
};
