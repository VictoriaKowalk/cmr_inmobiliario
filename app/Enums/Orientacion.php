<?php

namespace App\Enums;

enum Orientacion: string
{
    case NORTE = 'norte';
    case SUR = 'sur';
    case ESTE = 'este';
    case OESTE = 'oeste';
    case NORESTE = 'noreste';
    case NOROESTE = 'noroeste';
    case SUDESTE = 'sudeste';
    case SUDOESTE = 'sudoeste';

    public function etiqueta(): string
    {
        return ucfirst($this->value);
    }
}
