@extends('layouts.administracion')
@section('titulo', 'Nueva característica')
@section('contenido')
    <header class="features-form-heading"><a href="{{ route('administracion.caracteristicas.listar') }}">← Características</a><h1>Nueva característica</h1><p>Agregá una opción para utilizar al cargar las propiedades.</p></header>
    <form method="POST" action="{{ route('administracion.caracteristicas.guardar') }}" class="features-form-card">
        @csrf
        @include('administracion.caracteristicas._formulario', ['caracteristica' => null, 'textoBoton' => 'Crear característica'])
    </form>
@endsection
