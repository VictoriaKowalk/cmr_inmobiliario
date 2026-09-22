@extends('layouts.administracion')
@section('titulo', 'Editar característica')
@section('contenido')
    <div class="mb-6"><h1 class="text-2xl font-semibold">Editar característica</h1></div>
    <form method="POST" action="{{ route('administracion.caracteristicas.actualizar', $caracteristica) }}" class="max-w-2xl border border-neutral-200 bg-white p-6 shadow-sm">
        @csrf @method('PUT')
        @include('administracion.caracteristicas._formulario', ['caracteristica' => $caracteristica, 'textoBoton' => 'Guardar cambios'])
    </form>
@endsection
