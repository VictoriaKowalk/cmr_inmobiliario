@extends('layouts.administracion')
@section('titulo', 'Editar característica')
@section('contenido')
    <header class="features-form-heading"><a href="{{ route('administracion.caracteristicas.listar') }}">← Características</a><h1>Editar característica</h1><p>Actualizá el nombre o la categoría de esta opción.</p></header>
    <form method="POST" action="{{ route('administracion.caracteristicas.actualizar', $caracteristica) }}" class="features-form-card">
        @csrf @method('PUT')
        @include('administracion.caracteristicas._formulario', ['caracteristica' => $caracteristica, 'textoBoton' => 'Guardar cambios'])
    </form>
@endsection
