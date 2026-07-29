@extends('layouts.administracion')
@section('titulo', 'Nueva característica')
@section('contenido')
    <div class="mb-6"><h1 class="text-2xl font-semibold">Nueva característica</h1></div>
    <form method="POST" action="{{ route('administracion.caracteristicas.guardar') }}" class="max-w-2xl border border-neutral-200 bg-white p-6 shadow-sm">
        @csrf
        @include('administracion.caracteristicas._formulario', ['caracteristica' => null, 'textoBoton' => 'Crear característica'])
    </form>
@endsection
