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

class IndicadoresComercialesTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_dashboard_muestra_indicadores_comerciales_del_periodo(): void
    {
        $asesor = Usuario::factory()->create(['nombre' => 'Ana', 'apellido' => 'Asesora']);
        $propiedad = $this->crearPropiedad();
        $consulta = Consulta::query()->create([
            'propiedad_id' => $propiedad->id,
            'responsable_id' => $asesor->id,
            'nombre' => 'Cliente interesado',
            'email' => 'cliente@example.com',
            'mensaje' => 'Quiero visitar.',
            'estado_seguimiento' => EstadoSeguimiento::GANADA,
            'atendida_en' => now()->addMinutes(30),
        ]);
        Visita::query()->create([
            'oportunidad_type' => Consulta::class,
            'oportunidad_id' => $consulta->id,
            'propiedad_id' => $propiedad->id,
            'asesor_id' => $asesor->id,
            'interesado_nombre' => $consulta->nombre,
            'interesado_email' => $consulta->email,
            'inicio' => now()->addDay(),
            'fin' => now()->addDay()->addHour(),
            'estado' => EstadoVisita::REALIZADA,
        ]);

        $this->actingAs($asesor)
            ->get(route('administracion.contactos.metricas', [
                'desde' => now()->subDay()->toDateString(),
                'hasta' => now()->addDays(2)->toDateString(),
            ]))
            ->assertOk()
            ->assertSee('Indicadores del período')
            ->assertSee('Actividad comercial')
            ->assertSee('Embudo comercial')
            ->assertSee('Origen de los contactos')
            ->assertSee('Consulta → visita')
            ->assertSee('100,0%')
            ->assertSee('Casa con demanda')
            ->assertSee('Ana Asesora');
    }

    public function test_el_dashboard_admite_un_periodo_sin_datos(): void
    {
        $administrador = Usuario::factory()->create();

        $this->actingAs($administrador)
            ->get(route('administracion.contactos.metricas', [
                'desde' => '2020-01-01',
                'hasta' => '2020-01-31',
            ]))
            ->assertOk()
            ->assertSee('0,0%')
            ->assertSee('No hubo consultas asociadas a propiedades');
    }

    public function test_el_dashboard_general_muestra_un_resumen_comercial(): void
    {
        $administrador = Usuario::factory()->create();

        $this->actingAs($administrador)
            ->get(route('administracion.dashboard'))
            ->assertOk()
            ->assertSee('Propiedades publicadas')
            ->assertSee('Contactos sin asignar')
            ->assertSee('Visitas de hoy')
            ->assertSee('Hoy y próximas visitas');
    }

    private function crearPropiedad(): Propiedad
    {
        $tipo = TipoPropiedad::query()->firstOrCreate(['nombre' => 'Casa'], ['activo' => true]);
        $ubicacion = Ubicacion::query()->create([
            'pais' => 'Argentina',
            'nombre_completo' => 'Argentina',
            'activa' => true,
        ]);

        return Propiedad::query()->create([
            'tipo_propiedad_id' => $tipo->id,
            'ubicacion_id' => $ubicacion->id,
            'titulo' => 'Casa con demanda',
            'slug' => 'casa-con-demanda',
            'codigo_interno' => 'MET-001',
        ]);
    }
}
