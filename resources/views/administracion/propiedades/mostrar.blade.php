@extends('layouts.administracion')

@section('titulo', $propiedad->titulo)

@section('contenido')
    @php
        $operacionPrincipal = $propiedad->operaciones->firstWhere('estado.value', 'publicada') ?? $propiedad->operaciones->first();
        $precioPrincipal = $operacionPrincipal?->precio
            ? ($operacionPrincipal->moneda?->value.' '.number_format((float) $operacionPrincipal->precio, 2, ',', '.'))
            : 'Consultar precio';
    @endphp

    <div class="property-detail-heading mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="dashboard-eyebrow">Propiedad · Código {{ $propiedad->codigo_interno }}</p>
            <h1 class="mt-1 text-2xl font-semibold">{{ $propiedad->titulo }}</h1>
            @if ($propiedad->direccion)
                <p class="property-detail-address mt-1">{{ $propiedad->direccion }}</p>
            @endif
            <p class="mt-1 text-sm text-neutral-600">{{ $propiedad->ubicacion->nombre_completo }}</p>
            <div class="property-detail-summary mt-4">
                <span>{{ $propiedad->tipoPropiedad->nombre }}</span>
                @if ($operacionPrincipal)<span>{{ ucfirst(str_replace('_', ' ', $operacionPrincipal->tipo_operacion->value)) }} · {{ ucfirst($operacionPrincipal->estado->value) }}</span>@endif
                <strong>{{ $precioPrincipal }}</strong>
                @if ($propiedad->ambientes)<span>{{ $propiedad->ambientes }} ambientes</span>@endif
                @if ($propiedad->superficie_total)<span>{{ rtrim(rtrim(number_format((float) $propiedad->superficie_total, 2, ',', '.'), '0'), ',') }} m²</span>@endif
            </div>
        </div>
        <div class="property-detail-actions">
            <a href="{{ route('administracion.propiedades.imprimir', $propiedad) }}" target="_blank" rel="noopener" class="property-detail-secondary-action">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v7H6v-7Z"/><path d="M18 12h.01"/></svg>
                Imprimir ficha
            </a>
            <a href="{{ route('administracion.propiedades.pdf', $propiedad) }}" class="property-detail-secondary-action">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12M7 10l5 5 5-5M5 21h14"/></svg>
                Descargar PDF
            </a>
            <a href="{{ route('administracion.propiedades.editar', $propiedad) }}"
               class="property-detail-edit-action inline-flex h-11 items-center justify-center px-4 text-sm font-semibold text-white">
                Editar propiedad
            </a>
        </div>
    </div>

    <div class="property-detail-content flex flex-col gap-6">
            @if ($propiedad->imagenes->isNotEmpty())
                <section class="property-detail-gallery border border-neutral-200 bg-white p-4" data-property-gallery>
                    @php $imagenPrincipal = $propiedad->imagenes->first(); @endphp
                    <div class="property-detail-gallery__main">
                        <img src="{{ $imagenPrincipal->obtenerUrlPublica() }}"
                             alt="{{ $imagenPrincipal->nombre_original ?: 'Imagen de '.$propiedad->titulo }}"
                             data-property-gallery-main>
                        <button type="button" class="property-detail-gallery__open" data-property-gallery-open>
                            Ver galería · {{ $propiedad->imagenes->count() }} fotos
                        </button>
                    </div>
                    @if ($propiedad->imagenes->count() > 1)
                        <div class="property-detail-gallery__thumbs">
                            @foreach ($propiedad->imagenes->take(6) as $imagen)
                                <button type="button" @class(['is-active' => $loop->first])
                                        data-property-gallery-thumb
                                        data-image-url="{{ $imagen->obtenerUrlPublica() }}"
                                        data-image-alt="{{ $imagen->nombre_original ?: 'Imagen de '.$propiedad->titulo }}">
                                    <img src="{{ $imagen->obtenerUrlPublica() }}" alt="">
                                </button>
                            @endforeach
                        </div>
                    @endif
                    <dialog class="property-detail-gallery__dialog" data-property-gallery-dialog>
                        <div class="property-detail-gallery__dialog-header">
                            <strong>Galería de {{ $propiedad->titulo }}</strong>
                            <button type="button" data-property-gallery-close aria-label="Cerrar galería">×</button>
                        </div>
                        <div class="property-detail-gallery__dialog-grid">
                            @foreach ($propiedad->imagenes as $imagen)
                                <img src="{{ $imagen->obtenerUrlPublica() }}" alt="{{ $imagen->nombre_original ?: 'Imagen de '.$propiedad->titulo }}">
                            @endforeach
                        </div>
                    </dialog>
                </section>
            @endif

            @if (false && $propiedad->videos->isNotEmpty())
                <section class="border border-neutral-200 bg-white p-5 sm:p-6">
                    <h2 class="font-semibold">Videos</h2>
                    <div class="mt-5 grid gap-4 lg:grid-cols-2">
                        @foreach ($propiedad->videos as $video)
                            <article class="overflow-hidden border border-neutral-200">
                                <div class="aspect-video bg-neutral-100">
                                    @if ($video->esYoutube())
                                        <iframe src="{{ $video->obtenerUrlEmbedYoutube() }}"
                                                title="{{ $video->titulo ?: 'Video de '.$propiedad->titulo }}"
                                                class="h-full w-full"
                                                allowfullscreen
                                                loading="lazy"></iframe>
                                    @else
                                        <video src="{{ $video->obtenerUrlPublica() }}"
                                               controls
                                               preload="metadata"
                                               class="h-full w-full bg-black"></video>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <p class="font-medium">
                                        {{ $video->titulo ?: ($video->esYoutube() ? 'Video de YouTube' : 'Video subido') }}
                                    </p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="property-detail-general border border-neutral-200 bg-white p-5 sm:p-6">
                <h2 class="font-semibold">Datos generales</h2>
                <div class="property-detail-data mt-5">
                    <section class="property-detail-characteristics">
                        <h3>Características</h3>
                        <dl>
                            @if ($propiedad->antiguedad !== null)<div><dt>Antigüedad:</dt><dd>{{ $propiedad->antiguedad }}</dd></div>@endif
                            @if ($propiedad->ambientes !== null)<div><dt>Cant. ambientes:</dt><dd>{{ $propiedad->ambientes }}</dd></div>@endif
                            @if ($propiedad->dormitorios !== null)<div><dt>Cant. dormitorios:</dt><dd>{{ $propiedad->dormitorios }}</dd></div>@endif
                            @if ($propiedad->banios !== null)<div><dt>Cant. baños:</dt><dd>{{ $propiedad->banios }}</dd></div>@endif
                            @if ($propiedad->cocheras !== null)<div><dt>Cant. cocheras:</dt><dd>{{ $propiedad->cocheras }}</dd></div>@endif
                            @if ($propiedad->orientacion)<div><dt>Orientación:</dt><dd>{{ ucfirst($propiedad->orientacion) }}</dd></div>@endif
                        </dl>
                    </section>
                    <section class="property-detail-surfaces">
                        <h3>Superficies</h3>
                        <dl>
                            <div><dt>Total</dt><dd>{{ $propiedad->superficie_total ? $propiedad->superficie_total.' m²' : 'No informada' }}</dd></div>
                            <div><dt>Cubierta</dt><dd>{{ $propiedad->superficie_cubierta ? $propiedad->superficie_cubierta.' m²' : 'No informada' }}</dd></div>
                            <div><dt>Descubierta</dt><dd>{{ $propiedad->superficie_descubierta ? $propiedad->superficie_descubierta.' m²' : 'No informada' }}</dd></div>
                            @if ($propiedad->superficie_terreno)<div><dt>Terreno</dt><dd>{{ $propiedad->superficie_terreno }} m²</dd></div>@endif
                        </dl>
                    </section>
                </div>
                @if ($propiedad->descripcion)
                    <div class="mt-7 pt-5">
                        <h2 class="font-semibold">Descripción</h2>
                        <p class="mt-3 whitespace-pre-line text-sm leading-6 text-neutral-700">{{ str($propiedad->descripcion)->limit(520) }}</p>
                        @if (mb_strlen($propiedad->descripcion) > 520)
                            <details class="property-detail-description mt-3">
                                <summary>Ver descripción completa</summary>
                                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-neutral-700">{{ $propiedad->descripcion }}</p>
                            </details>
                        @endif
                    </div>
                @endif

                @php
                    $serviciosPropiedad = $propiedad->caracteristicas
                        ->where('categoria', \App\Enums\CategoriaCaracteristica::SERVICIO);
                    $ambientesPropiedad = $propiedad->caracteristicas
                        ->where('categoria', \App\Enums\CategoriaCaracteristica::AMBIENTE);
                    $cartelPropiedad = $propiedad->caracteristicas
                        ->firstWhere('categoria', \App\Enums\CategoriaCaracteristica::CARTEL);
                    $observacionesPropiedad = $propiedad->caracteristicas
                        ->where('categoria', \App\Enums\CategoriaCaracteristica::OBSERVACION);
                    $preferenciasLotePropiedad = $propiedad->caracteristicas
                        ->where('categoria', \App\Enums\CategoriaCaracteristica::PREFERENCIA_LOTE);
                    $amenitiesPropiedad = $propiedad->caracteristicas
                        ->where('categoria', \App\Enums\CategoriaCaracteristica::AMENITY);
                @endphp

                @if (
                    $serviciosPropiedad->isNotEmpty()
                    || $ambientesPropiedad->isNotEmpty()
                    || $cartelPropiedad
                    || $observacionesPropiedad->isNotEmpty()
                    || $preferenciasLotePropiedad->isNotEmpty()
                    || $amenitiesPropiedad->isNotEmpty()
                )
                    <div class="property-detail-features mt-7 border-t border-neutral-200 pt-5">
                        @if ($serviciosPropiedad->isNotEmpty())
                            <div>
                                <h2 class="font-semibold">Servicios</h2>
                                <ul class="property-detail-list mt-3">
                                    @foreach ($serviciosPropiedad as $servicio)
                                        <li>{{ $servicio->nombre }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($ambientesPropiedad->isNotEmpty())
                            <div>
                                <h2 class="font-semibold">Ambientes y espacios</h2>
                                <ul class="property-detail-list mt-3">
                                    @foreach ($ambientesPropiedad as $ambiente)
                                        <li>{{ $ambiente->nombre }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($amenitiesPropiedad->isNotEmpty())
                            <div>
                                <h2 class="font-semibold">Amenities</h2>
                                <ul class="property-detail-list mt-3">
                                    @foreach ($amenitiesPropiedad as $amenity)
                                        <li>{{ $amenity->nombre }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($observacionesPropiedad->isNotEmpty())
                            <div>
                                <h2 class="font-semibold">Observaciones</h2>
                                <ul class="property-detail-list mt-3">
                                    @foreach ($observacionesPropiedad as $observacion)
                                        <li>{{ $observacion->nombre }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($preferenciasLotePropiedad->isNotEmpty())
                            <div>
                                <h2 class="font-semibold">Preferencia de lote</h2>
                                <ul class="property-detail-list mt-3">
                                    @foreach ($preferenciasLotePropiedad as $preferencia)
                                        <li>{{ $preferencia->nombre }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($cartelPropiedad)
                            <div>
                                <h2 class="font-semibold">Cartel</h2>
                                <ul class="property-detail-list mt-3"><li>{{ $cartelPropiedad->nombre }}</li></ul>
                            </div>
                        @endif
                    </div>
                @endif
            </section>

            {{--
            <section class="border border-neutral-200 bg-white p-5">
                @php
                    $checklistPublicacion = collect($propiedad->obtenerChecklistPublicacion());
                    $estadoChecklist = $propiedad->estadoChecklistPublicacion();
                @endphp
                <div class="flex items-center justify-between gap-3">
                    <h2 class="font-semibold">Checklist</h2>
                    <span @class([
                        'px-2 py-1 text-xs font-semibold',
                        'bg-emerald-50 text-emerald-800' => $estadoChecklist === 'lista',
                        'bg-amber-50 text-amber-800' => $estadoChecklist === 'publicada_incompleta',
                        'bg-red-50 text-red-800' => $estadoChecklist === 'incompleta',
                    ])>
                        {{ $propiedad->etiquetaChecklistPublicacion() }}
                    </span>
                </div>
                <ul class="mt-4 space-y-3 text-sm">
                    @foreach ($checklistPublicacion as $item)
                        <li class="flex gap-3">
                            <span @class([
                                'mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full text-xs font-semibold',
                                'bg-emerald-100 text-emerald-800' => $item['completo'],
                                'bg-red-100 text-red-800' => ! $item['completo'] && $item['obligatorio'],
                                'bg-amber-100 text-amber-800' => ! $item['completo'] && ! $item['obligatorio'],
                            ])>
                                {{ $item['completo'] ? '✓' : '!' }}
                            </span>
                            <span @class([
                                'text-neutral-700' => $item['completo'],
                                'font-medium text-red-800' => ! $item['completo'] && $item['obligatorio'],
                                'text-amber-800' => ! $item['completo'] && ! $item['obligatorio'],
                            ])>
                                {{ $item['texto'] }}
                            </span>
                        </li>
                    @endforeach
                </ul>
                @if ($propiedad->cantidadPendientesChecklistPublicacion() > 0)
                    <a href="{{ route('administracion.propiedades.editar', $propiedad) }}"
                       class="mt-5 inline-flex h-10 items-center bg-neutral-900 px-4 text-sm font-semibold text-white">
                        Completar datos
                    </a>
                @endif
            </section>
            --}}

            <section class="property-detail-operations border border-neutral-200 bg-white p-5">
                <h2 class="font-semibold">Operaciones</h2>
                <div class="property-detail-operations__grid mt-4">
                    @foreach ($propiedad->operaciones as $operacion)
                        <article class="property-detail-operation">
                            <p><span>Tipo de operación:</span><strong>{{ str_replace('_', ' ', ucfirst($operacion->tipo_operacion->value)) }}</strong></p>
                            <dl class="mt-3">
                                <div><dt>Estado:</dt><dd>{{ ucfirst($operacion->estado->value) }}</dd></div>
                            </dl>
                        </article>
                    @endforeach
                    <article class="property-detail-operation property-detail-operation--expense">
                        <p><span>Expensas:</span><strong>{{ $propiedad->expensas ? $propiedad->expensas_moneda?->value.' '.number_format((float) $propiedad->expensas, 2, ',', '.') : 'No informadas' }}</strong></p>
                        @if ($operacionPrincipal)
                            <dl class="mt-3"><div><dt>Precio:</dt><dd>{{ $operacionPrincipal->precio ? $operacionPrincipal->moneda?->value.' '.number_format((float) $operacionPrincipal->precio, 2, ',', '.') : 'Consultar' }}</dd></div></dl>
                        @endif
                    </article>
                </div>
                @if (false)
                @if ($errors->has('estado_operacion'))
                    <div class="mt-4 border-l-4 border-red-600 bg-red-50 p-3 text-sm text-red-900">
                        {{ $errors->first('estado_operacion') }}
                    </div>
                @endif
                <div class="mt-4 space-y-3">
                    @foreach ($propiedad->operaciones as $operacion)
                        <div class="border border-neutral-200 p-3">
                            <p class="font-semibold">{{ str_replace('_', ' ', ucfirst($operacion->tipo_operacion->value)) }}</p>
                            <p class="mt-1 text-sm text-neutral-600">
                                {{ ucfirst($operacion->estado->value) }} ·
                                @if ($operacion->precio)
                                    {{ $operacion->moneda?->value }} {{ number_format((float) $operacion->precio, 2, ',', '.') }}
                                @else
                                    Consultar
                                @endif
                            </p>
                            <form method="POST"
                                  action="{{ route('administracion.propiedades.operaciones.actualizar', [$propiedad, $operacion]) }}"
                                  class="property-operation-editor mt-4 grid gap-3" data-operation-editor>
                                @csrf
                                @method('PUT')
                                <select name="moneda"
                                        class="h-9 min-w-0 border border-neutral-300 bg-white px-2 text-sm">
                                    <option value="">Consultar</option>
                                    @foreach (\App\Enums\Moneda::cases() as $moneda)
                                        <option value="{{ $moneda->value }}" @selected($operacion->moneda === $moneda)>
                                            {{ $moneda->value }}
                                        </option>
                                    @endforeach
                                </select>
                                <input name="precio" type="number" min="0" step="0.01"
                                       value="{{ $operacion->precio }}"
                                       placeholder="Precio"
                                       class="h-9 min-w-0 border border-neutral-300 px-2 text-sm">
                                <select name="estado"
                                        class="h-9 min-w-0 border border-neutral-300 bg-white px-2 text-sm">
                                    @foreach (\App\Enums\EstadoOperacion::cases() as $estadoOperacion)
                                        @continue(
                                            $operacion->tipo_operacion === \App\Enums\TipoOperacion::VENTA
                                            && $estadoOperacion === \App\Enums\EstadoOperacion::ALQUILADA
                                        )
                                        @continue(
                                            $operacion->tipo_operacion !== \App\Enums\TipoOperacion::VENTA
                                            && $estadoOperacion === \App\Enums\EstadoOperacion::VENDIDA
                                        )
                                        <option value="{{ $estadoOperacion->value }}" @selected($operacion->estado === $estadoOperacion)>
                                            {{ ucfirst($estadoOperacion->value) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="h-9 min-w-0 px-3 text-xs font-semibold text-white" data-operation-save>
                                    Guardar
                                </button>
                            </form>
                            @php
                                $estadosRapidos = [
                                    \App\Enums\EstadoOperacion::PUBLICADA->value => 'Publicar',
                                    \App\Enums\EstadoOperacion::PAUSADA->value => 'Pausar',
                                ];

                                if ($operacion->tipo_operacion === \App\Enums\TipoOperacion::VENTA) {
                                    $estadosRapidos[\App\Enums\EstadoOperacion::VENDIDA->value] = 'Marcar vendida';
                                } else {
                                    $estadosRapidos[\App\Enums\EstadoOperacion::ALQUILADA->value] = 'Marcar alquilada';
                                }
                            @endphp
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($estadosRapidos as $estadoRapido => $etiquetaRapida)
                                    @continue($operacion->estado->value === $estadoRapido)
                                    <form method="POST"
                                          action="{{ route('administracion.propiedades.operaciones.cambiar-estado', [$propiedad, $operacion]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="estado" value="{{ $estadoRapido }}">
                                        <button @class([
                                            'px-2 py-1 text-xs font-semibold',
                                            'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' => $estadoRapido === 'publicada',
                                            'bg-neutral-100 text-neutral-700 hover:bg-neutral-200' => $estadoRapido === 'pausada',
                                            'bg-amber-50 text-amber-800 hover:bg-amber-100' => in_array($estadoRapido, ['vendida', 'alquilada'], true),
                                        ])>
                                            {{ $etiquetaRapida }}
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </section>

            @if ($propiedad->videos->isNotEmpty())
                <section class="property-detail-videos border border-neutral-200 bg-white p-5 sm:p-6">
                    <h2 class="font-semibold">Videos</h2>
                    <div class="mt-5 grid gap-4 lg:grid-cols-2">
                        @foreach ($propiedad->videos as $video)
                            <article class="overflow-hidden border border-neutral-200">
                                <div class="aspect-video bg-neutral-100">
                                    @if ($video->esYoutube())
                                        <iframe src="{{ $video->obtenerUrlEmbedYoutube() }}"
                                                title="{{ $video->titulo ?: 'Video de '.$propiedad->titulo }}"
                                                class="h-full w-full" allowfullscreen loading="lazy"></iframe>
                                    @else
                                        <video src="{{ $video->obtenerUrlPublica() }}" controls preload="metadata" class="h-full w-full bg-black"></video>
                                    @endif
                                </div>
                                <div class="p-4"><p class="font-medium">{{ $video->titulo ?: ($video->esYoutube() ? 'Video de YouTube' : 'Video subido') }}</p></div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (auth()->user()->puedeSupervisar())
            <details class="property-danger-zone border border-red-200 bg-white p-5">
                <summary>Más acciones</summary>
                <div class="mt-4 border-t border-red-100 pt-4">
                    <h2 class="font-semibold text-red-800">Eliminar propiedad</h2>
                    <p class="mt-2 text-sm text-neutral-600">Se podrá restaurar desde el listado de eliminadas.</p>
                    <form method="POST" action="{{ route('administracion.propiedades.eliminar', $propiedad) }}"
                          class="mt-4"
                          onsubmit="return confirm('¿Eliminar esta propiedad del panel activo?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-sm font-semibold text-red-700">Eliminar propiedad</button>
                    </form>
                </div>
            </details>
            @endif
    </div>
@endsection
