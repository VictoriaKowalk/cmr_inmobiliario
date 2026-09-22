@extends('layouts.autenticacion')

@section('titulo', 'Ingresar')

@section('contenido')
    <header class="auth-form-heading">
        <span>Panel administrativo</span>
        <h2>Bienvenido</h2>
        <p>Ingresá tus credenciales para acceder al CRM.</p>
    </header>

    @if (session('estado'))
        <div class="auth-message is-success" role="status">
            <span>✓</span><p>{{ session('estado') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="auth-message is-error" role="alert">
            <span>!</span><p>{{ $errors->first() }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('administracion.ingresar') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <label for="email">Correo electrónico</label>
            <div class="auth-input-wrap">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                <input id="email" name="email" type="email" value="{{ old('email') }}"
                       autocomplete="username" placeholder="nombre@inmobiliaria.com" required autofocus>
            </div>
        </div>

        <div class="auth-field">
            <label for="contrasenia">Contraseña</label>
            <div class="auth-input-wrap">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                <input id="contrasenia" name="contrasenia" type="password"
                       autocomplete="current-password" placeholder="Ingresá tu contraseña" required data-password-input>
                <button type="button" data-password-toggle aria-label="Mostrar contraseña" aria-pressed="false">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                </button>
            </div>
        </div>

        <label class="auth-remember">
            <input type="checkbox" name="recordarme" value="1" @checked(old('recordarme'))>
            <span>Mantener la sesión iniciada</span>
        </label>

        <button type="submit" class="auth-submit">
            Ingresar al panel
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
        </button>
    </form>

    <div class="auth-help">
        <span></span><p>Si no podés ingresar, contactá al administrador del sistema.</p><span></span>
    </div>
@endsection
