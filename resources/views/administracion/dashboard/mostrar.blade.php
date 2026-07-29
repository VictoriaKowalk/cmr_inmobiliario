@extends('layouts.administracion')

@section('titulo', 'Dashboard')

@section('contenido')
    <div class="mb-8">
        <h1 class="text-2xl font-semibold">Dashboard</h1>
        <p class="mt-1 text-sm text-neutral-600">Resumen general de la inmobiliaria.</p>
    </div>

    <section class="mb-6 border border-neutral-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-semibold">Acciones rápidas</h2>
                <p class="text-sm text-neutral-600">Atajos para las tareas más frecuentes del panel.</p>
            </div>
        </div>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            <a href="{{ route('administracion.propiedades.crear') }}"
               class="inline-flex min-h-11 items-center justify-center border border-emerald-700 bg-emerald-700 px-4 text-center text-sm font-semibold text-white hover:bg-emerald-800">
                Nueva propiedad
            </a>
            <a href="{{ route('administracion.propiedades.listar') }}"
               class="inline-flex min-h-11 items-center justify-center border border-neutral-300 bg-white px-4 text-center text-sm font-semibold text-neutral-800 hover:border-neutral-500">
                Ver propiedades
            </a>
            <a href="{{ route('administracion.consultas.listar') }}"
               class="inline-flex min-h-11 items-center justify-center border border-neutral-300 bg-white px-4 text-center text-sm font-semibold text-neutral-800 hover:border-neutral-500">
                Ver consultas
            </a>
            <a href="{{ route('administracion.tasaciones.listar') }}"
               class="inline-flex min-h-11 items-center justify-center border border-neutral-300 bg-white px-4 text-center text-sm font-semibold text-neutral-800 hover:border-neutral-500">
                Ver tasaciones
            </a>
            <a href="{{ route('administracion.ubicaciones.crear') }}"
               class="inline-flex min-h-11 items-center justify-center border border-neutral-300 bg-white px-4 text-center text-sm font-semibold text-neutral-800 hover:border-neutral-500">
                Cargar ubicación
            </a>
            <a href="{{ route('administracion.tipos-propiedad.crear') }}"
               class="inline-flex min-h-11 items-center justify-center border border-neutral-300 bg-white px-4 text-center text-sm font-semibold text-neutral-800 hover:border-neutral-500">
                Cargar tipo
            </a>
        </div>
    </section>

    <section class="mb-6 border border-neutral-200 bg-white p-5 shadow-sm">
        <div class="mb-4">
            <h2 class="font-semibold">Alertas operativas</h2>
            <p class="text-sm text-neutral-600">Puntos que conviene revisar para mantener la publicación y el seguimiento al día.</p>
        </div>

        @if (
            $propiedadesPublicadasSinImagen === 0
            && $propiedadesPublicadasSinPortada === 0
            && $operacionesPublicadasSinPrecio === 0
            && $propiedadesSinOperacionPublicada === 0
            && $contactosSinLeer === 0
        )
            <div class="border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">
                No hay alertas pendientes.
            </div>
        @else
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-6">
                <a href="{{ route('administracion.propiedades.listar', ['revision' => 'sin_imagen']) }}"
                   class="block border border-neutral-200 px-4 py-3 transition hover:border-neutral-400 hover:bg-neutral-50">
                    <p class="text-2xl font-semibold">{{ $propiedadesPublicadasSinImagen }}</p>
                    <p class="mt-1 text-sm font-medium">Publicadas sin imagen</p>
                    <p class="mt-1 text-xs text-neutral-500">Revisar fichas activas.</p>
                </a>
                <a href="{{ route('administracion.propiedades.listar', ['revision' => 'sin_portada']) }}"
                   class="block border border-neutral-200 px-4 py-3 transition hover:border-neutral-400 hover:bg-neutral-50">
                    <p class="text-2xl font-semibold">{{ $propiedadesPublicadasSinPortada }}</p>
                    <p class="mt-1 text-sm font-medium">Sin portada</p>
                    <p class="mt-1 text-xs text-neutral-500">Mejora la ficha pública.</p>
                </a>
                <a href="{{ route('administracion.propiedades.listar', ['revision' => 'sin_precio']) }}"
                   class="block border border-neutral-200 px-4 py-3 transition hover:border-neutral-400 hover:bg-neutral-50">
                    <p class="text-2xl font-semibold">{{ $operacionesPublicadasSinPrecio }}</p>
                    <p class="mt-1 text-sm font-medium">Operaciones sin precio</p>
                    <p class="mt-1 text-xs text-neutral-500">Se mostrarán como consultar.</p>
                </a>
                <a href="{{ route('administracion.propiedades.listar', ['revision' => 'sin_operacion_publicada']) }}"
                   class="block border border-neutral-200 px-4 py-3 transition hover:border-neutral-400 hover:bg-neutral-50">
                    <p class="text-2xl font-semibold">{{ $propiedadesSinOperacionPublicada }}</p>
                    <p class="mt-1 text-sm font-medium">Sin operación publicada</p>
                    <p class="mt-1 text-xs text-neutral-500">No aparecen en la web.</p>
                </a>
                <a href="{{ route('administracion.consultas.listar', ['lectura' => 'sin_leer']) }}"
                   class="block border border-neutral-200 px-4 py-3 transition hover:border-neutral-400 hover:bg-neutral-50">
                    <p class="text-2xl font-semibold">{{ $consultasSinLeer }}</p>
                    <p class="mt-1 text-sm font-medium">Consultas sin leer</p>
                    <p class="mt-1 text-xs text-neutral-500">Revisar bandeja.</p>
                </a>
                <a href="{{ route('administracion.tasaciones.listar', ['lectura' => 'sin_leer']) }}"
                   class="block border border-neutral-200 px-4 py-3 transition hover:border-neutral-400 hover:bg-neutral-50">
                    <p class="text-2xl font-semibold">{{ $tasacionesSinLeer }}</p>
                    <p class="mt-1 text-sm font-medium">Tasaciones sin leer</p>
                    <p class="mt-1 text-xs text-neutral-500">Revisar bandeja.</p>
                </a>
            </div>
        @endif
    </section>

    <section class="grid gap-4 sm:grid-cols-3">
        <a href="{{ route('administracion.propiedades.listar', ['estado' => 'publicada']) }}"
           class="block border border-neutral-200 bg-white p-5 shadow-sm transition hover:border-neutral-400 hover:bg-neutral-50">
            <p class="text-sm text-neutral-600">Propiedades publicadas</p>
            <p class="mt-3 text-3xl font-semibold">{{ $cantidadPublicadas }}</p>
        </a>
        <a href="{{ route('administracion.propiedades.listar', ['estado' => 'pausada']) }}"
           class="block border border-neutral-200 bg-white p-5 shadow-sm transition hover:border-neutral-400 hover:bg-neutral-50">
            <p class="text-sm text-neutral-600">Propiedades pausadas</p>
            <p class="mt-3 text-3xl font-semibold">{{ $cantidadPausadas }}</p>
        </a>
        <a href="{{ route('administracion.propiedades.listar', ['revision' => 'destacadas']) }}"
           class="block border border-neutral-200 bg-white p-5 shadow-sm transition hover:border-neutral-400 hover:bg-neutral-50">
            <p class="text-sm text-neutral-600">Propiedades destacadas</p>
            <p class="mt-3 text-3xl font-semibold">{{ $cantidadDestacadas }}</p>
        </a>
    </section>

    <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('administracion.consultas.listar', ['estado' => 'nueva']) }}"
           class="block border border-neutral-200 bg-white p-5 shadow-sm transition hover:border-neutral-400 hover:bg-neutral-50">
            <p class="text-sm text-neutral-600">Consultas nuevas</p>
            <p class="mt-3 text-3xl font-semibold">{{ $consultasNuevas }}</p>
            <p class="mt-2 text-xs text-neutral-500">{{ $consultasSinLeer }} sin leer</p>
        </a>
        <a href="{{ route('administracion.tasaciones.listar', ['estado' => 'nueva']) }}"
           class="block border border-neutral-200 bg-white p-5 shadow-sm transition hover:border-neutral-400 hover:bg-neutral-50">
            <p class="text-sm text-neutral-600">Tasaciones nuevas</p>
            <p class="mt-3 text-3xl font-semibold">{{ $tasacionesNuevas }}</p>
            <p class="mt-2 text-xs text-neutral-500">{{ $tasacionesSinLeer }} sin leer</p>
        </a>
        <a href="{{ route('administracion.consultas.listar', ['estado' => 'en_seguimiento']) }}"
           class="block border border-neutral-200 bg-white p-5 shadow-sm transition hover:border-neutral-400 hover:bg-neutral-50">
            <p class="text-sm text-neutral-600">Consultas en seguimiento</p>
            <p class="mt-3 text-3xl font-semibold">{{ $consultasEnSeguimiento }}</p>
            <p class="mt-2 text-xs text-neutral-500">Pendientes comerciales</p>
        </a>
        <a href="{{ route('administracion.tasaciones.listar', ['estado' => 'en_seguimiento']) }}"
           class="block border border-neutral-200 bg-white p-5 shadow-sm transition hover:border-neutral-400 hover:bg-neutral-50">
            <p class="text-sm text-neutral-600">Tasaciones en seguimiento</p>
            <p class="mt-3 text-3xl font-semibold">{{ $tasacionesEnSeguimiento }}</p>
            <p class="mt-2 text-xs text-neutral-500">Pendientes comerciales</p>
        </a>
    </section>

    <section class="mt-8 grid gap-6 xl:grid-cols-2">
        <article class="border border-neutral-200 bg-white shadow-sm">
            <header class="border-b border-neutral-200 px-5 py-4">
                <h2 class="font-semibold">Últimas consultas</h2>
            </header>
            @forelse ($ultimasConsultas as $consulta)
                <a href="{{ route('administracion.consultas.mostrar', $consulta) }}"
                   class="block border-b border-neutral-100 px-5 py-4 hover:bg-neutral-50 last:border-b-0">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-semibold">{{ $consulta->nombre }}</p>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $consulta->estado_seguimiento->clasesBadge() }}">
                                    {{ $consulta->estado_seguimiento->etiqueta() }}
                                </span>
                                @if ($consulta->leida_en === null)
                                    <span class="bg-white px-2 py-1 text-xs font-semibold text-sky-700 ring-1 ring-inset ring-sky-100">
                                        Sin leer
                                    </span>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-neutral-600">
                                {{ $consulta->propiedad?->codigo_interno ?? 'Consulta general' }}
                                @if ($consulta->propiedad)
                                    · {{ $consulta->propiedad->titulo }}
                                @endif
                            </p>
                            <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-xs text-neutral-500">
                                <span>{{ $consulta->telefono ?: 'Sin teléfono' }}</span>
                                <span>{{ $consulta->email ?: 'Sin email' }}</span>
                            </div>
                        </div>
                        <span class="shrink-0 text-xs text-neutral-500">
                            {{ $consulta->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </a>
            @empty
                <p class="px-5 py-8 text-sm text-neutral-500">Todavía no se recibieron consultas.</p>
            @endforelse
        </article>

        <article class="border border-neutral-200 bg-white shadow-sm">
            <header class="border-b border-neutral-200 px-5 py-4">
                <h2 class="font-semibold">Últimas tasaciones</h2>
            </header>
            @forelse ($ultimasTasaciones as $tasacion)
                <a href="{{ route('administracion.tasaciones.mostrar', $tasacion) }}"
                   class="block border-b border-neutral-100 px-5 py-4 hover:bg-neutral-50 last:border-b-0">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-semibold">{{ $tasacion->nombre }}</p>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $tasacion->estado_seguimiento->clasesBadge() }}">
                                    {{ $tasacion->estado_seguimiento->etiqueta() }}
                                </span>
                                @if ($tasacion->leida_en === null)
                                    <span class="bg-white px-2 py-1 text-xs font-semibold text-sky-700 ring-1 ring-inset ring-sky-100">
                                        Sin leer
                                    </span>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-neutral-600">
                                Tasación · {{ $tasacion->tipoPropiedad?->nombre ?? 'Sin tipo indicado' }}
                            </p>
                            <p class="mt-1 text-sm text-neutral-600">
                                {{ $tasacion->ubicacion_texto }}
                            </p>
                            <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-xs text-neutral-500">
                                <span>{{ $tasacion->telefono ?: 'Sin teléfono' }}</span>
                                <span>{{ $tasacion->email ?: 'Sin email' }}</span>
                            </div>
                        </div>
                        <span class="shrink-0 text-xs text-neutral-500">
                            {{ $tasacion->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </a>
            @empty
                <p class="px-5 py-8 text-sm text-neutral-500">Todavía no se recibieron tasaciones.</p>
            @endforelse
        </article>
    </section>
@endsection
