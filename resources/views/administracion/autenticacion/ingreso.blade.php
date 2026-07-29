@extends('layouts.autenticacion')

@section('titulo', 'Ingresar')

@section('contenido')
    <div class="mb-8 lg:hidden">
        <p class="text-sm font-semibold uppercase text-emerald-700">{{ config('app.name') }}</p>
    </div>

    <div class="mb-8">
        <h2 class="text-3xl font-semibold">Ingresar al panel</h2>
        <p class="mt-2 text-sm text-neutral-600">
            Usá tus credenciales de administración.
        </p>
    </div>

    @if (session('estado'))
        <div class="mb-5 border-l-4 border-emerald-600 bg-emerald-50 p-4 text-sm text-emerald-900">
            {{ session('estado') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('administracion.ingresar') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-medium">Correo electrónico</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required
                autofocus
                class="h-12 w-full border border-neutral-300 bg-white px-3 outline-none transition focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100">
        </div>

        <div>
            <label for="contrasenia" class="mb-2 block text-sm font-medium">Contraseña</label>
            <input id="contrasenia" name="contrasenia" type="password" autocomplete="current-password" required
                class="h-12 w-full border border-neutral-300 bg-white px-3 outline-none transition focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100">
        </div>

        <label class="flex items-center gap-3 text-sm text-neutral-700">
            <input type="checkbox" name="recordarme" value="1"
                class="size-4 border-neutral-300 text-emerald-700 focus:ring-emerald-600">
            Mantener la sesión iniciada
        </label>

        <button type="submit"
            class="h-12 w-full bg-emerald-700 px-5 text-sm font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
            Ingresar
        </button>
    </form>
@endsection