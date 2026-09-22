<?php

namespace App\Notifications;

use App\Models\Visita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecordatorioVisita extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Visita $visita, public bool $paraAsesor = false)
    {
        $this->onQueue('notificaciones');
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Recordatorio de visita · '.$this->visita->inicio->format('d/m H:i'))
            ->greeting('Recordatorio de visita')
            ->line('Interesado: '.$this->visita->interesado_nombre)
            ->line('Propiedad: '.$this->visita->propiedad->titulo)
            ->line('Fecha: '.$this->visita->inicio->format('d/m/Y H:i'))
            ->line('Lugar: '.($this->visita->lugar ?: 'A confirmar'))
            ->when(
                $this->paraAsesor,
                fn (MailMessage $mail) => $mail->action(
                    'Abrir visita',
                    route('administracion.visitas.mostrar', $this->visita)
                )
            );
    }
}
