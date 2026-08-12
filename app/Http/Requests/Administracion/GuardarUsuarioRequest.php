<?php

namespace App\Http\Requests\Administracion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class GuardarUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->estaActivo() === true;
    }

    public function rules(): array
    {
        $usuario = $this->route('usuario');

        return [
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['nullable', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios', 'email')->ignore($usuario?->id),
            ],
            'telefono' => ['nullable', 'string', 'max:50'],
            'celular' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'dni' => ['nullable', 'string', 'max:20', 'regex:/^[0-9.\-]+$/', Rule::unique('usuarios', 'dni')->ignore($usuario?->id)],
            'fecha_nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
            'contrasenia' => [
                $usuario ? 'nullable' : 'required',
                'confirmed',
                Password::min(10)->letters()->mixedCase()->numbers(),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $datos = [
            'nombre' => trim((string) $this->input('nombre')),
            'apellido' => trim((string) $this->input('apellido')) ?: null,
            'email' => mb_strtolower(trim((string) $this->input('email'))),
        ];

        foreach (['telefono', 'celular', 'direccion', 'dni'] as $campo) {
            $datos[$campo] = trim((string) $this->input($campo)) ?: null;
        }

        $this->merge($datos);
    }
}
