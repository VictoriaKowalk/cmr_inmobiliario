<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/** @extends Factory<Usuario> */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    protected static ?string $contrasenia;

    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'contrasenia' => static::$contrasenia ??= Hash::make('contrasenia'),
            'rol' => 'administrador',
            'activo' => true,
            'recordar_token' => Str::random(10),
        ];
    }
}
