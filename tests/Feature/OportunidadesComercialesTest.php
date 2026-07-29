<?php

namespace Tests\Feature;

use App\Enums\EstadoSeguimiento;
use App\Enums\PrioridadOportunidad;
use App\Models\Consulta;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OportunidadesComercialesTest extends TestCase
{
    use RefreshDatabase;

    public function test_una_consulta_publica_inicia_una_oportunidad_con_historial(): void
    {
        $this->post(route('publico.consultas.guardar'), [
            'nombre' => 'María Cliente',
            'email' => 'maria@example.com',
            'mensaje' => 'Quiero recibir más información.',
        ])->assertSessionHasNoErrors();

        $consulta = Consulta::query()->sole();

        $this->assertSame(EstadoSeguimiento::NUEVA, $consulta->estado_seguimiento);
        $this->assertSame(PrioridadOportunidad::MEDIA, $consulta->prioridad);
        $this->assertDatabaseHas('historial_oportunidades', [
            'oportunidad_type' => Consulta::class,
            'oportunidad_id' => $consulta->id,
            'evento' => 'oportunidad_creada',
        ]);
    }

    public function test_un_administrador_asigna_y_actualiza_una_oportunidad(): void
    {
        $administrador = Usuario::factory()->create();
        $asesor = Usuario::factory()->create();
        $consulta = Consulta::query()->create([
            'nombre' => 'Cliente',
            'email' => 'cliente@example.com',
            'mensaje' => 'Consulta',
        ]);

        $this->actingAs($administrador)
            ->patch(route('administracion.consultas.actualizar-seguimiento', $consulta), [
                'estado_seguimiento' => EstadoSeguimiento::VISITA_COORDINADA->value,
                'responsable_id' => $asesor->id,
                'prioridad' => PrioridadOportunidad::ALTA->value,
                'proxima_tarea' => 'Confirmar la visita',
                'proxima_tarea_en' => now()->addDay()->format('Y-m-d H:i:s'),
                'notas_internas' => 'Prefiere horario por la tarde.',
            ])
            ->assertSessionHasNoErrors();

        $consulta->refresh();

        $this->assertSame(EstadoSeguimiento::VISITA_COORDINADA, $consulta->estado_seguimiento);
        $this->assertSame($asesor->id, $consulta->responsable_id);
        $this->assertSame(PrioridadOportunidad::ALTA, $consulta->prioridad);
        $this->assertSame('Confirmar la visita', $consulta->proxima_tarea);
        $this->assertDatabaseHas('historial_oportunidades', [
            'oportunidad_type' => Consulta::class,
            'oportunidad_id' => $consulta->id,
            'usuario_id' => $administrador->id,
            'evento' => 'seguimiento_actualizado',
        ]);
    }

    public function test_una_oportunidad_perdida_exige_motivo(): void
    {
        $administrador = Usuario::factory()->create();
        $consulta = Consulta::query()->create([
            'nombre' => 'Cliente',
            'email' => 'cliente@example.com',
            'mensaje' => 'Consulta',
        ]);

        $this->actingAs($administrador)
            ->patch(route('administracion.consultas.actualizar-seguimiento', $consulta), [
                'estado_seguimiento' => EstadoSeguimiento::PERDIDA->value,
                'prioridad' => PrioridadOportunidad::MEDIA->value,
            ])
            ->assertSessionHasErrors('motivo_cierre');
    }
}
