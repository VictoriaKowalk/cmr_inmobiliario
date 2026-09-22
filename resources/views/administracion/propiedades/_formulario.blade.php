@php
    $propiedad = $propiedad ?? null;
    $operacionesGuardadas = $propiedad?->operaciones?->keyBy(
        fn ($operacion) => $operacion->tipo_operacion->value
    ) ?? collect();
    $ubicacionTexto = old(
        'ubicacion_texto',
        $propiedad?->ubicacion?->nombre_completo ?? ''
    );
    $ubicacionSeleccionada = old('ubicacion_id', $propiedad?->ubicacion_id);
    $caracteristicasSeleccionadas = collect(old(
        'caracteristicas',
        $propiedad?->caracteristicas
            ?->reject(
                fn ($caracteristica) => $caracteristica->categoria
                    === \App\Enums\CategoriaCaracteristica::CARTEL
            )
            ->pluck('id')
            ->all() ?? []
    ))->map(fn ($id) => (int) $id);
    $cartelSeleccionado = (int) old(
        'cartel_caracteristica_id',
        $propiedad?->caracteristicas
            ?->firstWhere('categoria', \App\Enums\CategoriaCaracteristica::CARTEL)
            ?->id ?? 0
    );
@endphp

@if ($errors->any())
    <div class="mb-6 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">
        <p class="font-semibold">Revisá los datos del formulario.</p>
        <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<section class="border border-neutral-200 bg-white p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Datos principales</h2>
    <div class="mt-5 grid gap-5 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-medium">Código interno</label>
            <div class="flex h-11 items-center border border-neutral-200 bg-neutral-100 px-3 font-semibold text-neutral-700">
                {{ $propiedad?->codigo_interno ?? 'Se asignará automáticamente al guardar' }}
            </div>
        </div>
        <div>
            <label for="tipo_propiedad_id" class="mb-2 block text-sm font-medium">Tipo de propiedad</label>
            <select id="tipo_propiedad_id" name="tipo_propiedad_id" required
                    class="h-11 w-full border border-neutral-300 bg-white px-3">
                <option value="">Seleccionar</option>
                @foreach ($tiposPropiedad as $tipoPropiedad)
                    <option value="{{ $tipoPropiedad->id }}"
                        @selected((string) old('tipo_propiedad_id', $propiedad?->tipo_propiedad_id) === (string) $tipoPropiedad->id)>
                        {{ $tipoPropiedad->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-2">
            <label for="titulo" class="mb-2 block text-sm font-medium">Título</label>
            <input id="titulo" name="titulo"
                   value="{{ old('titulo', $propiedad?->titulo) }}"
                   maxlength="180" required
                   class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
        </div>
        <div class="relative z-[1000] sm:col-span-2"
             data-autocomplete-ubicacion
             data-url="{{ route('administracion.ubicaciones.buscar') }}"
             data-url-crear="{{ route('administracion.ubicaciones.crear') }}">
            <div class="mb-3 flex flex-wrap items-end justify-between gap-3 border-b border-neutral-200 pb-3">
                <div>
                    <h3 class="font-semibold">Ubicación comercial</h3>
                    <p class="mt-1 text-sm text-neutral-600">Es la zona con la que se buscará y publicará la propiedad.</p>
                </div>
                <div class="text-right text-sm">
                    <span class="block text-neutral-500">País</span>
                    <span class="font-medium">Argentina</span>
                </div>
            </div>
            <label for="ubicacion_texto" class="mb-2 block text-sm font-medium">Búsqueda rápida de barrio o ubicación</label>
            <input id="ubicacion_texto" name="ubicacion_texto"
                   value="{{ $ubicacionTexto }}"
                   autocomplete="off" required data-autocomplete-entrada
                   placeholder="Ej.: Nordelta, Belgrano, Benavídez o Zona Norte"
                   class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
            <input type="hidden" name="ubicacion_id"
                   value="{{ $ubicacionSeleccionada }}"
                   data-autocomplete-id>
            <div data-autocomplete-resultados
                 class="absolute z-20 mt-1 hidden max-h-64 w-full overflow-y-auto border border-neutral-200 bg-white shadow-lg">
            </div>
            <div data-autocomplete-seleccion
                 @class([
                    'mt-3 border-l-4 border-emerald-600 bg-emerald-50 px-3 py-2 text-sm',
                    'hidden' => ! $ubicacionSeleccionada,
                 ])>
                <p class="font-medium text-emerald-900">Ubicación elegida</p>
                <p class="mt-1 text-emerald-800" data-autocomplete-ruta>{{ $ubicacionTexto }}</p>
            </div>
            <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm">
                <p class="text-neutral-600" data-autocomplete-ayuda>
                    Buscá por barrio, subbarrio, localidad o zona y elegí la coincidencia correcta.
                </p>
                <a data-autocomplete-nueva-ubicacion
                   class="hidden font-medium text-emerald-700 hover:text-emerald-900 hover:underline">
                </a>
            </div>
        </div>
        <div class="sm:col-span-2"
             data-geocodificacion
             data-mapas-proveedor="{{ config('services.mapas.proveedor') }}"
             data-google-maps-key="{{ config('services.google_maps.key') }}"
             data-latitud-inicial="{{ old('latitud', $propiedad?->latitud) }}"
             data-longitud-inicial="{{ old('longitud', $propiedad?->longitud) }}">
            <div class="mb-3 border-b border-neutral-200 pb-3">
                <h3 class="font-semibold">Dirección y mapa</h3>
                <p class="mt-1 text-sm text-neutral-600">La dirección real y el pin son independientes de la ubicación comercial.</p>
            </div>
            <label for="direccion" class="mb-2 block text-sm font-medium">Dirección real</label>
            <div class="flex flex-col gap-3 lg:flex-row">
                <input id="direccion" name="direccion"
                       value="{{ old('direccion', $propiedad?->direccion) }}"
                       maxlength="255"
                       autocomplete="off"
                       data-direccion-geocodificacion
                       placeholder="Ej.: Avenida de los Lagos 120"
                       class="h-11 min-w-0 flex-1 border border-neutral-300 px-3">
                <button type="button"
                        data-confirmar-geocodificacion
                        class="inline-flex h-11 items-center justify-center bg-neutral-950 px-4 text-sm font-semibold text-white hover:bg-neutral-800">
                    Buscar coordenadas
                </button>
            </div>
            <input type="hidden" name="direccion_normalizada"
                   value="{{ old('direccion_normalizada', $propiedad?->direccion_normalizada) }}"
                   data-direccion-normalizada>
            <input type="hidden" name="proveedor_geocodificacion"
                   value="{{ old('proveedor_geocodificacion', $propiedad?->proveedor_geocodificacion) }}"
                   data-proveedor-geocodificacion>
            <input type="hidden" name="place_id"
                   value="{{ old('place_id', $propiedad?->place_id) }}"
                   data-place-id>
            <input type="hidden" name="ubicacion_confirmada"
                   value="{{ old('ubicacion_confirmada', $propiedad?->ubicacion_confirmada) ? '1' : '0' }}"
                   data-ubicacion-confirmada>
            <p class="mt-2 text-sm text-neutral-600" data-estado-geocodificacion>
                @if (old('latitud', $propiedad?->latitud) && old('longitud', $propiedad?->longitud))
                    Coordenadas cargadas. Podés ajustar el pin si hace falta.
                @else
                    Buscá la dirección o hacé click en el mapa para ubicar el pin.
                @endif
            </p>
            <div class="mt-4 overflow-hidden border border-neutral-200 bg-neutral-100">
                <div class="flex min-h-[280px] items-center justify-center text-center text-sm text-neutral-500"
                     data-mapa-geocodificacion>
                    Cargando mapa...
                </div>
            </div>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="latitud" class="mb-2 block text-sm font-medium">Latitud</label>
                    <input id="latitud" name="latitud" type="number" step="0.0000001"
                           value="{{ old('latitud', $propiedad?->latitud) }}"
                           data-latitud-geocodificacion
                           class="h-11 w-full border border-neutral-300 px-3">
                </div>
                <div>
                    <label for="longitud" class="mb-2 block text-sm font-medium">Longitud</label>
                    <input id="longitud" name="longitud" type="number" step="0.0000001"
                           value="{{ old('longitud', $propiedad?->longitud) }}"
                           data-longitud-geocodificacion
                           class="h-11 w-full border border-neutral-300 px-3">
                </div>
            </div>
            <label class="mt-3 flex items-center gap-2 text-sm">
                <input type="checkbox" name="mostrar_direccion" value="1"
                       @checked(old('mostrar_direccion', $propiedad?->mostrar_direccion))>
                Mostrar esta dirección en la web pública
            </label>
        </div>
    </div>
</section>

<section class="border border-neutral-200 bg-white p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Operaciones</h2>
    <p class="mt-1 text-sm text-neutral-600">Activá una o varias opciones comerciales.</p>
    <div class="mt-5 grid gap-4 xl:grid-cols-3">
        @foreach ($tiposOperacion as $indice => $tipoOperacion)
            @php
                $guardada = $operacionesGuardadas->get($tipoOperacion->value);
                $activa = old("operaciones.$indice.activa", $guardada ? '1' : null);
                $nombreOperacion = match($tipoOperacion->value) {
                    'venta' => 'Venta',
                    'alquiler' => 'Alquiler',
                    default => 'Alquiler temporal',
                };
            @endphp
            <article class="border border-neutral-200 p-4" data-operacion>
                <input type="hidden" name="operaciones[{{ $indice }}][tipo_operacion]"
                       value="{{ $tipoOperacion->value }}">
                <label class="flex items-center gap-3 font-semibold">
                    <input type="checkbox" name="operaciones[{{ $indice }}][activa]"
                           value="1" data-operacion-activa @checked($activa)>
                    {{ $nombreOperacion }}
                </label>
                <div class="mt-4 space-y-4" data-operacion-campos>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-neutral-600">Moneda</label>
                        <select name="operaciones[{{ $indice }}][moneda]"
                                class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm">
                            <option value="">Sin precio</option>
                            @foreach ($monedas as $moneda)
                                <option value="{{ $moneda->value }}"
                                    @selected(old("operaciones.$indice.moneda", $guardada?->moneda?->value) === $moneda->value)>
                                    {{ $moneda->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-neutral-600">Precio</label>
                        <input type="text"
                               inputmode="decimal"
                               name="operaciones[{{ $indice }}][precio]"
                               value="{{ old("operaciones.$indice.precio", $guardada?->precio) }}"
                               placeholder="Ej.: 150.000"
                               data-precio-miles
                               class="h-10 w-full border border-neutral-300 px-3 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-neutral-600">Estado</label>
                        <select name="operaciones[{{ $indice }}][estado]"
                                class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm">
                            @foreach ($estadosOperacion as $estadoOperacion)
                                @continue(
                                    $tipoOperacion->value === 'venta' && $estadoOperacion->value === 'alquilada'
                                )
                                @continue(
                                    $tipoOperacion->value !== 'venta' && $estadoOperacion->value === 'vendida'
                                )
                                <option value="{{ $estadoOperacion->value }}"
                                    @selected(old("operaciones.$indice.estado", $guardada?->estado?->value ?? 'pausada') === $estadoOperacion->value)>
                                    {{ ucfirst($estadoOperacion->value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>

<section class="border border-neutral-200 bg-white p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Características</h2>
    <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            'ambientes' => 'Ambientes',
            'dormitorios' => 'Dormitorios',
            'banios' => 'Baños',
            'cocheras' => 'Cocheras',
            'antiguedad' => 'Antigüedad',
        ] as $campo => $etiqueta)
            <div>
                <label for="{{ $campo }}" class="mb-2 block text-sm font-medium">{{ $etiqueta }}</label>
                <input id="{{ $campo }}" name="{{ $campo }}" type="number" min="0"
                       value="{{ old($campo, $propiedad?->{$campo}) }}"
                       class="h-11 w-full border border-neutral-300 px-3">
            </div>
        @endforeach
        <div>
            <label for="orientacion" class="mb-2 block text-sm font-medium">Orientación</label>
            <select id="orientacion"
                    name="orientacion"
                    class="h-11 w-full border border-neutral-300 bg-white px-3">
                <option value="">Sin informar</option>
                @foreach ($orientaciones as $orientacion)
                    <option value="{{ $orientacion->value }}"
                        @selected(old('orientacion', $propiedad?->orientacion) === $orientacion->value)>
                        {{ $orientacion->etiqueta() }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            'superficie_total' => 'Superficie total',
            'superficie_cubierta' => 'Superficie cubierta',
            'superficie_descubierta' => 'Superficie descubierta',
            'superficie_terreno' => 'Superficie de terreno',
        ] as $campo => $etiqueta)
            <div>
                <label for="{{ $campo }}" class="mb-2 block text-sm font-medium">{{ $etiqueta }}</label>
                <input id="{{ $campo }}" name="{{ $campo }}" type="number" min="0" step="0.01"
                       value="{{ old($campo, $propiedad?->{$campo}) }}"
                       class="h-11 w-full border border-neutral-300 px-3">
            </div>
        @endforeach
        <div class="sm:col-span-2 lg:col-span-2">
            <label for="expensas" class="mb-2 block text-sm font-medium">Expensas</label>
            <div class="grid gap-3 sm:grid-cols-[150px_1fr]">
                <select id="expensas_moneda"
                        name="expensas_moneda"
                        class="h-11 w-full border border-neutral-300 bg-white px-3">
                    <option value="">Moneda</option>
                    @foreach ($monedas as $moneda)
                        <option value="{{ $moneda->value }}"
                            @selected(old('expensas_moneda', $propiedad?->expensas_moneda?->value) === $moneda->value)>
                            {{ $moneda->value }}
                        </option>
                    @endforeach
                </select>
                <input id="expensas"
                       name="expensas"
                       type="text"
                       inputmode="decimal"
                       value="{{ old('expensas', $propiedad?->expensas) }}"
                       placeholder="Ej.: 250.000"
                       data-precio-miles
                       class="h-11 w-full border border-neutral-300 px-3">
            </div>
        </div>
    </div>
</section>

<section class="border border-neutral-200 bg-white p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Cartel</h2>
    <p class="mt-1 text-sm text-neutral-600">
        Elegí una sola opción.
    </p>
    <div class="mt-5 grid gap-3 sm:grid-cols-3">
        <label class="flex min-h-11 items-center gap-3 border border-neutral-200 px-3 py-2 text-sm">
            <input type="radio"
                   name="cartel_caracteristica_id"
                   value=""
                   @checked($cartelSeleccionado === 0)
                   class="size-4">
            Sin informar
        </label>
        @foreach ($opcionesCartel as $opcionCartel)
            <label class="flex min-h-11 items-center gap-3 border border-neutral-200 px-3 py-2 text-sm">
                <input type="radio"
                       name="cartel_caracteristica_id"
                       value="{{ $opcionCartel->id }}"
                       @checked($cartelSeleccionado === $opcionCartel->id)
                       class="size-4">
                {{ $opcionCartel->nombre }}
            </label>
        @endforeach
    </div>
</section>

<section class="border border-neutral-200 bg-white p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Observaciones</h2>
    <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($observacionesCaracteristicas as $observacionCaracteristica)
            <label class="flex min-h-10 items-center gap-3 text-sm">
                <input type="checkbox"
                       name="caracteristicas[]"
                       value="{{ $observacionCaracteristica->id }}"
                       @checked($caracteristicasSeleccionadas->contains($observacionCaracteristica->id))
                       class="size-4">
                {{ $observacionCaracteristica->nombre }}
            </label>
        @endforeach
    </div>
</section>

<section class="border border-neutral-200 bg-white p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Preferencia de lote</h2>
    <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($preferenciasLote as $preferenciaLote)
            <label class="flex min-h-10 items-center gap-3 text-sm">
                <input type="checkbox"
                       name="caracteristicas[]"
                       value="{{ $preferenciaLote->id }}"
                       @checked($caracteristicasSeleccionadas->contains($preferenciaLote->id))
                       class="size-4">
                {{ $preferenciaLote->nombre }}
            </label>
        @endforeach
    </div>
</section>

<section class="border border-neutral-200 bg-white p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Amenities</h2>
    <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($amenitiesCaracteristicas as $amenityCaracteristica)
            <label class="flex min-h-10 items-center gap-3 text-sm">
                <input type="checkbox"
                       name="caracteristicas[]"
                       value="{{ $amenityCaracteristica->id }}"
                       @checked($caracteristicasSeleccionadas->contains($amenityCaracteristica->id))
                       class="size-4">
                {{ $amenityCaracteristica->nombre }}
            </label>
        @endforeach
    </div>
</section>

@if ($adicionalesCaracteristicas->isNotEmpty())
    <section class="border border-neutral-200 bg-white p-5 sm:p-6">
        <h2 class="text-lg font-semibold">Adicionales</h2>
        <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($adicionalesCaracteristicas as $adicional)
                <label class="flex min-h-10 items-center gap-3 text-sm">
                    <input type="checkbox"
                           name="caracteristicas[]"
                           value="{{ $adicional->id }}"
                           @checked($caracteristicasSeleccionadas->contains($adicional->id))
                           class="size-4">
                    {{ $adicional->nombre }}
                </label>
            @endforeach
        </div>
    </section>
@endif

<section class="border border-neutral-200 bg-white p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Servicios</h2>
    <p class="mt-1 text-sm text-neutral-600">
        Marcá los servicios disponibles en la propiedad.
    </p>
    <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($servicios as $servicio)
            <label class="flex min-h-10 items-center gap-3 text-sm">
                <input type="checkbox"
                       name="caracteristicas[]"
                       value="{{ $servicio->id }}"
                       @checked($caracteristicasSeleccionadas->contains($servicio->id))
                       class="size-4">
                {{ $servicio->nombre }}
            </label>
        @endforeach
    </div>
</section>

<section class="border border-neutral-200 bg-white p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Ambientes y espacios</h2>
    <p class="mt-1 text-sm text-neutral-600">
        Seleccioná los ambientes que forman parte de la propiedad.
    </p>
    <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($ambientesCaracteristicas as $ambienteCaracteristica)
            <label class="flex min-h-10 items-center gap-3 text-sm">
                <input type="checkbox"
                       name="caracteristicas[]"
                       value="{{ $ambienteCaracteristica->id }}"
                       @checked($caracteristicasSeleccionadas->contains($ambienteCaracteristica->id))
                       class="size-4">
                {{ $ambienteCaracteristica->nombre }}
            </label>
        @endforeach
    </div>
</section>

<section class="border border-neutral-200 bg-white p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Descripción y detalles</h2>
    <div class="mt-5 space-y-5">
        <div>
            <label for="descripcion_corta" class="mb-2 block text-sm font-medium">Descripción corta</label>
            <textarea id="descripcion_corta" name="descripcion_corta" rows="2" maxlength="500"
                      class="w-full border border-neutral-300 p-3">{{ old('descripcion_corta', $propiedad?->descripcion_corta) }}</textarea>
        </div>
        <div>
            <label for="descripcion" class="mb-2 block text-sm font-medium">Descripción completa</label>
            <textarea id="descripcion" name="descripcion" rows="7"
                      class="w-full border border-neutral-300 p-3">{{ old('descripcion', $propiedad?->descripcion) }}</textarea>
        </div>
    </div>
</section>

@if ($mostrarAcciones ?? true)
    <div class="flex flex-wrap gap-3">
        <button type="submit"
                class="inline-flex h-11 items-center bg-emerald-700 px-5 text-sm font-semibold text-white hover:bg-emerald-800">
            {{ $textoBoton }}
        </button>
        <a href="{{ route('administracion.propiedades.listar') }}"
           class="inline-flex h-11 items-center px-4 text-sm font-medium text-neutral-600">
            Cancelar
        </a>
    </div>
@endif
