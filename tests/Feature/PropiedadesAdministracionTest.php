<?php

namespace Tests\Feature;

use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropiedadesAdministracionTest extends TestCase
{
    use RefreshDatabase;

    private Usuario $usuario;

    private TipoPropiedad $tipo;

    private Ubicacion $ubicacion;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usuario = Usuario::factory()->create();
        $this->tipo = TipoPropiedad::query()->create([
            'nombre' => 'Casa',
            'activo' => true,
        ]);
        $this->ubicacion = Ubicacion::query()->create([
            'pais' => 'Argentina',
            'nombre_completo' => 'Argentina | Buenos Aires',
            'activa' => true,
        ]);
    }

    public function test_una_propiedad_se_guarda_con_varias_operaciones(): void
    {
        $respuesta = $this->actingAs($this->usuario)
            ->post(route('administracion.propiedades.guardar'), $this->datos([
                $this->operacion('venta', 'USD', 150000, 'publicada'),
                $this->operacion('alquiler', 'ARS', 900000, 'pausada'),
            ]));

        $propiedad = Propiedad::query()->sole();

        $respuesta->assertRedirect(
            route('administracion.propiedades.editar', $propiedad)
        );
        $this->assertCount(2, $propiedad->operaciones);
        $this->assertSame('casa-en-venta', $propiedad->slug);
    }

    public function test_no_acepta_tipos_de_operacion_repetidos(): void
    {
        $this->actingAs($this->usuario)
            ->post(route('administracion.propiedades.guardar'), $this->datos([
                $this->operacion('venta', 'USD', 150000, 'publicada'),
                $this->operacion('venta', 'USD', 170000, 'pausada'),
            ]))
            ->assertSessionHasErrors('operaciones');

        $this->assertDatabaseCount('propiedades', 0);
    }

    public function test_un_precio_requiere_moneda(): void
    {
        $this->actingAs($this->usuario)
            ->post(route('administracion.propiedades.guardar'), $this->datos([
                $this->operacion('venta', null, 150000, 'publicada'),
            ]))
            ->assertSessionHasErrors('operaciones.0.moneda');
    }

    public function test_una_venta_no_puede_marcarse_como_alquilada(): void
    {
        $this->actingAs($this->usuario)
            ->post(route('administracion.propiedades.guardar'), $this->datos([
                $this->operacion('venta', 'USD', 150000, 'alquilada'),
            ]))
            ->assertSessionHasErrors('operaciones.0.estado');
    }

    private function datos(array $operaciones): array
    {
        return [
            'tipo_propiedad_id' => $this->tipo->id,
            'ubicacion_id' => $this->ubicacion->id,
            'titulo' => 'Casa en venta',
            'codigo_interno' => 'CASA-001',
            'operaciones' => $operaciones,
        ];
    }

    private function operacion(
        string $tipo,
        ?string $moneda,
        ?int $precio,
        string $estado
    ): array {
        return [
            'activa' => true,
            'tipo_operacion' => $tipo,
            'moneda' => $moneda,
            'precio' => $precio,
            'estado' => $estado,
        ];
    }
}
