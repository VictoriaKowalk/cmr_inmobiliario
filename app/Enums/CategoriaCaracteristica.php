<?php

namespace App\Enums;

enum CategoriaCaracteristica: string
{
    case SERVICIO = 'servicio';
    case AMBIENTE = 'ambiente';
    case CARTEL = 'cartel';
    case OBSERVACION = 'observacion';
    case PREFERENCIA_LOTE = 'preferencia_lote';
    case AMENITY = 'amenity';
    case ADICIONAL = 'adicional';

    public function etiqueta(): string
    {
        return match ($this) {
            self::SERVICIO => 'Servicios',
            self::AMBIENTE => 'Ambientes',
            self::CARTEL => 'Cartel',
            self::OBSERVACION => 'Observaciones',
            self::PREFERENCIA_LOTE => 'Preferencia de lote',
            self::AMENITY => 'Amenities',
            self::ADICIONAL => 'Adicionales',
        };
    }
}
