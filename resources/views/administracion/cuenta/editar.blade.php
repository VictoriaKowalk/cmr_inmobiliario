@extends('layouts.administracion')
@section('titulo', 'Mi empresa')
@section('contenido')
    <header class="company-heading"><div><p class="dashboard-eyebrow">Mi empresa</p><h1>Mi empresa</h1><p>Administrá los datos, accesos y seguridad de tu inmobiliaria.</p></div><div class="company-identity"><span class="company-identity__logo">@if ($empresa->logoUrl())<img src="{{ $empresa->logoUrl() }}" alt="Logo de {{ $empresa->nombre_comercial }}">@else{{ $empresa->iniciales() ?: 'HS' }}@endif</span><div><strong>{{ $empresa->nombre_comercial }}</strong><small>Cuenta administradora</small></div></div></header>
    <section class="company-grid" aria-label="Gestión de la empresa">
        @if (auth()->user()->esAdministrador())
        <a href="{{ route('administracion.usuarios.listar') }}" class="company-card"><span class="company-card__icon"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/></svg></span><div><h2>Usuarios y equipo</h2><p>Gestioná usuarios, roles y accesos de las personas que trabajan en la inmobiliaria.</p></div><span class="company-card__meta"><strong>{{ $administradoresActivos }}</strong> activos</span><span class="company-card__arrow">›</span></a>
        <a href="{{ route('administracion.empresa.editar') }}" class="company-card"><span class="company-card__icon"><svg viewBox="0 0 24 24"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h2M14 7h2M8 11h2M14 11h2M8 15h2M14 15h2M10 21v-3h4v3"/></svg></span><div><h2>Datos de la empresa</h2><p>Actualizá el logo, los datos de contacto, el email y la zona horaria.</p></div><span class="company-card__meta">Editar información</span><span class="company-card__arrow">›</span></a>
        <a href="{{ route('administracion.usuarios.permisos') }}" class="company-card"><span class="company-card__icon"><svg viewBox="0 0 24 24"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a6 6 0 0 1 12 0v2M17 11l2 2 4-4M18 5h4"/></svg></span><div><h2>Roles y permisos</h2><p>Consultá qué puede hacer cada integrante según su rol dentro del sistema.</p></div><span class="company-card__meta">Ver matriz</span><span class="company-card__arrow">›</span></a>
        @endif
    </section>
    <section id="seguridad" class="company-security"><header><div><p class="dashboard-eyebrow">Acceso y seguridad</p><h2>Tu cuenta administradora</h2><p>Actualizá la contraseña utilizada para ingresar al panel.</p></div><div class="company-user"><span>{{ mb_strtoupper(mb_substr(auth()->user()->nombre, 0, 1)) }}{{ auth()->user()->apellido ? mb_strtoupper(mb_substr(auth()->user()->apellido, 0, 1)) : '' }}</span><div><strong>{{ auth()->user()->nombreCompleto() }}</strong><small>{{ auth()->user()->email }}</small></div></div></header>
        <form method="POST" action="{{ route('administracion.cuenta.actualizar-contrasenia') }}" class="company-password-form">@csrf @method('PUT')
            <label>Contraseña actual<input name="contrasenia_actual" type="password" autocomplete="current-password" required>@error('contrasenia_actual')<small>{{ $message }}</small>@enderror</label>
            <label>Nueva contraseña<input name="contrasenia" type="password" autocomplete="new-password" required><span>Mínimo 10 caracteres, con mayúsculas, minúsculas y números.</span>@error('contrasenia')<small>{{ $message }}</small>@enderror</label>
            <label>Confirmar contraseña<input name="contrasenia_confirmation" type="password" autocomplete="new-password" required></label>
            <button type="submit">Guardar nueva contraseña <span>→</span></button>
        </form>
    </section>
@endsection
