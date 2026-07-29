<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioAdministradorSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $contrasenia = env('ADMIN_PASSWORD');

        if (! $email || ! $contrasenia) {
            $this->command?->warn(
                'No se creo el administrador: configure ADMIN_EMAIL y ADMIN_PASSWORD.'
            );

            return;
        }

        Usuario::query()->updateOrCreate(
            ['email' => $email],
            [
                'nombre' => env('ADMIN_NOMBRE', 'Administrador'),
                'apellido' => env('ADMIN_APELLIDO'),
                'contrasenia' => Hash::make($contrasenia),
                'activo' => true,
            ],
        );
    }
}
