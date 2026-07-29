<?php

namespace Tests\Feature;

use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use App\Models\Usuario;
use App\Notifications\NotificacionNuevoContacto;
use App\Services\ServicioImagenesPropiedad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PreparacionProduccionTest extends TestCase
{
    use RefreshDatabase;

    public function test_una_consulta_notifica_a_los_administradores_activos(): void
    {
        Notification::fake();
        $activo = Usuario::factory()->create();
        $inactivo = Usuario::factory()->create(['activo' => false]);

        $this->post(route('publico.consultas.guardar'), [
            'nombre' => 'Nuevo contacto',
            'email' => 'contacto@example.com',
            'mensaje' => 'Quiero más información.',
        ])->assertSessionHasNoErrors();

        Notification::assertSentTo(
            $activo,
            NotificacionNuevoContacto::class
        );
        Notification::assertNotSentTo(
            $inactivo,
            NotificacionNuevoContacto::class
        );
    }

    public function test_el_endpoint_de_estado_comprueba_base_y_storage(): void
    {
        Storage::fake('local');

        $this->get(route('estado-sistema'))
            ->assertOk()
            ->assertJson([
                'estado' => 'ok',
                'comprobaciones' => [
                    'base_datos' => true,
                    'storage' => true,
                ],
            ]);
    }

    public function test_las_respuestas_web_incluyen_cabeceras_de_seguridad(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader(
                'Permissions-Policy',
                'camera=(), microphone=(), geolocation=(self)'
            );
    }

    public function test_las_imagenes_se_guardan_aunque_gd_no_este_disponible(): void
    {
        Storage::fake('public');
        $propiedad = $this->crearPropiedad();
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='
        );
        $archivo = UploadedFile::fake()->createWithContent('casa.png', $png);

        app(ServicioImagenesPropiedad::class)
            ->guardarImagenes($propiedad, [$archivo]);

        $imagen = $propiedad->imagenes()->sole();
        Storage::disk('public')->assertExists($imagen->ruta);
        $this->assertTrue($imagen->portada);
    }

    public function test_el_backup_no_se_ejecuta_si_esta_deshabilitado(): void
    {
        config()->set('backup.habilitado', false);

        $this->artisan('sistema:backup')
            ->expectsOutput(
                'Los backups están deshabilitados. Configurá BACKUP_ENABLED=true.'
            )
            ->assertFailed();
    }

    private function crearPropiedad(): Propiedad
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

        return Propiedad::query()->create([
            'tipo_propiedad_id' => $tipo->id,
            'ubicacion_id' => $ubicacion->id,
            'titulo' => 'Casa',
            'slug' => 'casa',
            'codigo_interno' => 'CASA-001',
        ]);
    }
}
