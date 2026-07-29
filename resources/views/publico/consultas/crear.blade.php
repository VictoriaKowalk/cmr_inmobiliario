@extends('layouts.publico')

@section('titulo', 'Contacto')

@section('contenido')
    <div class="grid gap-8 lg:grid-cols-[0.85fr_1.15fr]">
        <div>
            <p class="text-sm font-medium text-emerald-700">Contacto</p>
            <h1 class="mt-2 text-3xl font-semibold">Hacenos tu consulta</h1>
            <p class="mt-4 text-sm leading-6 text-neutral-600">
                Dejanos tus datos y el equipo comercial te responde desde el panel de administración.
            </p>
        </div>

        <form method="POST"
              action="{{ route('publico.consultas.guardar') }}"
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
