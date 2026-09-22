<?php

namespace App\Http\Requests\Administracion;

use App\Models\Ubicacion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class GuardarUbicacionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $edicion = $this->route('ubicacion') !== null;

        return [
            'nombre' => ['required', 'string', 'max:150'],
            'ubicacion_padre_id' => [Rule::requiredIf(! $edicion), 'nullable', 'integer', Rule::exists('ubicaciones', 'id')->where('activa', true)],
            'tipo_ubicacion_id' => [Rule::requiredIf(! $edicion), 'nullable', 'integer', Rule::exists('tipos_ubicacion', 'id')->where('activo', true)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['nombre' => trim((string) $this->input('nombre'))]);
    }

    public function after(): array
    {
        return [function (Validator $validador): void {
            if ($this->route('ubicacion') || $validador->errors()->isNotEmpty()) return;
            $existe = Ubicacion::query()
                ->where('ubicacion_padre_id', $this->integer('ubicacion_padre_id'))
                ->where('tipo_ubicacion_id', $this->integer('tipo_ubicacion_id'))
                ->where('nombre_normalizado', Str::lower(Str::ascii($this->input('nombre'))))
                ->exists();
            if ($existe) $validador->errors()->add('nombre', 'Ya existe una ubicación igual en este nivel.');
        }];
    }
}
