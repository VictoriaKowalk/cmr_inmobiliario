<?php

namespace Tests\Feature;

use App\Models\Consulta;
use App\Models\OperacionPropiedad;
use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormulariosPublicosTest extends TestCase
{
    use RefreshDatabase;

    public function test_una_consulta_general_valida_se_guarda(): void
    {
        $this->post(route('publico.consultas.guardar'), [
            'nombre' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'telefono' => null,
            'mensaje' => 'Quiero recibir información.',
            'sitio_web' => null,
        ])->assertSessionHasNoErrors()
            ->assertSessionHas('estado');

        $this->assertDatabaseHas('consultas', [
            'nombre' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'propiedad_id' => null,
        ]);
    }

    public function test_el_honeypot_rechaza_bots(): void
    {
        $this->post(route('publico.consultas.guardar'), [
            'nombre' => 'Bot',
            'email' => 'bot@example.com',
            'mensaje' => 'Spam',
            'sitio_web' => 'https://spam.example.com',
        ])->assertSessionHasErrors('sitio_web');

        $this->assertDatabaseCount('consultas', 0);
    }

    public function test_no_se_puede_consultar_una_propiedad_sin_operaciones_publicadas(): void
    {
        [$propiedad] = $this->crearPropiedadConOperacion('pausada');

        $this->get(route(
            'publico.propiedades.consultas.crear',
            $propiedad->slug
        ))->assertNotFound();
    }

    public function test_una_consulta_conserva_la_propiedad_y_operacion_publicada(): void
    {
        [$propiedad, $operacion] = $this->crearPropiedadConOperacion('publicada');

        $this->post(route(
            'publico.propiedades.consultas.guardar',
            $propiedad->slug
        ), [
            'nombre' => 'Juan Pérez',
            'telefono' => '1122334455',
            'mensaje' => 'Quiero visitar la propiedad.',
            'operacion_propiedad_id' => $operacion->id,
            'sitio_web' => null,
        ])->assertSessionHasNoErrors();

        $consulta = Consulta::query()->sole();
        $this->assertSame($propiedad->id, $consulta->propiedad_id);
        $this->assertSame($operacion->id, $consulta->operacion_propiedad_id);
    }

    private function crearPropiedadConOperacion(
        string $estado
    ): array {
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
            'titulo' => 'Casa de prueba',
            'slug' => 'casa-de-prueba',
            'codigo_interno' => 'CASA-001',
        ]);
        $operacion = OperacionPropiedad::query()->create([
            'propiedad_id' => $propiedad->id,
            'tipo_operacion' => 'venta',
            'moneda' => 'USD',
            'precio' => 100000,
            'estado' => $estado,
        ]);

        return [$propiedad, $operacion];
    }
}
