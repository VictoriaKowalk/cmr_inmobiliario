<?php

namespace App\Enums;

enum ResultadoVisita: string
{
    case INTERESADO = 'interesado';
    case SEGUIMIENTO = 'seguimiento';
    case NO_INTERESADO = 'no_interesado';
    case PROPUESTA = 'propuesta';

    public function etiqueta(): string
    {
        return match ($this) {
            self::INTERESADO => 'Interesado',
            self::SEGUIMIENTO => 'Requiere seguimiento',
            self::NO_INTERESADO => 'No interesado',
            self::PROPUESTA => 'Propuesta realizada',
        };
    }
}
