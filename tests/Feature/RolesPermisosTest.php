<?php

namespace Tests\Feature;

use App\Models\Consulta;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolesPermisosTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_administrador_puede_ver_la_matriz_de_permisos(): void
    {
        $administrador = Usuario::factory()->create(['rol' => 'administrador']);

        $this->actingAs($administrador)
            ->get(route('administracion.usuarios.permisos'))
            ->assertOk()
            ->assertSee('ROLES Y PERMISOS')
            ->assertSee('Supervisor')
            ->assertSee('Asesor');
    }

    public function test_supervisor_y_asesor_no_pueden_gestionar_usuarios_ni_empresa(): void
    {
        foreach (['supervisor', 'asesor'] as $rol) {
            $usuario = Usuario::factory()->create(['rol' => $rol]);
            $this->actingAs($usuario)->get(route('administracion.usuarios.listar'))->assertForbidden();
            $this->actingAs($usuario)->get(route('administracion.empresa.editar'))->assertForbidden();
        }
    }

    public function test_el_supervisor_puede_ver_metricas_y_el_asesor_no(): void
    {
        $supervisor = Usuario::factory()->create(['rol' => 'supervisor']);
        $asesor = Usuario::factory()->create(['rol' => 'asesor']);

        $this->actingAs($supervisor)->get(route('administracion.contactos.metricas'))->assertOk();
        $this->actingAs($asesor)->get(route('administracion.contactos.metricas'))->assertForbidden();
    }

    public function test_el_asesor_solo_ve_sus_oportunidades_asignadas(): void
    {
        $asesor = Usuario::factory()->create(['rol' => 'asesor']);
        $propia = Consulta::query()->create([
            'nombre' => 'Contacto propio',
            'email' => 'propio@example.com',
            'mensaje' => 'Consulta',
            'responsable_id' => $asesor->id,
        ]);
        $ajena = Consulta::query()->create([
            'nombre' => 'Contacto ajeno',
            'email' => 'ajeno@example.com',
            'mensaje' => 'Consulta',
        ]);

        $this->actingAs($asesor)
            ->get(route('administracion.contactos.listar'))
            ->assertOk()
            ->assertSee($propia->nombre)
            ->assertDontSee($ajena->nombre);
        $this->actingAs($asesor)
            ->get(route('administracion.consultas.mostrar', $ajena))
            ->assertForbidden();
    }

    public function test_no_se_puede_cambiar_el_rol_del_ultimo_administrador_activo(): void
    {
        $administrador = Usuario::factory()->create(['rol' => 'administrador']);

        $this->actingAs($administrador)
            ->put(route('administracion.usuarios.actualizar', $administrador), [
                'nombre' => $administrador->nombre,
                'apellido' => $administrador->apellido,
                'email' => $administrador->email,
                'rol' => 'supervisor',
            ])
            ->assertSessionHasErrors('rol');

        $this->assertTrue($administrador->fresh()->esAdministrador());
    }

    public function test_la_validacion_de_contrasena_muestra_un_mensaje_claro_en_espanol(): void
    {
        $administrador = Usuario::factory()->create(['rol' => 'administrador']);

        $this->actingAs($administrador)
            ->post(route('administracion.usuarios.guardar'), [
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'email' => 'juan@example.com',
                'rol' => 'asesor',
                'contrasenia' => 'Corta1',
                'contrasenia_confirmation' => 'Corta1',
            ])
            ->assertSessionHasErrors([
                'contrasenia' => 'La contraseña debe tener al menos 10 caracteres e incluir mayúsculas, minúsculas y números.',
            ]);
    }
}
