<?php

namespace Tests\Feature;

use App\Models\Caracteristica;
use App\Models\Consulta;
use App\Models\OperacionPropiedad;
use App\Models\Propiedad;
use App\Models\Tasacion;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CierreFuncionalPanelTest extends TestCase
{
    use RefreshDatabase;

    private Usuario $administrador;

    protected function setUp(): void
    {
        parent::setUp();
        $this->administrador = Usuario::factory()->create();
    }

    public function test_se_puede_crear_un_nuevo_administrador(): void
    {
        $this->actingAs($this->administrador)
            ->post(route('administracion.usuarios.guardar'), [
                'nombre' => 'Victoria',
                'apellido' => 'Pérez',
                'email' => 'victoria@example.com',
                'contrasenia' => 'ClaveSegura123',
                'contrasenia_confirmation' => 'ClaveSegura123',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('usuarios', [
            'email' => 'victoria@example.com',
            'activo' => true,
        ]);
    }

    public function test_un_administrador_no_puede_desactivarse_a_si_mismo(): void
    {
        $this->actingAs($this->administrador)
            ->patch(route(
                'administracion.usuarios.cambiar-estado',
                $this->administrador
            ))
            ->assertSessionHasErrors('usuario');

        $this->assertTrue($this->administrador->fresh()->activo);
    }

    public function test_se_puede_desactivar_otro_administrador(): void
    {
        $otro = Usuario::factory()->create();

        $this->actingAs($this->administrador)
            ->patch(route('administracion.usuarios.cambiar-estado', $otro))
            ->assertSessionHasNoErrors();

        $this->assertFalse($otro->fresh()->activo);
    }

    public function test_se_puede_crear_y_desactivar_una_caracteristica(): void
    {
        $this->actingAs($this->administrador)
            ->post(route('administracion.caracteristicas.guardar'), [
                'nombre' => 'Vista al río',
                'categoria' => 'adicional',
            ])
            ->assertSessionHasNoErrors();

        $caracteristica = Caracteristica::query()
            ->where('nombre', 'Vista al río')
            ->sole();
        $this->assertTrue($caracteristica->activa);

        $this->actingAs($this->administrador)
            ->patch(route(
                'administracion.caracteristicas.cambiar-estado',
                $caracteristica
            ));

        $this->assertFalse($caracteristica->fresh()->activa);
    }

    public function test_la_bandeja_unificada_muestra_consultas_y_tasaciones(): void
    {
        Consulta::query()->create([
            'nombre' => 'Consulta Uno',
            'email' => 'consulta@example.com',
            'mensaje' => 'Mensaje',
        ]);
        Tasacion::query()->create([
            'nombre' => 'Tasación Dos',
            'telefono' => '11223344',
            'ubicacion_texto' => 'Tigre',
        ]);

        $this->actingAs($this->administrador)
            ->get(route('administracion.contactos.listar'))
            ->assertOk()
            ->assertSee('Consulta Uno')
            ->assertSee('Tasación Dos')
            ->assertSee('Consulta general')
            ->assertSee('Tasación');
    }

    public function test_la_bandeja_unificada_filtra_por_tipo(): void
    {
        Consulta::query()->create([
            'nombre' => 'Sólo consulta',
            'email' => 'consulta@example.com',
            'mensaje' => 'Mensaje',
        ]);
        Tasacion::query()->create([
            'nombre' => 'No mostrar tasación',
            'telefono' => '11223344',
            'ubicacion_texto' => 'Tigre',
        ]);

        $this->actingAs($this->administrador)
            ->get(route('administracion.contactos.listar', [
                'tipo' => 'consulta_general',
            ]))
            ->assertSee('Sólo consulta')
            ->assertDontSee('No mostrar tasación');
    }

    public function test_se_puede_actualizar_precio_moneda_y_estado_de_operacion(): void
    {
        [$propiedad, $operacion] = $this->crearPropiedad();

        $this->actingAs($this->administrador)
            ->put(route(
                'administracion.propiedades.operaciones.actualizar',
                [$propiedad, $operacion]
            ), [
                'moneda' => 'USD',
                'precio' => 250000,
                'estado' => 'publicada',
            ])
            ->assertSessionHasNoErrors();

        $operacion->refresh();
        $this->assertSame('250000.00', $operacion->precio);
        $this->assertSame('USD', $operacion->moneda->value);
        $this->assertSame('publicada', $operacion->estado->value);
        $this->assertNotNull($operacion->publicada_en);
    }

    public function test_la_edicion_rapida_rechaza_un_estado_incompatible(): void
    {
        [$propiedad, $operacion] = $this->crearPropiedad();

        $this->actingAs($this->administrador)
            ->put(route(
                'administracion.propiedades.operaciones.actualizar',
                [$propiedad, $operacion]
            ), [
                'moneda' => 'USD',
                'precio' => 250000,
                'estado' => 'alquilada',
            ])
            ->assertSessionHasErrors('estado');
    }

    private function crearPropiedad(): array
    {
        $tipo = TipoPropiedad::query()->create([
            'nombre' => 'Casa',
            'activo' => true,
        ]);
        $ubicacion = Ubicacion::query()->create([
            'pais' => 'Argentina',
            'nombre_completo' => 'Argentina',
            'activa' => true,
        ]);
        $propiedad = Propiedad::query()->create([
            'tipo_propiedad_id' => $tipo->id,
            'ubicacion_id' => $ubicacion->id,
            'titulo' => 'Casa',
            'slug' => 'casa',
            'codigo_interno' => 'CASA-001',
        ]);
        $operacion = OperacionPropiedad::query()->create([
            'propiedad_id' => $propiedad->id,
            'tipo_operacion' => 'venta',
            'estado' => 'pausada',
        ]);

        return [$propiedad, $operacion];
    }
}
