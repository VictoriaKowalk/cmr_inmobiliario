<?php

namespace App\Http\Requests\Administracion;

use App\Enums\EstadoSeguimiento;
use App\Enums\PrioridadOportunidad;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActualizarSeguimientoContactoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'estado_seguimiento' => [
                'required',
                Rule::in(array_column(EstadoSeguimiento::cases(), 'value')),
            ],
            'notas_internas' => ['nullable', 'string', 'max:5000'],
            'responsable_id' => [
                'nullable',
                Rule::exists('usuarios', 'id')->where('activo', true),
            ],
            'prioridad' => [
                'required',
                Rule::in(array_column(PrioridadOportunidad::cases(), 'value')),
            ],
            'proxima_tarea' => ['nullable', 'string', 'max:255'],
            'proxima_tarea_en' => ['nullable', 'date'],
            'motivo_cierre' => [
                Rule::requiredIf(fn () => in_array($this->input('estado_seguimiento'), [
                    EstadoSeguimiento::PERDIDA->value,
                    EstadoSeguimiento::CERRADA->value,
                ], true)),
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'estado_seguimiento.required' => 'Seleccioná un estado de seguimiento.',
            'estado_seguimiento.in' => 'El estado de seguimiento seleccionado no es válido.',
            'notas_internas.max' => 'Las notas internas no pueden superar los 5000 caracteres.',
            'responsable_id.exists' => 'El asesor seleccionado no está disponible.',
            'prioridad.required' => 'Seleccioná una prioridad.',
            'proxima_tarea_en.date' => 'Ingresá una fecha válida para la próxima tarea.',
            'motivo_cierre.required' => 'Indicá el motivo de cierre o pérdida.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'notas_internas' => trim((string) $this->input('notas_internas')),
            'proxima_tarea' => trim((string) $this->input('proxima_tarea')) ?: null,
            'motivo_cierre' => trim((string) $this->input('motivo_cierre')) ?: null,
        ]);
    }
}
