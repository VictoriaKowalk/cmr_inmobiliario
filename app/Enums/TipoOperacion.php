<?php

namespace App\Enums;

enum TipoOperacion: string
{
    case VENTA = 'venta';
    case ALQUILER = 'alquiler';
    case ALQUILER_TEMPORAL = 'alquiler_temporal';
}
