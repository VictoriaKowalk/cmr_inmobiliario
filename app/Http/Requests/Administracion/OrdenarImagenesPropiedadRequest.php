<?php

namespace App\Http\Requests\Administracion;

use Illuminate\Foundation\Http\FormRequest;

class OrdenarImagenesPropiedadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ordenes' => ['required', 'array', 'min:1'],
            'ordenes.*' => ['required', 'integer', 'min:0', 'max:65535'],
        ];
    }

    public function messages(): array
    {
        return [
            'ordenes.required' => 'No se recibieron imágenes para ordenar.',
            'ordenes.*.integer' => 'El orden debe ser un número entero.',
            'ordenes.*.min' => 'El orden no puede ser negativo.',
        ];
    }
}
