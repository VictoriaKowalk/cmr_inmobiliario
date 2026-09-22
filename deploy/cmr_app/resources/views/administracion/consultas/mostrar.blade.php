@extends('layouts.administracion')

@section('titulo', 'Consulta de '.$consulta->nombre)

@section('contenido')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-700">Consulta recibida</p>
            <h1 class="mt-1 text-2xl font-semibold">{{ $consulta->nombre }}</h1>
            <p class="mt-1 text-sm text-neutral-600">{{ $consulta->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <a href="{{ route('administracion.consultas.listar') }}"
           class="inline-flex h-11 items-center justify-center border border-neutral-300 bg-white px-4 text-sm font-semibold text-neutral-800 hover:border-neutral-500">
            Volver a consultas
        </a>
        <a href="{{ route('administracion.visitas.crear', ['consulta' => $consulta]) }}"
           class="inline-flex h-11 items-center justify-center bg-emerald-700 px-4 text-sm font-semibold text-white">
            Coordinar visita
        </a>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
        <div class="space-y-6">
            <section class="border border-neutral-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $consulta->estado_seguimiento->clasesBadge() }}">
                        {{ $consulta->estado_seguimiento->etiqueta() }}
                    </span>
                    <span class="bg-neutral-100 px-2 py-1 text-xs font-semibold text-neutral-700 ring-1 ring-inset ring-neutral-200">
                        Leída
                    </span>
                </div>

                <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs uppercase text-neutral-500">Teléfono</dt>
                        <dd class="mt-1">{{ $consulta->telefono ?: 'No informado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-neutral-500">Email</dt>
                        <dd class="mt-1">{{ $consulta->email ?: 'No informado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-neutral-500">Leída</dt>
                        <dd class="mt-1">{{ $consulta->leida_en?->format('d/m/Y H:i') ?? 'No' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-neutral-500">Atendida</dt>
                        <dd class="mt-1">{{ $consulta->atendida_en?->format('d/m/Y H:i') ?? 'No registrada' }}</dd>
                    </div>
                </dl>

                <div class="mt-7 border-t border-neutral-200 pt-5">
                    <h2 class="font-semibold">Mensaje</h2>
                    <p class="mt-3 whitespace-pre-line text-sm leading-6 text-neutral-700">{{ $consulta->mensaje }}</p>
                </div>
            </section>

            <section class="border border-neutral-200 bg-white p-5 shadow-sm sm:p-6">
                <h2 class="font-semibold">Origen</h2>
                @if ($consulta->propiedad)
                    <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs uppercase text-neutral-500">Código</dt>
                            <dd class="mt-1">{{ $consulta->propiedad->codigo_interno }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-neutral-500">Operación</dt>
                            <dd class="mt-1">
                                {{ $consulta->operacionPropiedad?->tipo_operacion?->value
                                    ? ucfirst(str_replace('_', ' ', $consulta->operacionPropiedad->tipo_operacion->value))
                                    : 'No indicada' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs uppercase text-neutral-500">Propiedad</dt>
                            <dd class="mt-1">
                                @if ($consulta->propiedad->trashed())
                                    {{ $consulta->propiedad->titulo }} <span class="text-xs text-neutral-500">(eliminada del panel activo)</span>
                                @else
                                    <a href="{{ route('administracion.propiedades.mostrar', $consulta->propiedad) }}"
                                       class="font-medium text-emerald-700 hover:text-emerald-900">
                                    {{ $consulta->propiedad->titulo }}
                                    </a>
                                @endif
                            </dd>
                        </div>
                    </dl>
                @else
                    <p class="mt-3 text-sm text-neutral-600">Consulta general, sin propiedad asociada.</p>
                @endif
            </section>

            @include('administracion.contactos._historial_oportunidad', ['oportunidad' => $consulta])
        </div>

        <aside class="space-y-6">
            <section class="border border-neutral-200 bg-white p-5 shadow-sm">
                <h2 class="font-semibold">Seguimiento</h2>
                <form method="POST"
                      action="{{ route('administracion.consultas.actualizar-seguimiento', $consulta) }}"
                      class="mt-5 space-y-4">
                    @csrf
                    @method('PATCH')

                    @include('administracion.contactos._campos_oportunidad', ['oportunidad' => $consulta])

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
