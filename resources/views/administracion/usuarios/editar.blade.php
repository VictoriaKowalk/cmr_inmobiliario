@extends('layouts.administracion')
@section('titulo', 'Editar administrador')
@section('contenido')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Editar administrador</h1>
        <p class="mt-1 text-sm text-neutral-600">Actualizá sus datos o restablecé la contraseña.</p>
    </div>
    <form method="POST" action="{{ route('administracion.usuarios.actualizar', $usuario) }}"
          class="max-w-3xl border border-neutral-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')
        @include('administracion.usuarios._formulario', [
            'usuario' => $usuario,
            'textoBoton' => 'Guardar cambios',
        ])
    </form>
@endsection
