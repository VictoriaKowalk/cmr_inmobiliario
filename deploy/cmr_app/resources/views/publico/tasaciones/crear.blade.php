@extends('layouts.publico')

@section('titulo', 'Tasación')

@section('contenido')
    <div class="grid gap-8 lg:grid-cols-[0.85fr_1.15fr]">
        <div>
            <p class="text-sm font-medium text-emerald-700">Tasación</p>
            <h1 class="mt-2 text-3xl font-semibold">Solicitá una tasación</h1>
            <p class="mt-4 text-sm leading-6 text-neutral-600">
                Completá los datos principales de la propiedad y el pedido entra en la bandeja privada del administrador.
            </p>
        </div>

        <form method="POST"
              action="{{ route('publico.tasaciones.guardar') }}"
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
                <div>
                    <label for="tipo_propiedad_id" class="mb-2 block text-sm font-medium">Tipo de propiedad</label>
                    <select id="tipo_propiedad_id" name="tipo_propiedad_id"
                            class="h-11 w-full border border-neutral-300 bg-white px-3 text-sm">
                        <option value="">No indicado</option>
                        @foreach ($tiposPropiedad as $tipoPropiedad)
                            <option value="{{ $tipoPropiedad->id }}" @selected((string) old('tipo_propiedad_id') === (string) $tipoPropiedad->id)>
                                {{ $tipoPropiedad->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="ubicacion_texto" class="mb-2 block text-sm font-medium">Ubicación</label>
                    <input id="ubicacion_texto" name="ubicacion_texto" value="{{ old('ubicacion_texto') }}" required
                           placeholder="Barrio, localidad o zona"
                           class="h-11 w-full border border-neutral-300 px-3 text-sm outline-none focus:border-emerald-700">
                </div>
                <div class="sm:col-span-2">
                    <label for="direccion" class="mb-2 block text-sm font-medium">Dirección</label>
                    <input id="direccion" name="direccion" value="{{ old('direccion') }}"
                           class="h-11 w-full border border-neutral-300 px-3 text-sm outline-none focus:border-emerald-700">
                </div>
                <div class="sm:col-span-2">
                    <label for="mensaje" class="mb-2 block text-sm font-medium">Comentarios</label>
                    <textarea id="mensaje" name="mensaje" rows="7"
                              class="w-full border border-neutral-300 px-3 py-2 text-sm outline-none focus:border-emerald-700">{{ old('mensaje') }}</textarea>
                </div>
            </div>

            <button class="mt-5 h-11 w-full bg-emerald-700 px-4 text-sm font-semibold text-white hover:bg-emerald-800">
                Solicitar tasación
            </button>
        </form>
    </div>
@endsection
