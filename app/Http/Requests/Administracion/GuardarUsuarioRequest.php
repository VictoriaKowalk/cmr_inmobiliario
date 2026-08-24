<?php

namespace App\Http\Requests\Administracion;

use App\Enums\RolUsuario;
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
            'rol' => ['required', Rule::enum(RolUsuario::class)],
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

    public function messages(): array
    {
        return [
            'nombre.required' => 'Ingresá el nombre del usuario.',
            'email.required' => 'Ingresá el correo electrónico.',
            'email.email' => 'Ingresá un correo electrónico válido.',
            'email.unique' => 'Ya existe un usuario con ese correo electrónico.',
            'rol.required' => 'Seleccioná un rol para el usuario.',
            'rol.enum' => 'El rol seleccionado no es válido.',
            'dni.regex' => 'El DNI solo puede contener números, puntos y guiones.',
            'dni.unique' => 'Ya existe un usuario con ese DNI.',
            'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser futura.',
            'contrasenia.required' => 'Ingresá una contraseña inicial.',
            'contrasenia.confirmed' => 'La confirmación de la contraseña no coincide.',
            'contrasenia.min' => 'La contraseña debe tener al menos 10 caracteres e incluir mayúsculas, minúsculas y números.',
            'contrasenia.letters' => 'La contraseña debe tener al menos 10 caracteres e incluir mayúsculas, minúsculas y números.',
            'contrasenia.mixed' => 'La contraseña debe tener al menos 10 caracteres e incluir mayúsculas, minúsculas y números.',
            'contrasenia.numbers' => 'La contraseña debe tener al menos 10 caracteres e incluir mayúsculas, minúsculas y números.',
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
