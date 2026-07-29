<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AutenticacionAdministrativaTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_administrador_activo_puede_ingresar(): void
    {
        $usuario = Usuario::factory()->create([
            'email' => 'admin@example.com',
            'contrasenia' => 'Contrasenia123',
        ]);

        $this->post(route('administracion.ingresar'), [
            'email' => $usuario->email,
            'contrasenia' => 'Contrasenia123',
        ])->assertRedirect(route('administracion.dashboard'));

        $this->assertAuthenticatedAs($usuario);
        $this->assertNotNull($usuario->fresh()->ultimo_acceso_en);
    }

    public function test_un_usuario_inactivo_no_puede_ingresar(): void
    {
        Usuario::factory()->create([
            'email' => 'inactivo@example.com',
            'contrasenia' => 'Contrasenia123',
            'activo' => false,
        ]);

        $this->post(route('administracion.ingresar'), [
            'email' => 'inactivo@example.com',
            'contrasenia' => 'Contrasenia123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_el_login_limita_los_intentos_repetidos(): void
    {
        Usuario::factory()->create([
            'email' => 'admin@example.com',
            'contrasenia' => 'Contrasenia123',
        ]);

        for ($intento = 0; $intento < 5; $intento++) {
            $this->post(route('administracion.ingresar'), [
                'email' => 'admin@example.com',
                'contrasenia' => 'incorrecta',
            ]);
        }

        $this->post(route('administracion.ingresar'), [
            'email' => 'admin@example.com',
            'contrasenia' => 'incorrecta',
        ])->assertSessionHasErrors([
            'email' => 'Realizaste demasiados intentos. Esperá un minuto y volvé a probar.',
        ]);
    }

    public function test_un_administrador_puede_cambiar_su_contrasenia(): void
    {
        $usuario = Usuario::factory()->create([
            'contrasenia' => 'Contrasenia123',
        ]);

        $this->actingAs($usuario)
            ->put(route('administracion.cuenta.actualizar-contrasenia'), [
                'contrasenia_actual' => 'Contrasenia123',
                'contrasenia' => 'NuevaClave456',
                'contrasenia_confirmation' => 'NuevaClave456',
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('estado');

        $this->assertTrue(
            Hash::check('NuevaClave456', $usuario->fresh()->contrasenia)
        );
    }

    public function test_no_se_puede_cambiar_la_contrasenia_sin_la_actual(): void
    {
        $usuario = Usuario::factory()->create([
            'contrasenia' => 'Contrasenia123',
        ]);

        $this->actingAs($usuario)
            ->put(route('administracion.cuenta.actualizar-contrasenia'), [
                'contrasenia_actual' => 'equivocada',
                'contrasenia' => 'NuevaClave456',
                'contrasenia_confirmation' => 'NuevaClave456',
            ])
            ->assertSessionHasErrors('contrasenia_actual');

        $this->assertTrue(
            Hash::check('Contrasenia123', $usuario->fresh()->contrasenia)
        );
    }
}
