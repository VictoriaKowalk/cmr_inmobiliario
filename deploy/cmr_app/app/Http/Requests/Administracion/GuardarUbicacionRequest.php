<?php

namespace App\Http\Requests\Administracion;

use App\Services\ServicioUbicaciones;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarUbicacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ubicacion = $this->route('ubicacion');
        $nombreCompleto = app(ServicioUbicaciones::class)
            ->generarNombreCompleto($this->all());

        return [
            'pais' => ['required', 'string', 'max:100'],
            'zona' => ['nullable', 'string', 'max:150'],
            'localidad' => ['nullable', 'string', 'max:150'],
            'categoria_barrio' => ['nullable', 'string', 'max:150'],
            'barrio_principal' => ['nullable', 'string', 'max:150'],
            'barrio' => ['nullable', 'string', 'max:150'],
            'nombre_completo' => [
                Rule::unique('ubicaciones', 'nombre_completo')
                    ->ignore($ubicacion?->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'pais.required' => 'Ingresá el país.',
            'pais.max' => 'El país no puede superar los 100 caracteres.',
            '*.max' => 'Uno de los campos supera la longitud permitida.',
            'nombre_completo.unique' => 'Esta ubicación ya existe.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $campos = [
            'pais',
            'zona',
            'localidad',
            'categoria_barrio',
            'barrio_principal',
            'barrio',
        ];

        $datos = collect($campos)
            ->mapWithKeys(function (string $campo): array {
                $valor = trim((string) $this->input($campo));

                return [$campo => $valor !== '' ? $valor : null];
            })
            ->all();

        $this->merge([
            ...$datos,
            'nombre_completo' => app(ServicioUbicaciones::class)
                ->generarNombreCompleto($datos),
        ]);
    }
}
