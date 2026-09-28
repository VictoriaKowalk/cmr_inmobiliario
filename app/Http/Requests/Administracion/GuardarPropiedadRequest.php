<?php

namespace App\Http\Requests\Administracion;

use App\Enums\EstadoOperacion;
use App\Enums\Moneda;
use App\Enums\Orientacion;
use App\Enums\TipoOperacion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use App\Services\ServicioCoberturaUbicaciones;

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
            'imagenes' => [
                Rule::excludeIf((bool) $propiedad),
                'nullable',
                'array',
                'max:20',
            ],
            'imagenes.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
                'dimensions:max_width=8000,max_height=8000',
            ],
            'video_titulo' => [
                Rule::excludeIf((bool) $propiedad),
                'nullable',
                'string',
                'max:150',
            ],
            'youtube_url' => [
                Rule::excludeIf((bool) $propiedad),
                'nullable',
                'url',
                'max:500',
                function (string $atributo, mixed $valor, \Closure $fallar): void {
                    if ($valor && ! $this->esUrlYoutubeValida((string) $valor)) {
                        $fallar('Ingresá una URL válida de YouTube.');
                    }
                },
            ],
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
            'operaciones.required' => 'Seleccioná al menos una operación.',
            'operaciones.min' => 'Seleccioná al menos una operación.',
            'imagenes.max' => 'Podés subir hasta 20 imágenes.',
            'imagenes.*.image' => 'Uno de los archivos no es una imagen válida.',
            'imagenes.*.mimes' => 'Las imágenes deben ser JPG, PNG o WebP.',
            'imagenes.*.max' => 'Cada imagen puede pesar hasta 10 MB.',
            'imagenes.*.dimensions' => 'Cada imagen puede medir como máximo 8000 × 8000 píxeles.',
            'youtube_url.url' => 'Ingresá una URL válida de YouTube.',
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
            'descripcion_corta',
            'descripcion',
            'direccion',
            'direccion_normalizada',
            'proveedor_geocodificacion',
            'place_id',
            'expensas_moneda',
            'orientacion',
            'video_titulo',
            'youtube_url',
        ];

        $datos = collect($camposTexto)
            ->mapWithKeys(function (string $campo): array {
                $valor = trim((string) $this->input($campo));

                return [$campo => $valor !== '' ? $valor : null];
            })
            ->all();

        $operaciones = collect($this->input('operaciones', []))
            ->map(function (mixed $operacion): mixed {
                if (! is_array($operacion)) {
                    return $operacion;
                }

                $precio = trim((string) ($operacion['precio'] ?? ''));

                if ($precio !== '') {
                    $operacion['precio'] = str_replace(
                        ['.', ','],
                        ['', '.'],
                        $precio
                    );
                }

                return $operacion;
            })
            ->all();

        $expensas = trim((string) $this->input('expensas'));

        if ($expensas !== '') {
            $expensas = str_replace(['.', ','], ['', '.'], $expensas);
        } else {
            $expensas = null;
        }

        $this->merge([
            ...$datos,
            'expensas' => $expensas,
            'operaciones' => $operaciones,
        ]);
    }

    public function withValidator(Validator $validador): void
    {
        $validador->after(function (Validator $validador): void {
            if ($validador->errors()->has('ubicacion_id') || ! $this->filled('ubicacion_id')) {
                return;
            }

            $propiedad = $this->route('propiedad');
            $ubicacionId = $this->integer('ubicacion_id');

            if ($propiedad && $propiedad->ubicacion_id === $ubicacionId) {
                return;
            }

            if (! app(ServicioCoberturaUbicaciones::class)->incluye($ubicacionId)) {
                $validador->errors()->add(
                    'ubicacion_id',
                    'La ubicación seleccionada está fuera de tus zonas de trabajo.'
                );
            }
        });
    }

    private function esUrlYoutubeValida(string $url): bool
    {
        $partes = parse_url($url);

        if (! $partes || empty($partes['host'])) {
            return false;
        }

        $host = strtolower($partes['host']);
        $path = trim($partes['path'] ?? '', '/');

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            return $path !== '';
        }

        if (! in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            return false;
        }

        parse_str($partes['query'] ?? '', $query);

        return ! empty($query['v'])
            || str_starts_with($path, 'embed/')
            || str_starts_with($path, 'shorts/');
    }
}
