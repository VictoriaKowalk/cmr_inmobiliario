<?php

namespace App\Http\Requests\Administracion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ActualizarContraseniaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->estaActivo() === true;
    }

    public function rules(): array
    {
        return [
            'contrasenia_actual' => [
                'required',
                'current_password',
            ],
            'contrasenia' => [
                'required',
                'confirmed',
                Password::min(10)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'contrasenia_actual.required' => 'Ingresá tu contraseña actual.',
            'contrasenia_actual.current_password' => 'La contraseña actual no es correcta.',
            'contrasenia.required' => 'Ingresá la nueva contraseña.',
            'contrasenia.confirmed' => 'La confirmación de la contraseña no coincide.',
            'contrasenia.min' => 'La nueva contraseña debe tener al menos 10 caracteres.',
        ];
    }
}
