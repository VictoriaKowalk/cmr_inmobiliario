<?php

namespace App\Http\Requests\Administracion;

use App\Enums\CategoriaCaracteristica;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarCaracteristicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->estaActivo() === true;
    }

    public function rules(): array
    {
        $caracteristica = $this->route('caracteristica');

        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('caracteristicas', 'nombre')
                    ->where('categoria', $this->input('categoria'))
                    ->ignore($caracteristica?->id),
            ],
            'categoria' => [
                'required',
                Rule::enum(CategoriaCaracteristica::class),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' => trim((string) $this->input('nombre')),
        ]);
    }
}
