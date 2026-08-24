<?php

namespace App\Enums;

enum RolUsuario: string
{
    case ADMINISTRADOR = 'administrador';
    case SUPERVISOR = 'supervisor';
    case ASESOR = 'asesor';

    public function etiqueta(): string
    {
        return match ($this) {
            self::ADMINISTRADOR => 'Administrador',
            self::SUPERVISOR => 'Supervisor',
            self::ASESOR => 'Asesor',
        };
    }
}
