<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->dropIndex(['apto_credito']);
            $table->dropColumn([
                'apto_credito',
                'parrilla',
                'pileta',
                'seguridad',
                'amenities',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->boolean('apto_credito')->default(false)->index();
            $table->boolean('parrilla')->default(false);
            $table->boolean('pileta')->default(false);
            $table->boolean('seguridad')->default(false);
            $table->text('amenities')->nullable();
        });
    }
};
