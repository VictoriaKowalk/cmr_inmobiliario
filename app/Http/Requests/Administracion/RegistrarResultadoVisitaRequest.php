<?php

namespace App\Http\Requests\Administracion;

use App\Enums\ResultadoVisita;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrarResultadoVisitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'resultado' => ['required', Rule::enum(ResultadoVisita::class)],
            'comentarios_resultado' => ['nullable', 'string', 'max:5000'],
            'proxima_accion' => ['nullable', 'string', 'max:255'],
            'proxima_accion_en' => ['nullable', 'date'],
        ];
    }
}
