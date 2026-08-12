<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatosEmpresaTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_administrador_puede_actualizar_los_datos_de_la_empresa(): void
    {
        $administrador = Usuario::factory()->create();

        $this->actingAs($administrador)
            ->put(route('administracion.empresa.actualizar'), [
                'nombre_comercial' => 'Hemisferio Sur Propiedades',
                'razon_social' => 'Hemisferio Sur SAS',
                'email' => 'contacto@hemisferiosur.com',
                'telefono' => '+54 11 4000-0000',
                'whatsapp' => '+54 9 11 5000-0000',
                'direccion' => 'San Fernando, Buenos Aires',
                'zona_horaria' => 'America/Argentina/Buenos_Aires',
            ])
            ->assertRedirect(route('administracion.empresa.editar'));

        $this->assertDatabaseHas('empresas', [
            'nombre_comercial' => 'Hemisferio Sur Propiedades',
            'email' => 'contacto@hemisferiosur.com',
        ]);
    }

    public function test_el_logo_se_guarda_y_puede_reemplazarse(): void
    {
        Storage::fake('public');
        $administrador = Usuario::factory()->create();
        Empresa::query()->create([
            'nombre_comercial' => 'Inmobiliaria',
            'zona_horaria' => 'America/Argentina/Buenos_Aires',
        ]);

        $this->actingAs($administrador)->put(route('administracion.empresa.actualizar'), [
            'nombre_comercial' => 'Inmobiliaria',
            'zona_horaria' => 'America/Argentina/Buenos_Aires',
            'logo' => UploadedFile::fake()->createWithContent(
                'logo.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')
            ),
        ]);

        $rutaAnterior = Empresa::query()->firstOrFail()->logo_ruta;
        Storage::disk('public')->assertExists($rutaAnterior);

        $this->actingAs($administrador)->put(route('administracion.empresa.actualizar'), [
            'nombre_comercial' => 'Inmobiliaria',
            'zona_horaria' => 'America/Argentina/Buenos_Aires',
            'logo' => UploadedFile::fake()->createWithContent(
                'nuevo-logo.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wl2nEAAAAAASUVORK5CYII=')
            ),
        ]);

        Storage::disk('public')->assertMissing($rutaAnterior);
        Storage::disk('public')->assertExists(Empresa::query()->firstOrFail()->logo_ruta);
    }

    public function test_los_datos_de_empresa_requieren_nombre_y_zona_horaria_valida(): void
    {
        $administrador = Usuario::factory()->create();

        $this->actingAs($administrador)
            ->from(route('administracion.empresa.editar'))
            ->put(route('administracion.empresa.actualizar'), [
                'nombre_comercial' => '',
                'zona_horaria' => 'Zona/Inexistente',
            ])
            ->assertSessionHasErrors(['nombre_comercial', 'zona_horaria']);
    }
}
