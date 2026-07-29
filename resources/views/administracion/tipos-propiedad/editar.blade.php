@extends('layouts.administracion')

@section('titulo', 'Editar tipo de propiedad')

@section('contenido')
    <div class="mb-6">
        <p class="text-sm font-medium text-emerald-700">Tipos de propiedad</p>
        <h1 class="mt-1 text-2xl font-semibold">Editar tipo</h1>
    </div>

    <form method="POST"
          action="{{ route('administracion.tipos-propiedad.actualizar', $tipoPropiedad) }}"
          class="max-w-2xl border border-neutral-200 bg-white p-5 sm:p-6">
        @csrf
        @method('PUT')
        @include('administracion.tipos-propiedad._formulario', [
            'tipoPropiedad' => $tipoPropiedad,
            'textoBoton' => 'Guardar cambios',
        ])
    </form>
@endsection
