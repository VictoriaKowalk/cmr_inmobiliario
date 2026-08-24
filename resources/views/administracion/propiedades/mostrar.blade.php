@extends('layouts.administracion')

@section('titulo', $propiedad->titulo)

@section('contenido')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-700">{{ $propiedad->codigo_interno }}</p>
            <h1 class="mt-1 text-2xl font-semibold">{{ $propiedad->titulo }}</h1>
            <p class="mt-1 text-sm text-neutral-600">{{ $propiedad->ubicacion->nombre_completo }}</p>
        </div>
        <a href="{{ route('administracion.propiedades.editar', $propiedad) }}"
           class="inline-flex h-11 items-center justify-center bg-emerald-700 px-4 text-sm font-semibold text-white">
            Editar propiedad
        </a>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1fr_320px]">
        <div class="space-y-6">
            @if ($propiedad->imagenes->isNotEmpty())
                <section class="border border-neutral-200 bg-white p-4">
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($propiedad->imagenes->take(6) as $imagen)
                            <div @class([
                                'overflow-hidden bg-neutral-100',
                                'sm:col-span-2' => $loop->first,
                                'aspect-[16/9]' => $loop->first,
                                'aspect-[4/3]' => ! $loop->first,
                            ])>
                                <img src="{{ $imagen->obtenerUrlPublica() }}"
                                     alt="{{ $imagen->nombre_original ?: 'Imagen de '.$propiedad->titulo }}"
                                     class="h-full w-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($propiedad->videos->isNotEmpty())
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

            <section class="border border-neutral-200 bg-white p-5 sm:p-6">
                <h2 class="font-semibold">Datos generales</h2>
                <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div><dt class="text-xs uppercase text-neutral-500">Tipo</dt><dd class="mt-1">{{ $propiedad->tipoPropiedad->nombre }}</dd></div>
                    <div>
                        <dt class="text-xs uppercase text-neutral-500">Dirección</dt>
                        <dd class="mt-1">{{ $propiedad->direccion ?: 'No informada' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-neutral-500">Coordenadas</dt>
                        <dd class="mt-1">
                            @if ($propiedad->latitud && $propiedad->longitud)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $propiedad->latitud }},{{ $propiedad->longitud }}"
                                   target="_blank"
                                   rel="noopener"
                                   class="font-medium text-emerald-700 hover:text-emerald-900">
                                    {{ $propiedad->latitud }}, {{ $propiedad->longitud }}
                                </a>
                                <span class="ml-2 text-xs text-neutral-500">
                                    {{ $propiedad->ubicacion_confirmada ? 'Confirmada' : 'Sin confirmar' }}
                                </span>
                            @else
                                No informadas
                            @endif
                        </dd>
                    </div>
                    <div><dt class="text-xs uppercase text-neutral-500">Ambientes</dt><dd class="mt-1">{{ $propiedad->ambientes ?? 'No informado' }}</dd></div>
                    <div><dt class="text-xs uppercase text-neutral-500">Dormitorios</dt><dd class="mt-1">{{ $propiedad->dormitorios ?? 'No informado' }}</dd></div>
                    <div><dt class="text-xs uppercase text-neutral-500">Superficie total</dt><dd class="mt-1">{{ $propiedad->superficie_total ? $propiedad->superficie_total.' m²' : 'No informada' }}</dd></div>
                    <div>
                        <dt class="text-xs uppercase text-neutral-500">Expensas</dt>
                        <dd class="mt-1">
                            @if ($propiedad->expensas)
                                {{ $propiedad->expensas_moneda?->value }} {{ number_format((float) $propiedad->expensas, 2, ',', '.') }}
                            @else
                                No informadas
                            @endif
                        </dd>
                    </div>
                    <div><dt class="text-xs uppercase text-neutral-500">Destacada</dt><dd class="mt-1">{{ $propiedad->estaDestacada() ? 'Sí' : 'No' }}</dd></div>
                </dl>
                @if ($propiedad->descripcion)
                    <div class="mt-7 border-t border-neutral-200 pt-5">
                        <h2 class="font-semibold">Descripción</h2>
                        <p class="mt-3 whitespace-pre-line text-sm leading-6 text-neutral-700">{{ $propiedad->descripcion }}</p>
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
                    <div class="mt-7 grid gap-6 border-t border-neutral-200 pt-5 sm:grid-cols-2 lg:grid-cols-3">
                        @if ($serviciosPropiedad->isNotEmpty())
                            <div>
                                <h2 class="font-semibold">Servicios</h2>
                                <ul class="mt-3 space-y-2 text-sm text-neutral-700">
                                    @foreach ($serviciosPropiedad as $servicio)
                                        <li>{{ $servicio->nombre }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($ambientesPropiedad->isNotEmpty())
                            <div>
                                <h2 class="font-semibold">Ambientes y espacios</h2>
                                <ul class="mt-3 space-y-2 text-sm text-neutral-700">
                                    @foreach ($ambientesPropiedad as $ambiente)
                                        <li>{{ $ambiente->nombre }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($cartelPropiedad)
                            <div>
                                <h2 class="font-semibold">Cartel</h2>
                                <p class="mt-3 text-sm text-neutral-700">{{ $cartelPropiedad->nombre }}</p>
                            </div>
                        @endif
                        @if ($observacionesPropiedad->isNotEmpty())
                            <div>
                                <h2 class="font-semibold">Observaciones</h2>
                                <ul class="mt-3 space-y-2 text-sm text-neutral-700">
                                    @foreach ($observacionesPropiedad as $observacion)
                                        <li>{{ $observacion->nombre }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($preferenciasLotePropiedad->isNotEmpty())
                            <div>
                                <h2 class="font-semibold">Preferencia de lote</h2>
                                <ul class="mt-3 space-y-2 text-sm text-neutral-700">
                                    @foreach ($preferenciasLotePropiedad as $preferencia)
                                        <li>{{ $preferencia->nombre }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($amenitiesPropiedad->isNotEmpty())
                            <div>
                                <h2 class="font-semibold">Amenities</h2>
                                <ul class="mt-3 space-y-2 text-sm text-neutral-700">
                                    @foreach ($amenitiesPropiedad as $amenity)
                                        <li>{{ $amenity->nombre }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif
            </section>
        </div>

        <aside class="space-y-6">
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

            <section class="border border-neutral-200 bg-white p-5">
                <h2 class="font-semibold">Operaciones</h2>
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
                                  class="mt-4 grid gap-3 sm:grid-cols-[120px_minmax(120px,1fr)_160px_auto]">
                                @csrf
                                @method('PUT')
                                <select name="moneda"
                                        class="h-9 border border-neutral-300 bg-white px-2 text-sm">
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
                                       class="h-9 border border-neutral-300 px-2 text-sm">
                                <select name="estado"
                                        class="h-9 border border-neutral-300 bg-white px-2 text-sm">
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
                                <button class="h-9 bg-neutral-900 px-3 text-xs font-semibold text-white">
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
            </section>

            @if (auth()->user()->puedeSupervisar())
            <section class="border border-red-200 bg-white p-5">
                <h2 class="font-semibold text-red-800">Eliminar propiedad</h2>
                <p class="mt-2 text-sm text-neutral-600">Se podrá restaurar desde el listado de eliminadas.</p>
                <form method="POST" action="{{ route('administracion.propiedades.eliminar', $propiedad) }}"
                      class="mt-4"
                      onsubmit="return confirm('¿Eliminar esta propiedad del panel activo?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-sm font-semibold text-red-700">Eliminar propiedad</button>
                </form>
            </section>
            @endif
        </aside>
    </div>
@endsection
