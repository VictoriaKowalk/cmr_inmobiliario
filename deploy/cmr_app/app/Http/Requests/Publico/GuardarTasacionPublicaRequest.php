<?php

namespace App\Http\Requests\Publico;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarTasacionPublicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sitio_web' => ['nullable', 'prohibited'],
            'nombre' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:255', 'required_without:telefono'],
            'telefono' => ['nullable', 'string', 'max:50', 'required_without:email'],
            'tipo_propiedad_id' => [
                'nullable',
                Rule::exists('tipos_propiedad', 'id')->where('activo', true),
            ],
            'ubicacion_texto' => ['required', 'string', 'max:500'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'mensaje' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Ingresá tu nombre.',
            'email.email' => 'Ingresá un correo válido.',
            'email.required_without' => 'Ingresá un correo o un teléfono.',
            'telefono.required_without' => 'Ingresá un teléfono o un correo.',
            'tipo_propiedad_id.exists' => 'El tipo de propiedad seleccionado no está disponible.',
            'ubicacion_texto.required' => 'Ingresá la ubicación de la propiedad.',
            'ubicacion_texto.max' => 'La ubicación no puede superar los 500 caracteres.',
            'direccion.max' => 'La dirección no puede superar los 255 caracteres.',
            'mensaje.max' => 'El mensaje no puede superar los 5000 caracteres.',
            'sitio_web.prohibited' => 'No pudimos procesar el formulario.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' => trim((string) $this->input('nombre')),
            'email' => trim((string) $this->input('email')) ?: null,
            'telefono' => trim((string) $this->input('telefono')) ?: null,
            'ubicacion_texto' => trim((string) $this->input('ubicacion_texto')),
            'direccion' => trim((string) $this->input('direccion')) ?: null,
            'mensaje' => trim((string) $this->input('mensaje')) ?: null,
        ]);
    }
}
