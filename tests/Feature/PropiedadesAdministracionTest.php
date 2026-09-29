<?php

namespace Tests\Feature;

use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        $this->tipo = TipoPropiedad::query()->firstOrCreate([
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
        $this->assertSame('K1', $propiedad->codigo_interno);
    }

    public function test_el_codigo_se_genera_en_secuencia_y_no_acepta_el_enviado(): void
    {
        $this->actingAs($this->usuario)
            ->post(route('administracion.propiedades.guardar'), $this->datos([
                $this->operacion('venta', 'USD', 150000, 'publicada'),
            ]));

        $datos = $this->datos([
            $this->operacion('venta', 'USD', 180000, 'publicada'),
        ]);
        $datos['titulo'] = 'Segunda casa';
        $datos['codigo_interno'] = 'CODIGO-MANUAL';

        $this->actingAs($this->usuario)
            ->post(route('administracion.propiedades.guardar'), $datos)
            ->assertSessionHasNoErrors();

        $this->assertSame(
            ['K1', 'K2'],
            Propiedad::query()->orderBy('id')->pluck('codigo_interno')->all()
        );
    }

    public function test_crea_la_propiedad_con_sus_imagenes_y_video_en_un_solo_envio(): void
    {
        Storage::fake('public');

        $datos = $this->datos([
            $this->operacion('venta', 'USD', 150000, 'publicada'),
        ]);
        $imagenPng = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4z8DwHwAFgAI/ScLXXQAAAABJRU5ErkJggg=='
        );
        $datos['imagenes'] = [
            UploadedFile::fake()->createWithContent('frente.png', $imagenPng),
            UploadedFile::fake()->createWithContent('living.png', $imagenPng),
        ];
        $datos['video_titulo'] = 'Recorrido virtual';
        $datos['youtube_url'] = 'https://www.youtube.com/watch?v=abcdefghijk';

        $respuesta = $this->actingAs($this->usuario)
            ->post(route('administracion.propiedades.guardar'), $datos);

        $propiedad = Propiedad::query()->sole();

        $respuesta
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('administracion.propiedades.editar', $propiedad));
        $this->assertCount(2, $propiedad->imagenes);
        $this->assertTrue($propiedad->imagenes()->orderBy('orden')->first()->portada);
        $this->assertDatabaseHas('videos_propiedad', [
            'propiedad_id' => $propiedad->id,
            'titulo' => 'Recorrido virtual',
            'youtube_id' => 'abcdefghijk',
        ]);

        foreach ($propiedad->imagenes as $imagen) {
            Storage::disk('public')->assertExists($imagen->ruta);
        }
    }

    public function test_no_crea_la_propiedad_si_el_enlace_no_es_de_youtube(): void
    {
        $datos = $this->datos([
            $this->operacion('venta', 'USD', 150000, 'publicada'),
        ]);
        $datos['youtube_url'] = 'https://ejemplo.com/video';

        $this->actingAs($this->usuario)
            ->post(route('administracion.propiedades.guardar'), $datos)
            ->assertSessionHasErrors('youtube_url');

        $this->assertDatabaseCount('propiedades', 0);
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
