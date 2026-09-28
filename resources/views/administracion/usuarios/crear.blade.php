@extends('layouts.administracion')
@section('titulo', 'Nuevo Usuario')
@section('contenido')
    <header class="users-form-heading"><a href="{{ route('administracion.usuarios.listar') }}">← Usuarios y equipo</a><h1>Nuevo usuario</h1><p>Creá una cuenta, asignale un rol y definí su nivel de acceso al sistema.</p></header>
    <form method="POST" action="{{ route('administracion.usuarios.guardar') }}"
          class="users-form-card">
        @csrf
        @include('administracion.usuarios._formulario', [
            'usuario' => null,
            'textoBoton' => 'Crear usuario',
        ])
    </form>
@endsection
