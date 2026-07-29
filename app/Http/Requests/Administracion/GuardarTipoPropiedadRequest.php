<?php

namespace App\Http\Requests\Administracion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarTipoPropiedadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipoPropiedad = $this->route('tipoPropiedad');

        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tipos_propiedad', 'nombre')
                    ->ignore($tipoPropiedad?->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Ingresá el nombre del tipo de propiedad.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'nombre.unique' => 'Ya existe un tipo de propiedad con ese nombre.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' => trim((string) $this->input('nombre')),
        ]);
    }
}
