@extends('layouts.administracion')

@section('titulo', 'Mi cuenta')

@section('contenido')
    <div class="mb-8">
        <h1 class="text-2xl font-semibold">Mi cuenta</h1>
        <p class="mt-1 text-sm text-neutral-600">
            Actualizá la contraseña de acceso al panel.
        </p>
    </div>

    <section class="max-w-2xl border border-neutral-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-6 border-b border-neutral-200 pb-5">
            <p class="font-semibold">{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</p>
            <p class="mt-1 text-sm text-neutral-600">{{ auth()->user()->email }}</p>
        </div>

        <form method="POST"
              action="{{ route('administracion.cuenta.actualizar-contrasenia') }}"
              class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="contrasenia_actual" class="mb-2 block text-sm font-medium">
                    Contraseña actual
                </label>
                <input id="contrasenia_actual" name="contrasenia_actual"
                       type="password" autocomplete="current-password" required
                       class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
                @error('contrasenia_actual')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contrasenia" class="mb-2 block text-sm font-medium">
                    Nueva contraseña
                </label>
                <input id="contrasenia" name="contrasenia"
                       type="password" autocomplete="new-password" required
                       class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
                <p class="mt-2 text-xs text-neutral-500">
                    Mínimo 10 caracteres, con mayúsculas, minúsculas y números.
                </p>
                @error('contrasenia')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contrasenia_confirmation" class="mb-2 block text-sm font-medium">
                    Confirmar nueva contraseña
                </label>
                <input id="contrasenia_confirmation" name="contrasenia_confirmation"
                       type="password" autocomplete="new-password" required
                       class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
            </div>

            <button type="submit"
                    class="h-11 bg-emerald-700 px-5 text-sm font-semibold text-white hover:bg-emerald-800">
                Guardar contraseña
            </button>
        </form>
    </section>
@endsection
