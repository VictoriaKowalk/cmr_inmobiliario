<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class UbicacionesTokkoZonaNorteSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('ubicaciones:importar-tokko-zona-norte');
        $this->command?->line(Artisan::output());
    }
}
