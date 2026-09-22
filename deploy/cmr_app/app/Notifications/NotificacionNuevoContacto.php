<?php

namespace App\Notifications;

use App\Models\Consulta;
use App\Models\Tasacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificacionNuevoContacto extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 30;

    public function __construct(
        public Consulta|Tasacion $contacto
    ) {
        $this->onQueue('notificaciones');
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $esTasacion = $this->contacto instanceof Tasacion;
        $tipo = $esTasacion
            ? 'Nueva solicitud de tasación'
            : ($this->contacto->propiedad_id
                ? 'Nueva consulta por propiedad'
                : 'Nueva consulta general');
        $ruta = $esTasacion
            ? route('administracion.tasaciones.mostrar', $this->contacto)
            : route('administracion.consultas.mostrar', $this->contacto);

        return (new MailMessage)
            ->subject($tipo.' · '.$this->contacto->nombre)
            ->greeting($tipo)
            ->line('Nombre: '.$this->contacto->nombre)
            ->line('Teléfono: '.($this->contacto->telefono ?: 'No informado'))
            ->line('Correo: '.($this->contacto->email ?: 'No informado'))
            ->action('Abrir en el panel', $ruta)
            ->line('Este mensaje fue generado automáticamente por el sitio.');
    }
}
