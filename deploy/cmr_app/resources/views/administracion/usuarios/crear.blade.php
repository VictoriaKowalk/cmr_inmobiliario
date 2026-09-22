@extends('layouts.administracion')
@section('titulo', 'Nuevo Usuario')
@section('contenido')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Nuevo Usuario</h1>
        <p class="mt-1 text-sm text-neutral-600">Creá un usuario, asignale un rol y definí su nivel de acceso al sistema.</p>
    </div>
    <form method="POST" action="{{ route('administracion.usuarios.guardar') }}"
          class="max-w-3xl border border-neutral-200 bg-white p-6 shadow-sm">
        @csrf
        @include('administracion.usuarios._formulario', [
            'usuario' => null,
            'textoBoton' => 'Crear usuario',
        ])
    </form>
@endsection
