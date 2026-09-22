<?php

namespace App\Enums;

enum PrioridadOportunidad: string
{
    case BAJA = 'baja';
    case MEDIA = 'media';
    case ALTA = 'alta';
    case URGENTE = 'urgente';

    public function etiqueta(): string
    {
        return ucfirst($this->value);
    }

    public function clasesBadge(): string
    {
        return match ($this) {
            self::BAJA => 'bg-neutral-100 text-neutral-700',
            self::MEDIA => 'bg-sky-50 text-sky-800',
            self::ALTA => 'bg-amber-50 text-amber-800',
            self::URGENTE => 'bg-red-50 text-red-800',
        };
    }
}
