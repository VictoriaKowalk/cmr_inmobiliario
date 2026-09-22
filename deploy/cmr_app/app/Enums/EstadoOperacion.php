<?php

namespace App\Enums;

enum EstadoOperacion: string
{
    case PUBLICADA = 'publicada';
    case PAUSADA = 'pausada';
    case VENDIDA = 'vendida';
    case ALQUILADA = 'alquilada';
}
