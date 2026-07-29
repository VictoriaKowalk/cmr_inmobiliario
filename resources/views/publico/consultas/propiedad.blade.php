@extends('layouts.publico')

@section('titulo', 'Consulta por propiedad')

@section('contenido')
    <div class="grid gap-8 lg:grid-cols-[0.85fr_1.15fr]">
        <div>
            <p class="text-sm font-medium text-emerald-700">{{ $propiedad->codigo_interno }}</p>
            <h1 class="mt-2 text-3xl font-semibold">{{ $propiedad->titulo }}</h1>
            <p class="mt-4 text-sm leading-6 text-neutral-600">
                Consultá por esta propiedad y el contacto entra directamente al CRM del administrador.
            </p>
            @if ($propiedad->ubicacion)
                <p class="mt-4 text-sm text-neutral-500">{{ $propiedad->ubicacion->nombre_completo }}</p>
            @endif
        </div>

        <form method="POST"
              action="{{ route('publico.propiedades.consultas.guardar', $propiedad->slug) }}"
              class="border border-neutral-200 bg-white p-5 sm:p-6">
            @csrf
            <div class="absolute -left-[10000px]" aria-hidden="true">
                <label for="sitio_web">Sitio web</label>
                <input id="sitio_web" name="sitio_web" type="text"
                       tabindex="-1" autocomplete="off">
            </div>

            @if ($errors->any())
                <div class="mb-5 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="nombre" class="mb-2 block text-sm font-medium">Nombre</label>
                    <input id="nombre" name="nombre" value="{{ old('nombre') }}" required
                           class="h-11 w-full border border-neutral-300 px-3 text-sm outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}"
                           class="h-11 w-full border border-neutral-300 px-3 text-sm outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label for="telefono" class="mb-2 block text-sm font-medium">Teléfono</label>
                    <input id="telefono" name="telefono" value="{{ old('telefono') }}"
                           class="h-11 w-full border border-neutral-300 px-3 text-sm outline-none focus:border-emerald-700">
                </div>
                @if ($propiedad->operaciones->isNotEmpty())
                    <div class="sm:col-span-2">
                        <label for="operacion_propiedad_id" class="mb-2 block text-sm font-medium">Operación</label>
                        <select id="operacion_propiedad_id" name="operacion_propiedad_id"
                                class="h-11 w-full border border-neutral-300 bg-white px-3 text-sm">
                            <option value="">Consulta general sobre esta propiedad</option>
                            @foreach ($propiedad->operaciones as $operacion)
                                <option value="{{ $operacion->id }}" @selected((string) old('operacion_propiedad_id') === (string) $operacion->id)>
                                    {{ ucfirst(str_replace('_', ' ', $operacion->tipo_operacion->value)) }}
                                    @if ($operacion->precio)
                                        · {{ $operacion->moneda?->value }} {{ number_format((float) $operacion->precio, 0, ',', '.') }}
                                    @else
                                        · Consultar
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="sm:col-span-2">
                    <label for="mensaje" class="mb-2 block text-sm font-medium">Consulta</label>
                    <textarea id="mensaje" name="mensaje" rows="7" required
                              class="w-full border border-neutral-300 px-3 py-2 text-sm outline-none focus:border-emerald-700">{{ old('mensaje') }}</textarea>
                </div>
            </div>

            <button class="mt-5 h-11 w-full bg-emerald-700 px-4 text-sm font-semibold text-white hover:bg-emerald-800">
                Enviar consulta
            </button>
        </form>
    </div>
@endsection
