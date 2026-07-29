<?php

namespace Tests\Feature;

use App\Enums\EstadoSeguimiento;
use App\Enums\EstadoVisita;
use App\Models\Consulta;
use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use App\Models\Usuario;
use App\Models\Visita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendaVisitasTest extends TestCase
{
    use RefreshDatabase;

    public function test_se_coordina_una_visita_y_actualiza_la_oportunidad(): void
    {
        [$administrador, $propiedad, $consulta] = $this->datosBase();

        $this->actingAs($administrador)->post(route('administracion.visitas.guardar'), [
            'consulta_id' => $consulta->id,
            'propiedad_id' => $propiedad->id,
            'asesor_id' => $administrador->id,
            'interesado_nombre' => $consulta->nombre,
            'interesado_email' => $consulta->email,
            'inicio' => now()->addDay()->format('Y-m-d H:i:s'),
            'fin' => now()->addDay()->addHour()->format('Y-m-d H:i:s'),
            'estado' => EstadoVisita::CONFIRMADA->value,
        ])->assertSessionHasNoErrors();

        $visita = Visita::query()->sole();
        $this->assertSame($consulta->id, $visita->oportunidad_id);
        $this->assertSame(Consulta::class, $visita->oportunidad_type);
        $this->assertSame(
            EstadoSeguimiento::VISITA_COORDINADA,
            $consulta->fresh()->estado_seguimiento
        );
        $this->assertDatabaseHas('historial_visitas', [
            'visita_id' => $visita->id,
            'evento' => 'creada',
        ]);
    }

    public function test_no_permite_superponer_visitas_del_mismo_asesor(): void
    {
        [$administrador, $propiedad, $consulta] = $this->datosBase();
        $inicio = now()->addDay()->startOfHour();

        Visita::query()->create([
            'propiedad_id' => $propiedad->id,
            'asesor_id' => $administrador->id,
            'interesado_nombre' => 'Primer cliente',
            'interesado_email' => 'primero@example.com',
            'inicio' => $inicio,
            'fin' => $inicio->copy()->addHour(),
            'estado' => EstadoVisita::CONFIRMADA,
        ]);

        $this->actingAs($administrador)->post(route('administracion.visitas.guardar'), [
            'consulta_id' => $consulta->id,
            'propiedad_id' => $propiedad->id,
            'asesor_id' => $administrador->id,
            'interesado_nombre' => 'Segundo cliente',
            'interesado_email' => 'segundo@example.com',
            'inicio' => $inicio->copy()->addMinutes(30)->format('Y-m-d H:i:s'),
            'fin' => $inicio->copy()->addMinutes(90)->format('Y-m-d H:i:s'),
            'estado' => EstadoVisita::PENDIENTE->value,
        ])->assertSessionHasErrors('inicio');

        $this->assertCount(1, Visita::all());
    }

    public function test_el_fin_es_opcional_y_reserva_una_hora(): void
    {
        [$administrador, $propiedad, $consulta] = $this->datosBase();
        $inicio = now()->addDay()->startOfHour();

        $this->actingAs($administrador)->post(route('administracion.visitas.guardar'), [
            'consulta_id' => $consulta->id,
            'propiedad_id' => $propiedad->id,
            'asesor_id' => $administrador->id,
            'interesado_nombre' => $consulta->nombre,
            'interesado_email' => $consulta->email,
            'inicio' => $inicio->format('Y-m-d H:i:s'),
            'estado' => EstadoVisita::PENDIENTE->value,
        ])->assertSessionHasNoErrors();

        $visita = Visita::query()->sole();

        $this->assertTrue($visita->fin->equalTo($inicio->copy()->addHour()));
    }

    private function datosBase(): array
    {
        $administrador = Usuario::factory()->create();
        $tipo = TipoPropiedad::query()->create(['nombre' => 'Casa', 'activo' => true]);
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
            'codigo_interno' => 'VIS-001',
        ]);
        $consulta = Consulta::query()->create([
            'propiedad_id' => $propiedad->id,
            'nombre' => 'Cliente',
            'email' => 'cliente@example.com',
            'mensaje' => 'Quiero visitar.',
        ]);

        return [$administrador, $propiedad, $consulta];
    }
}
