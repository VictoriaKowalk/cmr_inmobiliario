@extends('layouts.administracion')
@section('titulo', 'Nuevo administrador')
@section('contenido')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Nuevo administrador</h1>
        <p class="mt-1 text-sm text-neutral-600">Creá un nuevo acceso al panel.</p>
    </div>
    <form method="POST" action="{{ route('administracion.usuarios.guardar') }}"
          class="max-w-3xl border border-neutral-200 bg-white p-6 shadow-sm">
        @csrf
        @include('administracion.usuarios._formulario', [
            'usuario' => null,
            'textoBoton' => 'Crear administrador',
        ])
    </form>
@endsection
