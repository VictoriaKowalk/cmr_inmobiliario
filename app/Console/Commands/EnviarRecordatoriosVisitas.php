<?php

namespace App\Console\Commands;

use App\Enums\EstadoVisita;
use App\Models\Visita;
use App\Notifications\RecordatorioVisita;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class EnviarRecordatoriosVisitas extends Command
{
    protected $signature = 'visitas:enviar-recordatorios';

    protected $description = 'Envía recordatorios de visitas próximas';

    public function handle(): int
    {
        Visita::query()
            ->with(['propiedad', 'asesor'])
            ->whereIn('estado', [EstadoVisita::PENDIENTE, EstadoVisita::CONFIRMADA])
            ->whereBetween('inicio', [now()->addHours(23), now()->addHours(24)])
            ->each(function (Visita $visita): void {
                if ($visita->recordatorio_asesor_enviado_en === null) {
                    $visita->asesor->notify(new RecordatorioVisita($visita, true));
                    $visita->update(['recordatorio_asesor_enviado_en' => now()]);
                }

                if ($visita->interesado_email && $visita->recordatorio_cliente_enviado_en === null) {
                    Notification::route('mail', $visita->interesado_email)
                        ->notify(new RecordatorioVisita($visita));
                    $visita->update(['recordatorio_cliente_enviado_en' => now()]);
                }
            });

        return self::SUCCESS;
    }
}
