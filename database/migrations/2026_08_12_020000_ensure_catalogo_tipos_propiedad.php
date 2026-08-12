<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tipos = [
            'Terreno', 'Departamento', 'Casa', 'Quinta', 'Oficina', 'Amarra', 'Local',
            'Edificio Comercial', 'Campo', 'Cochera', 'Hotel', 'Nave Industrial', 'PH',
            'Deposito', 'Fondo de Comercio', 'Baulera', 'Bodega', 'Finca', 'Chacra',
            'Cama nautica', 'Isla', 'Terraza', 'Galpon', 'Villa', 'Terreno comercial',
            'Terreno industrial', 'Hacienda', 'Haras', 'Consultorio', 'Monoambiente',
            'Terreno en condominio',
        ];

        foreach ($tipos as $nombre) {
            DB::table('tipos_propiedad')->insertOrIgnore([
                'nombre' => $nombre, 'activo' => true,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Se preserva el catálogo para proteger asociaciones existentes.
    }
};
