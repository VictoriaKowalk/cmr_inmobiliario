<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TiposPropiedadSeeder::class,
            UbicacionesSeeder::class,
            UbicacionesTokkoNordeltaSeeder::class,
            UbicacionesTokkoZonaNorteSeeder::class,
            CaracteristicasSeeder::class,
            UsuarioAdministradorSeeder::class,
            ContactosDemoSeeder::class,
        ]);
    }
}
