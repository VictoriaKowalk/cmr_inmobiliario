<?php

namespace Tests\Feature;

use App\Models\TipoUbicacion;
use App\Models\Usuario;
use App\Services\ServicioUbicaciones;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UbicacionesPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_panel_permite_navegar_buscar_y_crear_un_subbarrio(): void
    {
        $usuario = Usuario::factory()->create();
        $servicio = app(ServicioUbicaciones::class);
        $tipos = TipoUbicacion::query()->pluck('id', 'codigo');

        $argentina = $servicio->crearNodo(['tipo_ubicacion_id' => $tipos['pais'], 'nombre' => 'Argentina']);
        $provincia = $servicio->crearNodo(['ubicacion_padre_id' => $argentina->id, 'tipo_ubicacion_id' => $tipos['provincia'], 'nombre' => 'Buenos Aires']);
        $municipioSanFernando = $servicio->crearNodo(['ubicacion_padre_id' => $provincia->id, 'tipo_ubicacion_id' => $tipos['municipio'], 'nombre' => 'San Fernando']);
        $servicio->crearNodo(['ubicacion_padre_id' => $municipioSanFernando->id, 'tipo_ubicacion_id' => $tipos['localidad'], 'nombre' => 'San Fernando']);
        $servicio->crearNodo(['ubicacion_padre_id' => $municipioSanFernando->id, 'tipo_ubicacion_id' => $tipos['localidad'], 'nombre' => 'Victoria']);
        $zonaNorte = $servicio->crearNodo(['ubicacion_padre_id' => $provincia->id, 'tipo_ubicacion_id' => $tipos['zona_comercial'], 'nombre' => 'G.B.A. Zona Norte']);
        $partidoTigre = $servicio->crearNodo(['ubicacion_padre_id' => $zonaNorte->id, 'tipo_ubicacion_id' => $tipos['partido'], 'nombre' => 'Partido de Tigre']);
        $municipioTigre = $servicio->crearNodo(['ubicacion_padre_id' => $partidoTigre->id, 'tipo_ubicacion_id' => $tipos['municipio'], 'nombre' => 'Municipio de Tigre']);
        $nordelta = $servicio->crearNodo(['ubicacion_padre_id' => $municipioTigre->id, 'tipo_ubicacion_id' => $tipos['localidad'], 'nombre' => 'Nordelta']);
        $servicio->crearNodo(['ubicacion_padre_id' => $nordelta->id, 'tipo_ubicacion_id' => $tipos['subbarrio'], 'nombre' => 'El Yacht']);
        $localidad = $servicio->crearNodo(['ubicacion_padre_id' => $provincia->id, 'tipo_ubicacion_id' => $tipos['localidad'], 'nombre' => 'Benavídez']);
        $barrio = $servicio->crearNodo(['ubicacion_padre_id' => $localidad->id, 'tipo_ubicacion_id' => $tipos['barrio'], 'nombre' => 'Nordelta']);

        $this->actingAs($usuario)
            ->get(route('administracion.ubicaciones.listar', ['padre' => $argentina->id]))
            ->assertOk()
            ->assertSee('Buenos Aires');

        $this->actingAs($usuario)
            ->post(route('administracion.ubicaciones.guardar'), [
                'ubicacion_padre_id' => $barrio->id,
                'tipo_ubicacion_id' => $tipos['subbarrio'],
                'nombre' => 'Los Castores',
            ])
            ->assertSessionHasNoErrors();

        $this->actingAs($usuario)
            ->getJson(route('administracion.ubicaciones.buscar', ['buscar' => 'Castores']))
            ->assertOk()
            ->assertJsonFragment(['nombre_completo' => 'Argentina | Buenos Aires | Benavídez | Nordelta | Los Castores']);

        $this->actingAs($usuario)
            ->getJson(route('administracion.ubicaciones.buscar', ['buscar' => 'San Fernando']))
            ->assertOk()
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'nombre_mostrado' => 'San Fernando (Centro)',
                'ruta_mostrada' => 'Buenos Aires | San Fernando',
                'ruta_completa_mostrada' => 'Argentina | Buenos Aires | San Fernando | San Fernando (Centro)',
            ])
            ->assertJsonFragment([
                'nombre_mostrado' => 'Victoria',
                'ruta_mostrada' => 'Buenos Aires | San Fernando',
            ]);

        $this->actingAs($usuario)
            ->getJson(route('administracion.ubicaciones.buscar', ['buscar' => 'Yacht']))
            ->assertOk()
            ->assertJsonFragment([
                'nombre_mostrado' => 'El Yacht',
                'ruta_mostrada' => 'G.B.A. Zona Norte | Tigre | Nordelta',
            ]);
    }
}
