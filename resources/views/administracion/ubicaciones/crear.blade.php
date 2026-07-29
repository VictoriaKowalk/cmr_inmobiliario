@extends('layouts.administracion')

@section('titulo', 'Nueva ubicación')

@section('contenido')
    <div class="mb-6">
        <p class="text-sm font-medium text-emerald-700">Ubicaciones</p>
        <h1 class="mt-1 text-2xl font-semibold">Nueva ubicación</h1>
    </div>

    <form method="POST"
          action="{{ route('administracion.ubicaciones.guardar') }}"
          class="max-w-4xl border border-neutral-200 bg-white p-5 sm:p-6">
        @csrf
        @include('administracion.ubicaciones._formulario', [
            'ubicacion' => null,
            'textoBoton' => 'Crear ubicación',
        ])
    </form>
@endsection
