<?php

namespace App\Http\Requests\Administracion;

use App\Enums\EstadoOperacion;
use App\Enums\Moneda;
use App\Enums\Orientacion;
use App\Enums\TipoOperacion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class GuardarPropiedadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $propiedad = $this->route('propiedad');
        $tiposOperacion = array_column(TipoOperacion::cases(), 'value');
        $estados = array_column(EstadoOperacion::cases(), 'value');
        $monedas = array_column(Moneda::cases(), 'value');

        return [
            'tipo_propiedad_id' => [
                'required',
                Rule::exists('tipos_propiedad', 'id')->where(
                    fn ($consulta) => $consulta
                        ->where('activo', true)
                        ->when(
                            $propiedad,
                            fn ($subconsulta) => $subconsulta
                                ->orWhere('id', $propiedad->tipo_propiedad_id)
                        )
                ),
            ],
            'ubicacion_id' => [
                'required',
                Rule::exists('ubicaciones', 'id')->where(
                    fn ($consulta) => $consulta
                        ->where('activa', true)
                        ->when(
                            $propiedad,
                            fn ($subconsulta) => $subconsulta
                                ->orWhere('id', $propiedad->ubicacion_id)
                        )
                ),
            ],
            'titulo' => ['required', 'string', 'max:180'],
            'codigo_interno' => [
                'required',
                'string',
                'max:50',
                Rule::unique('propiedades', 'codigo_interno')
                    ->ignore($propiedad?->id),
            ],
            'expensas' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
            'expensas_moneda' => ['nullable', Rule::in($monedas)],
            'descripcion_corta' => ['nullable', 'string', 'max:500'],
            'descripcion' => ['nullable', 'string'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'direccion_normalizada' => ['nullable', 'string', 'max:255'],
            'mostrar_direccion' => ['nullable', 'boolean'],
            'ambientes' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'dormitorios' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'banios' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'cocheras' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'superficie_total' => ['nullable', 'numeric', 'min:0'],
            'superficie_cubierta' => ['nullable', 'numeric', 'min:0'],
            'superficie_descubierta' => ['nullable', 'numeric', 'min:0'],
            'superficie_terreno' => ['nullable', 'numeric', 'min:0'],
            'antiguedad' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'orientacion' => [
                'nullable',
                Rule::enum(Orientacion::class),
            ],
            'caracteristicas' => ['nullable', 'array'],
            'caracteristicas.*' => [
                'integer',
                'distinct',
                Rule::exists('caracteristicas', 'id')->where('activa', true),
            ],
            'cartel_caracteristica_id' => [
                'nullable',
                'integer',
                Rule::exists('caracteristicas', 'id')
                    ->where('activa', true)
                    ->where('categoria', 'cartel'),
            ],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
            'proveedor_geocodificacion' => ['nullable', 'string', 'max:50'],
            'place_id' => ['nullable', 'string', 'max:255'],
            'ubicacion_confirmada' => ['nullable', 'boolean'],
            'operaciones' => ['required', 'array', 'min:1'],
            'operaciones.*.activa' => ['nullable', 'boolean'],
            'operaciones.*.tipo_operacion' => [
                'required',
                Rule::in($tiposOperacion),
            ],
            'operaciones.*.moneda' => ['nullable', Rule::in($monedas)],
            'operaciones.*.precio' => ['nullable', 'numeric', 'min:0'],
            'operaciones.*.estado' => ['required', Rule::in($estados)],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_propiedad_id.required' => 'Seleccioná un tipo de propiedad.',
            'tipo_propiedad_id.exists' => 'El tipo de propiedad seleccionado no está disponible.',
            'ubicacion_id.required' => 'Seleccioná una ubicación desde el buscador.',
            'ubicacion_id.exists' => 'La ubicación seleccionada no está disponible.',
            'titulo.required' => 'Ingresá el título de la propiedad.',
            'codigo_interno.required' => 'Ingresá el código interno.',
            'codigo_interno.unique' => 'Ya existe una propiedad con ese código interno.',
            'operaciones.required' => 'Seleccioná al menos una operación.',
            'operaciones.min' => 'Seleccioná al menos una operación.',
            '*.numeric' => 'Ingresá un valor numérico válido.',
            '*.min' => 'Los valores no pueden ser negativos.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validador): void {
                $operacionesActivas = collect($this->input('operaciones', []))
                    ->filter(fn (array $operacion) => (bool) ($operacion['activa'] ?? false));

                if ($operacionesActivas->isEmpty()) {
                    $validador->errors()->add(
                        'operaciones',
                        'Seleccioná al menos una operación.'
                    );
                }

                $tiposRepetidos = $operacionesActivas
                    ->pluck('tipo_operacion')
                    ->filter()
                    ->duplicates()
                    ->unique();

                if ($tiposRepetidos->isNotEmpty()) {
                    $validador->errors()->add(
                        'operaciones',
                        'No se puede repetir un mismo tipo de operación.'
                    );
                }

                if ($this->filled('latitud') xor $this->filled('longitud')) {
                    $validador->errors()->add(
                        'latitud',
                        'Para geolocalizar la propiedad necesitás latitud y longitud.'
                    );
                }

                if ($this->filled('expensas') && ! $this->filled('expensas_moneda')) {
                    $validador->errors()->add(
                        'expensas_moneda',
                        'Seleccioná la moneda de las expensas.'
                    );
                }

                if ($this->filled('expensas_moneda') && ! $this->filled('expensas')) {
                    $validador->errors()->add(
                        'expensas',
                        'Ingresá el importe de las expensas o dejá la moneda vacía.'
                    );
                }

                foreach ($operacionesActivas as $indice => $operacion) {
                    $tipo = $operacion['tipo_operacion'] ?? null;
                    $estado = $operacion['estado'] ?? null;
                    $precio = $operacion['precio'] ?? null;
                    $moneda = $operacion['moneda'] ?? null;

                    if ($precio !== null && $precio !== '' && empty($moneda)) {
                        $validador->errors()->add(
                            "operaciones.{$indice}.moneda",
                            'Seleccioná la moneda de la operación.'
                        );
                    }

                    if (! empty($moneda) && ($precio === null || $precio === '')) {
                        $validador->errors()->add(
                            "operaciones.{$indice}.precio",
                            'Ingresá un precio o dejá la moneda vacía para mostrar Consultar.'
                        );
                    }

                    if ($tipo === TipoOperacion::VENTA->value
                        && $estado === EstadoOperacion::ALQUILADA->value) {
                        $validador->errors()->add(
                            "operaciones.{$indice}.estado",
                            'Una operación de venta no puede marcarse como alquilada.'
                        );
                    }

                    if (in_array($tipo, [
                        TipoOperacion::ALQUILER->value,
                        TipoOperacion::ALQUILER_TEMPORAL->value,
                    ], true) && $estado === EstadoOperacion::VENDIDA->value) {
                        $validador->errors()->add(
                            "operaciones.{$indice}.estado",
                            'Una operación de alquiler no puede marcarse como vendida.'
                        );
                    }
                }
            },
        ];
    }

    protected function prepareForValidation(): void
    {
        $camposTexto = [
            'titulo',
            'codigo_interno',
            'descripcion_corta',
            'descripcion',
            'direccion',
            'direccion_normalizada',
            'proveedor_geocodificacion',
            'place_id',
            'expensas_moneda',
            'orientacion',
        ];

        $datos = collect($camposTexto)
            ->mapWithKeys(function (string $campo): array {
                $valor = trim((string) $this->input($campo));

                return [$campo => $valor !== '' ? $valor : null];
            })
            ->all();

        $this->merge($datos);
    }
}
