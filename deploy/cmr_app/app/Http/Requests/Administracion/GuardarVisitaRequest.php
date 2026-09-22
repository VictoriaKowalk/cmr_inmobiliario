<?php

namespace App\Http\Requests\Administracion;

use App\Enums\EstadoVisita;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarVisitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'consulta_id' => ['nullable', 'integer', 'exists:consultas,id'],
            'tasacion_id' => ['nullable', 'integer', 'exists:tasaciones,id'],
            'propiedad_id' => ['required', 'integer', 'exists:propiedades,id'],
            'asesor_id' => [
                'required',
                Rule::exists('usuarios', 'id')->where('activo', true),
            ],
            'interesado_nombre' => ['required', 'string', 'max:150'],
            'interesado_email' => ['nullable', 'email', 'max:255', 'required_without:interesado_telefono'],
            'interesado_telefono' => ['nullable', 'string', 'max:50', 'required_without:interesado_email'],
            'inicio' => ['required', 'date'],
            'fin' => ['nullable', 'date', 'after:inicio'],
            'estado' => ['required', Rule::enum(EstadoVisita::class)],
            'lugar' => ['nullable', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
