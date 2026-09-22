<?php

namespace App\Http\Requests\Administracion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class ActualizarEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->estaActivo() === true;
    }

    public function rules(): array
    {
        return [
            'nombre_comercial' => ['required', 'string', 'max:150'],
            'razon_social' => ['nullable', 'string', 'max:180'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'zona_horaria' => ['required', 'timezone'],
            'logo' => ['nullable', File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(5 * 1024)],
            'eliminar_logo' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $campos = [
            'nombre_comercial', 'razon_social', 'email', 'telefono',
            'whatsapp', 'direccion', 'zona_horaria',
        ];

        $this->merge(collect($campos)->mapWithKeys(function (string $campo): array {
            $valor = trim((string) $this->input($campo));

            return [$campo => $valor !== '' ? $valor : null];
        })->all());
    }
}
