<?php

namespace App\Enums;

enum EstadoVisita: string
{
    case PENDIENTE = 'pendiente';
    case CONFIRMADA = 'confirmada';
    case REALIZADA = 'realizada';
    case CANCELADA = 'cancelada';
    case REPROGRAMADA = 'reprogramada';
    case AUSENTE = 'ausente';

    public function etiqueta(): string
    {
        return match ($this) {
            self::PENDIENTE => 'Pendiente de confirmación',
            self::CONFIRMADA => 'Confirmada',
            self::REALIZADA => 'Realizada',
            self::CANCELADA => 'Cancelada',
            self::REPROGRAMADA => 'Reprogramada',
            self::AUSENTE => 'Cliente ausente',
        };
    }
}
