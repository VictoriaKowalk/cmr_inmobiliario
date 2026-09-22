<?php

namespace App\Http\Requests\Administracion;

use App\Enums\EstadoOperacion;
use App\Enums\Moneda;
use App\Enums\TipoOperacion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ActualizarOperacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->estaActivo() === true;
    }

    public function rules(): array
    {
        return [
            'moneda' => ['nullable', Rule::enum(Moneda::class)],
            'precio' => ['nullable', 'numeric', 'min:0'],
            'estado' => ['required', Rule::enum(EstadoOperacion::class)],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validador): void {
                $operacion = $this->route('operacion');
                $estado = $this->input('estado');

                if ($this->filled('precio') && ! $this->filled('moneda')) {
                    $validador->errors()->add('moneda', 'Seleccioná la moneda.');
                }

                if ($this->filled('moneda') && ! $this->filled('precio')) {
                    $validador->errors()->add(
                        'precio',
                        'Ingresá el precio o dejá la moneda vacía para mostrar Consultar.'
                    );
                }

                if ($operacion->tipo_operacion === TipoOperacion::VENTA
                    && $estado === EstadoOperacion::ALQUILADA->value) {
                    $validador->errors()->add(
                        'estado',
                        'Una venta no puede marcarse como alquilada.'
                    );
                }

                if ($operacion->tipo_operacion !== TipoOperacion::VENTA
                    && $estado === EstadoOperacion::VENDIDA->value) {
                    $validador->errors()->add(
                        'estado',
                        'Un alquiler no puede marcarse como vendido.'
                    );
                }
            },
        ];
    }
}
