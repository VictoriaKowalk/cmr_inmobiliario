@extends('layouts.administracion')

@section('titulo', 'Tasación de '.$tasacion->nombre)

@section('contenido')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-700">Solicitud de tasación</p>
            <h1 class="mt-1 text-2xl font-semibold">{{ $tasacion->nombre }}</h1>
            <p class="mt-1 text-sm text-neutral-600">{{ $tasacion->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <a href="{{ route('administracion.tasaciones.listar') }}"
           class="inline-flex h-11 items-center justify-center border border-neutral-300 bg-white px-4 text-sm font-semibold text-neutral-800 hover:border-neutral-500">
            Volver a tasaciones
        </a>
        <a href="{{ route('administracion.visitas.crear', ['tasacion' => $tasacion]) }}"
           class="inline-flex h-11 items-center justify-center bg-emerald-700 px-4 text-sm font-semibold text-white">
            Coordinar visita
        </a>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
        <div class="space-y-6">
            <section class="border border-neutral-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $tasacion->estado_seguimiento->clasesBadge() }}">
                        {{ $tasacion->estado_seguimiento->etiqueta() }}
                    </span>
                    <span class="bg-neutral-100 px-2 py-1 text-xs font-semibold text-neutral-700 ring-1 ring-inset ring-neutral-200">
                        Leída
                    </span>
                </div>

                <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs uppercase text-neutral-500">Teléfono</dt>
                        <dd class="mt-1">{{ $tasacion->telefono ?: 'No informado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-neutral-500">Email</dt>
                        <dd class="mt-1">{{ $tasacion->email ?: 'No informado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-neutral-500">Leída</dt>
                        <dd class="mt-1">{{ $tasacion->leida_en?->format('d/m/Y H:i') ?? 'No' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-neutral-500">Atendida</dt>
                        <dd class="mt-1">{{ $tasacion->atendida_en?->format('d/m/Y H:i') ?? 'No registrada' }}</dd>
                    </div>
                </dl>
            </section>

            <section class="border border-neutral-200 bg-white p-5 shadow-sm sm:p-6">
                <h2 class="font-semibold">Propiedad a tasar</h2>
                <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs uppercase text-neutral-500">Tipo</dt>
                        <dd class="mt-1">{{ $tasacion->tipoPropiedad?->nombre ?? 'No indicado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-neutral-500">Ubicación</dt>
                        <dd class="mt-1">{{ $tasacion->ubicacion_texto }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs uppercase text-neutral-500">Dirección</dt>
                        <dd class="mt-1">{{ $tasacion->direccion ?: 'No informada' }}</dd>
                    </div>
                </dl>

                @if ($tasacion->mensaje)
                    <div class="mt-7 border-t border-neutral-200 pt-5">
                        <h2 class="font-semibold">Mensaje</h2>
                        <p class="mt-3 whitespace-pre-line text-sm leading-6 text-neutral-700">{{ $tasacion->mensaje }}</p>
                    </div>
                @endif
            </section>

            @include('administracion.contactos._historial_oportunidad', ['oportunidad' => $tasacion])
        </div>

        <aside class="space-y-6">
            <section class="border border-neutral-200 bg-white p-5 shadow-sm">
                <h2 class="font-semibold">Seguimiento</h2>
                <form method="POST"
                      action="{{ route('administracion.tasaciones.actualizar-seguimiento', $tasacion) }}"
                      class="mt-5 space-y-4">
                    @csrf
                    @method('PATCH')

                    @include('administracion.contactos._campos_oportunidad', ['oportunidad' => $tasacion])

                    @if ($errors->any())
                        <div class="border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <button class="h-11 w-full bg-emerald-700 px-4 text-sm font-semibold text-white hover:bg-emerald-800">
                        Guardar seguimiento
                    </button>
                </form>
            </section>
        </aside>
    </div>
@endsection
