<?php

namespace Database\Seeders;

use App\Models\TipoPropiedad;
use Illuminate\Database\Seeder;

class TiposPropiedadSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            'Terreno',
            'Departamento',
            'Casa',
            'Quinta',
            'Oficina',
            'Amarra',
            'Local',
            'Edificio Comercial',
            'Campo',
            'Cochera',
            'Hotel',
            'Nave Industrial',
            'PH',
            'Deposito',
            'Fondo de Comercio',
            'Baulera',
            'Bodega',
            'Finca',
            'Chacra',
            'Cama nautica',
            'Isla',
            'Terraza',
            'Galpon',
            'Villa',
            'Terreno comercial',
            'Terreno industrial',
            'Hacienda',
            'Haras',
            'Consultorio',
            'Monoambiente',
            'Terreno en condominio',
        ];

        foreach ($tipos as $nombre) {
            TipoPropiedad::query()->updateOrCreate(
                ['nombre' => $nombre],
                ['activo' => true],
            );
        }
    }
}
