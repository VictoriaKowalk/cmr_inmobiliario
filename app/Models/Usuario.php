<?php

namespace App\Models;

use App\Enums\RolUsuario;
use Database\Factories\UsuarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    /** @use HasFactory<UsuarioFactory> */
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'celular',
        'direccion',
        'dni',
        'fecha_nacimiento',
        'contrasenia',
        'rol',
        'activo',
        'ultimo_acceso_en',
    ];

    protected $hidden = [
        'contrasenia',
        'recordar_token',
    ];

    protected function casts(): array
    {
        return [
            'contrasenia' => 'hashed',
            'rol' => RolUsuario::class,
            'activo' => 'boolean',
            'ultimo_acceso_en' => 'datetime',
            'fecha_nacimiento' => 'date',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'contrasenia';
    }

    public function getRememberTokenName(): string
    {
        return 'recordar_token';
    }

    public function estaActivo(): bool
    {
        return $this->activo;
    }

    public function esAdministrador(): bool
    {
        return $this->rol === RolUsuario::ADMINISTRADOR;
    }

    public function esSupervisor(): bool
    {
        return $this->rol === RolUsuario::SUPERVISOR;
    }

    public function esAsesor(): bool
    {
        return $this->rol === RolUsuario::ASESOR;
    }

    public function puedeSupervisar(): bool
    {
        return $this->esAdministrador() || $this->esSupervisor();
    }

    public function nombreCompleto(): string
    {
        return trim($this->nombre.' '.$this->apellido);
    }
}
