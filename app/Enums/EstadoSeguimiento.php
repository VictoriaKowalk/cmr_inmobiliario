<?php

namespace App\Enums;

enum EstadoSeguimiento: string
{
    case NUEVA = 'nueva';
    case EN_SEGUIMIENTO = 'en_seguimiento';
    case CONTACTADA = 'contactada';
    case VISITA_COORDINADA = 'visita_coordinada';
    case NEGOCIACION = 'negociacion';
    case GANADA = 'ganada';
    case PERDIDA = 'perdida';
    case CERRADA = 'cerrada';

    public function etiqueta(): string
    {
        return match ($this) {
            self::NUEVA => 'Nueva',
            self::EN_SEGUIMIENTO => 'En seguimiento',
            self::CONTACTADA => 'Contactada',
            self::VISITA_COORDINADA => 'Visita coordinada',
            self::NEGOCIACION => 'Negociación',
            self::GANADA => 'Ganada',
            self::PERDIDA => 'Perdida',
            self::CERRADA => 'Cerrada',
        };
    }

    public function clasesBadge(): string
    {
        return match ($this) {
            self::NUEVA => 'bg-sky-50 text-sky-800 ring-sky-100',
            self::EN_SEGUIMIENTO => 'bg-amber-50 text-amber-800 ring-amber-100',
            self::CONTACTADA => 'bg-emerald-50 text-emerald-800 ring-emerald-100',
            self::VISITA_COORDINADA => 'bg-violet-50 text-violet-800 ring-violet-100',
            self::NEGOCIACION => 'bg-orange-50 text-orange-800 ring-orange-100',
            self::GANADA => 'bg-green-100 text-green-900 ring-green-200',
            self::PERDIDA => 'bg-red-50 text-red-800 ring-red-100',
            self::CERRADA => 'bg-neutral-100 text-neutral-700 ring-neutral-200',
        };
    }

    public function requiereMotivoCierre(): bool
    {
        return in_array($this, [self::PERDIDA, self::CERRADA], true);
    }
}
