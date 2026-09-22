<?php

namespace Tests\Feature;

use App\Models\TipoUbicacion;
use App\Services\ServicioUbicaciones;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UbicacionesJerarquicasTest extends TestCase
{
    use RefreshDatabase;

    public function test_crea_una_ruta_de_ubicaciones_respetando_la_jerarquia(): void
    {
        $servicio = app(ServicioUbicaciones::class);
        $tipos = TipoUbicacion::query()->pluck('id', 'codigo');

        $argentina = $servicio->crearNodo([
            'tipo_ubicacion_id' => $tipos['pais'],
            'nombre' => 'Argentina',
            'origen' => 'oficial',
        ]);
        $provincia = $servicio->crearNodo([
            'ubicacion_padre_id' => $argentina->id,
            'tipo_ubicacion_id' => $tipos['provincia'],
            'nombre' => 'Buenos Aires',
            'origen' => 'oficial',
        ]);
        $zona = $servicio->crearNodo([
            'ubicacion_padre_id' => $provincia->id,
            'tipo_ubicacion_id' => $tipos['zona_comercial'],
            'nombre' => 'Zona Norte',
        ]);

        $this->assertSame('Zona Norte', $zona->nombre);
        $this->assertTrue($zona->padre->is($provincia));
        $this->assertSame('zona_comercial', $zona->tipoUbicacion->codigo);
    }

    public function test_no_permite_una_localidad_directamente_debajo_de_argentina(): void
    {
        $servicio = app(ServicioUbicaciones::class);
        $tipos = TipoUbicacion::query()->pluck('id', 'codigo');
        $argentina = $servicio->crearNodo([
            'tipo_ubicacion_id' => $tipos['pais'],
            'nombre' => 'Argentina',
        ]);

        $this->assertFalse(
            $servicio->tipoEsValidoParaPadre($argentina->id, $tipos['localidad'])
        );
    }
}
