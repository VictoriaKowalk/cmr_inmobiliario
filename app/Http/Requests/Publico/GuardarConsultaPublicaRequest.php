<?php

namespace App\Http\Requests\Publico;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarConsultaPublicaRequest extends FormRequest
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
            'mensaje' => ['required', 'string', 'max:5000'],
            'operacion_propiedad_id' => [
                'nullable',
                Rule::exists('operaciones_propiedad', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Ingresá tu nombre.',
            'email.email' => 'Ingresá un correo válido.',
            'email.required_without' => 'Ingresá un correo o un teléfono.',
            'telefono.required_without' => 'Ingresá un teléfono o un correo.',
            'mensaje.required' => 'Ingresá tu consulta.',
            'mensaje.max' => 'La consulta no puede superar los 5000 caracteres.',
            'operacion_propiedad_id.exists' => 'La operación seleccionada no está disponible.',
            'sitio_web.prohibited' => 'No pudimos procesar el formulario.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' => trim((string) $this->input('nombre')),
            'email' => trim((string) $this->input('email')) ?: null,
            'telefono' => trim((string) $this->input('telefono')) ?: null,
            'mensaje' => trim((string) $this->input('mensaje')),
        ]);
    }
}
