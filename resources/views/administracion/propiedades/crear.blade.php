@extends('layouts.administracion')

@section('titulo', 'Nueva propiedad')

@section('contenido')
    <div class="mb-6">
        <p class="text-sm font-medium text-emerald-700">Propiedades</p>
        <h1 class="mt-1 text-2xl font-semibold">Nueva propiedad</h1>
        <p class="mt-1 text-sm text-neutral-600">Completá los datos y activá al menos una operación.</p>
    </div>

    <form method="POST" action="{{ route('administracion.propiedades.guardar') }}"
          class="space-y-6">
        @csrf
        @include('administracion.propiedades._formulario', [
            'propiedad' => null,
            'textoBoton' => 'Crear propiedad',
        ])
    </form>
@endsection
