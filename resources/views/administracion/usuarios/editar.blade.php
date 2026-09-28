@extends('layouts.administracion')
@section('titulo', 'Editar usuario')
@section('contenido')
    <header class="users-form-heading"><a href="{{ route('administracion.usuarios.listar') }}">← Usuarios y equipo</a><h1>Editar usuario</h1><p>Actualizá sus datos, contraseña y estado de acceso.</p></header>
    <div class="users-form-stack">
        <form method="POST" action="{{ route('administracion.usuarios.actualizar', $usuario) }}" class="users-form-card">
            @csrf @method('PUT')
            @include('administracion.usuarios._formulario', ['usuario' => $usuario, 'textoBoton' => 'Guardar cambios'])
        </form>
        <section class="user-access-card">
            <div>
                <p class="user-access-card__eyebrow">Estado de acceso</p>
                <div class="user-access-card__status"><h2>{{ $usuario->nombreCompleto() }}</h2><span @class(['users-status', 'is-active' => $usuario->activo, 'is-inactive' => ! $usuario->activo])><i></i>{{ $usuario->activo ? 'Usuario activo' : 'Usuario inactivo' }}</span></div>
                <p>{{ $usuario->activo ? 'Este usuario puede ingresar al panel. Al desactivarlo perderá el acceso hasta que vuelvas a habilitarlo.' : 'Este usuario no puede ingresar al panel. Podés activarlo nuevamente con sus credenciales actuales.' }}</p>
            </div>
            @if (auth()->user()->is($usuario))
                <span class="user-access-own">No podés desactivar tu propia cuenta</span>
            @else
                <form method="POST" action="{{ route('administracion.usuarios.cambiar-estado', $usuario) }}" data-user-status-form>
                    @csrf @method('PATCH')
                    <button type="button" data-user-status-open @class(['is-deactivate' => $usuario->activo, 'is-activate' => ! $usuario->activo])>{{ $usuario->activo ? 'Desactivar usuario' : 'Activar usuario' }}</button>
                </form>
            @endif
        </section>
    </div>

    @unless (auth()->user()->is($usuario))
        <div class="user-status-modal" data-user-status-modal hidden>
            <button type="button" class="user-status-modal__backdrop" data-user-status-close aria-label="Cerrar confirmación"></button>
            <section role="dialog" aria-modal="true" aria-labelledby="user-status-modal-title" tabindex="-1">
                <span @class(['user-status-modal__icon', 'is-danger' => $usuario->activo, 'is-success' => ! $usuario->activo])>{{ $usuario->activo ? '!' : '✓' }}</span>
                <h2 id="user-status-modal-title">¿{{ $usuario->activo ? 'Desactivar' : 'Activar' }} este usuario?</h2>
                <p>{{ $usuario->activo ? 'El usuario '.$usuario->nombreCompleto().' perderá el acceso al panel inmediatamente.' : 'El usuario '.$usuario->nombreCompleto().' podrá volver a ingresar al panel.' }}</p>
                <div><button type="button" data-user-status-close>Cancelar</button><button type="button" data-user-status-confirm @class(['is-danger' => $usuario->activo, 'is-success' => ! $usuario->activo])>Sí, {{ $usuario->activo ? 'desactivar' : 'activar' }}</button></div>
            </section>
        </div>
    @endunless
@endsection
