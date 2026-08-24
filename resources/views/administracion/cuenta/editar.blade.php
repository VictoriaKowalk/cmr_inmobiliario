@extends('layouts.administracion')

@section('titulo', 'Mi empresa')

@section('contenido')
    <header class="company-heading">
        <div>
            <p class="dashboard-eyebrow">Centro de configuración</p>
            <h1>Mi empresa</h1>
            <p>Administrá las preferencias, catálogos y accesos de tu inmobiliaria.</p>
        </div>
        <div class="company-identity">
            <span>HS</span>
            <div><strong>{{ config('app.name') }}</strong><small>Cuenta administradora</small></div>
        </div>
    </header>

    <section class="company-grid" aria-label="Configuración de la empresa">
        @if (auth()->user()->esAdministrador())
        <a href="{{ route('administracion.usuarios.listar') }}" class="company-card">
            <span class="company-card__icon"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/></svg></span>
            <div><h2>Administrador de usuario</h2><p>Gestioná tus usuarios y administradores. Podés cambiar las contraseñas.</p></div>
            <span class="company-card__meta"><strong>{{ $administradoresActivos }}</strong> activos</span>
            <span class="company-card__arrow">›</span>
        </a>

        <a href="{{ route('administracion.empresa.editar') }}" class="company-card">
            <span class="company-card__icon"><svg viewBox="0 0 24 24"><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-2.8 2.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6v.2h-4V21a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1L4.2 17l.1-.1a1.7 1.7 0 0 0 .3-1.9A1.7 1.7 0 0 0 3 14H2.8v-4H3a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9L4.2 7 7 4.2l.1.1a1.7 1.7 0 0 0 1.9.3A1.7 1.7 0 0 0 10 3V2.8h4V3a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1L19.8 7l-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.2v4H21a1.7 1.7 0 0 0-1.6 1Z"/></svg></span>
            <div><h2>Datos de la empresa</h2><p>Cargá el logo de tu empresa, datos de contacto, configurá el email y la zona horaria.</p></div>
            <span class="company-card__meta">Editar información</span>
            <span class="company-card__arrow">›</span>
        </a>
        @endif

        <a href="{{ route('administracion.contactos.listar') }}" class="company-card">
            <span class="company-card__icon"><svg viewBox="0 0 24 24"><path d="M4 19V9M10 19V5M16 19v-7M22 19H2"/><path d="m3 7 6-4 6 6 6-5"/></svg></span>
            <div><h2>Oportunidades</h2><p>Accedé al flujo comercial, prioridades y responsables.</p></div>
            <span class="company-card__meta"><strong>{{ $oportunidadesAbiertas }}</strong> abiertas</span>
            <span class="company-card__arrow">›</span>
        </a>

        @if (auth()->user()->puedeSupervisar())
        <a href="{{ route('administracion.tipos-propiedad.listar') }}" class="company-card">
            <span class="company-card__icon"><svg viewBox="0 0 24 24"><path d="m3 11 9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9Z"/></svg></span>
            <div><h2>Propiedades</h2><p>Configurá con qué tipos de propiedades trabaja tu inmobiliaria.</p></div>
            <span class="company-card__meta"><strong>{{ $propiedadesTotales }}</strong> propiedades · {{ $tiposPropiedadActivos }} tipos</span>
            <span class="company-card__arrow">›</span>
        </a>

        <a href="{{ route('administracion.visitas.listar') }}" class="company-card">
            <span class="company-card__icon"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18M8 15h3M13 15h3"/></svg></span>
            <div><h2>Agenda de visitas</h2><p>Administrá reservas, asesores, horarios y resultados.</p></div>
            <span class="company-card__meta"><strong>{{ $visitasProximas }}</strong> próximas</span>
            <span class="company-card__arrow">›</span>
        </a>

        <a href="{{ route('administracion.ubicaciones.listar') }}" class="company-card">
            <span class="company-card__icon"><svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></span>
            <div><h2>Ubicaciones</h2><p>Gestioná zonas, localidades, barrios y urbanizaciones.</p></div>
            <span class="company-card__meta"><strong>{{ $ubicacionesActivas }}</strong> activas</span>
            <span class="company-card__arrow">›</span>
        </a>

        <a href="{{ route('administracion.caracteristicas.listar') }}" class="company-card">
            <span class="company-card__icon"><svg viewBox="0 0 24 24"><path d="m12 3 2.2 4.5L19 8.2l-3.5 3.4.8 4.8-4.3-2.3-4.3 2.3.8-4.8L5 8.2l4.8-.7L12 3Z"/></svg></span>
            <div><h2>Características</h2><p>Definí ambientes, servicios y amenities para las propiedades.</p></div>
            <span class="company-card__meta"><strong>{{ $caracteristicasActivas }}</strong> activas</span>
            <span class="company-card__arrow">›</span>
        </a>
        @endif

        <a href="#seguridad" class="company-card">
            <span class="company-card__icon"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg></span>
            <div><h2>Seguridad</h2><p>Consultá tu usuario y actualizá la contraseña de acceso.</p></div>
            <span class="company-card__meta">Cuenta activa</span>
            <span class="company-card__arrow">↓</span>
        </a>

        @if (auth()->user()->esAdministrador())
        <a href="{{ route('administracion.usuarios.permisos') }}" class="company-card">
            <span class="company-card__icon"><svg viewBox="0 0 24 24"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a6 6 0 0 1 12 0v2M17 11l2 2 4-4M18 5h4"/></svg></span>
            <div><h2>Roles y permisos</h2><p>Consultá la matriz de permisos para administradores, supervisores y asesores.</p></div>
            <span class="company-card__meta">Ver matriz</span><span class="company-card__arrow">›</span>
        </a>
        @endif
    </section>

    <section id="seguridad" class="company-security">
        <header>
            <div><p class="dashboard-eyebrow">Acceso y seguridad</p><h2>Tu cuenta administradora</h2><p>Actualizá la contraseña utilizada para ingresar al panel.</p></div>
            <div class="company-user"><span>{{ mb_strtoupper(mb_substr(auth()->user()->nombre, 0, 1)) }}{{ auth()->user()->apellido ? mb_strtoupper(mb_substr(auth()->user()->apellido, 0, 1)) : '' }}</span><div><strong>{{ auth()->user()->nombreCompleto() }}</strong><small>{{ auth()->user()->email }}</small></div></div>
        </header>

        <form method="POST" action="{{ route('administracion.cuenta.actualizar-contrasenia') }}" class="company-password-form">
            @csrf
            @method('PUT')
            <label>Contraseña actual<input name="contrasenia_actual" type="password" autocomplete="current-password" required>@error('contrasenia_actual')<small>{{ $message }}</small>@enderror</label>
            <label>Nueva contraseña<input name="contrasenia" type="password" autocomplete="new-password" required><span>Mínimo 10 caracteres, con mayúsculas, minúsculas y números.</span>@error('contrasenia')<small>{{ $message }}</small>@enderror</label>
            <label>Confirmar contraseña<input name="contrasenia_confirmation" type="password" autocomplete="new-password" required></label>
            <button type="submit">Guardar nueva contraseña <span>→</span></button>
        </form>
    </section>
@endsection
