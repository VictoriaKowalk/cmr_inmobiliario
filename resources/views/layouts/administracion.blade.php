<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0b4fb4">
    <title>@yield('titulo', 'Panel') | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-panel min-h-screen antialiased">
    @php
        $empresaPanel = \App\Models\Empresa::query()->first();
        $iconos = [
            'dashboard' => '<path d="M3 13h8V3H3v10Zm0 8h8v-6H3v6Zm10 0h8V11h-8v10Zm0-18v6h8V3h-8Z"/>',
            'propiedades' => '<path d="m3 11 9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9Z"/>',
            'contactos' => '<path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z"/>',
            'visitas' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/>',
            'tipos' => '<path d="M20 13 13 20a2 2 0 0 1-3 0l-6-6a2 2 0 0 1 0-3l7-7h7a2 2 0 0 1 2 2v7Z"/><circle cx="15" cy="9" r="1"/>',
            'ubicaciones' => '<path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
            'caracteristicas' => '<path d="m12 3 2.2 4.5L19 8.2l-3.5 3.4.8 4.8-4.3-2.3-4.3 2.3.8-4.8L5 8.2l4.8-.7L12 3Z"/>',
            'usuarios' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
            'cuenta' => '<path d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-5h6v5M9 10h.01M15 10h.01"/>',
            'configuracion' => '<path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-2.8 2.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6v.2h-4V21a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1L4.2 17l.1-.1a1.7 1.7 0 0 0 .3-1.9A1.7 1.7 0 0 0 3 14H2.8v-4H3a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9L4.2 7 7 4.2l.1.1a1.7 1.7 0 0 0 1.9.3A1.7 1.7 0 0 0 10 3V2.8h4V3a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1L19.8 7l-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.2v4H21a1.7 1.7 0 0 0-1.6 1Z"/>',
        ];
        $navegacionPrincipal = [
            ['Dashboard', 'administracion.dashboard', 'administracion.dashboard', 'dashboard'],
            ['Propiedades', 'administracion.propiedades.listar', 'administracion.propiedades.*', 'propiedades'],
            ['Consultas', 'administracion.contactos.listar', ['administracion.contactos.*', 'administracion.consultas.*', 'administracion.tasaciones.*'], 'contactos'],
            ['Agenda', 'administracion.visitas.listar', 'administracion.visitas.*', 'visitas'],
        ];
        $navegacionEmpresa = [
            ['Mi empresa', 'administracion.cuenta.editar', ['administracion.cuenta.*', 'administracion.empresa.*', 'administracion.usuarios.*'], 'cuenta'],
        ];
        $navegacionConfiguracion = [];
        if (auth()->user()->puedeSupervisar()) {
            $navegacionConfiguracion = [['Configuración', 'administracion.configuracion', ['administracion.configuracion', 'administracion.tipos-propiedad.*', 'administracion.ubicaciones.*', 'administracion.caracteristicas.*'], 'configuracion']];
        }
    @endphp

    <div class="admin-shell" data-admin-shell>
        <button type="button" class="admin-overlay" data-sidebar-close aria-label="Cerrar menú"></button>

        <aside class="admin-sidebar" data-sidebar>
            <div class="admin-brand">
                <a href="{{ route('administracion.dashboard') }}" class="admin-brand__mark" aria-label="Ir al dashboard">
                    <img src="{{ asset('images/logo-noddo.png') }}" alt="NODDO">
                </a>
                <button type="button" class="admin-icon-button ml-auto lg:hidden" data-sidebar-close aria-label="Cerrar menú">
                    <svg viewBox="0 0 24 24"><path d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
            </div>

            <nav class="admin-nav" aria-label="Navegación principal">
                @foreach ($navegacionPrincipal as [$etiqueta, $ruta, $patron, $icono])
                    @php $activo = is_array($patron) ? request()->routeIs(...$patron) : request()->routeIs($patron); @endphp
                    <a href="{{ route($ruta) }}" @class(['admin-nav__link', 'is-active' => $activo]) @if($activo) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24" aria-hidden="true">{!! $iconos[$icono] !!}</svg>
                        <span>{{ $etiqueta }}</span>
                    </a>
                @endforeach

                @foreach ($navegacionEmpresa as [$etiqueta, $ruta, $patron, $icono])
                    @php $activo = is_array($patron) ? request()->routeIs(...$patron) : request()->routeIs($patron); @endphp
                    <a href="{{ route($ruta) }}" @class(['admin-nav__link', 'is-active' => $activo]) @if($activo) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24" aria-hidden="true">{!! $iconos[$icono] !!}</svg>
                        <span>{{ $etiqueta }}</span>
                    </a>
                @endforeach
                @if (count($navegacionConfiguracion))
                    @foreach ($navegacionConfiguracion as [$etiqueta, $ruta, $patron, $icono])
                        @php $activo = is_array($patron) ? request()->routeIs(...$patron) : request()->routeIs($patron); @endphp
                        <a href="{{ route($ruta) }}" @class(['admin-nav__link', 'is-active' => $activo]) @if($activo) aria-current="page" @endif>
                            <svg viewBox="0 0 24 24" aria-hidden="true">{!! $iconos[$icono] !!}</svg>
                            <span>{{ $etiqueta }}</span>
                        </a>
                    @endforeach
                @endif
            </nav>

            <div class="admin-user-card">
                <span class="admin-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->nombre, 0, 1)) }}{{ auth()->user()->apellido ? mb_strtoupper(mb_substr(auth()->user()->apellido, 0, 1)) : '' }}</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->nombreCompleto() }}</p>
                    <p class="truncate text-xs text-slate-400">{{ auth()->user()->rol->etiqueta() }}</p>
                </div>
                <form method="POST" action="{{ route('administracion.salir') }}">
                    @csrf
                    <button type="submit" class="admin-icon-button" title="Cerrar sesión" aria-label="Cerrar sesión">
                        <svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5M15 12H3M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/></svg>
                    </button>
                </form>
            </div>
        </aside>

        <div class="admin-workspace">
            <header class="admin-topbar">
                <button type="button" class="admin-icon-button lg:hidden" data-sidebar-open aria-label="Abrir menú" aria-expanded="false">
                    <svg viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
                <div class="admin-search">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                    <input type="search" placeholder="Buscar en el panel..." aria-label="Buscar en el panel" disabled>
                    <span>Próximamente</span>
                </div>
                <div class="ml-auto flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('administracion.visitas.listar') }}" class="admin-icon-button" title="Agenda" aria-label="Abrir agenda">
                        <svg viewBox="0 0 24 24">{!! $iconos['visitas'] !!}</svg>
                    </a>
                    <span class="hidden text-right sm:block">
                        <span class="block text-sm font-semibold text-white">{{ auth()->user()->nombre }}</span>
                        <span class="block text-xs text-slate-400">Panel privado</span>
                    </span>
                    <span class="admin-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->nombre, 0, 1)) }}</span>
                </div>
            </header>

            <main class="admin-content">
                @if (session('estado'))
                    <div class="admin-flash" role="status">
                        <span></span>{{ session('estado') }}
                    </div>
                @endif

                @unless (request()->routeIs('administracion.dashboard') || request()->routeIs('administracion.cuenta.editar'))
                    @php
                        $volverAConfiguracion = request()->routeIs(
                            'administracion.tipos-propiedad.*',
                            'administracion.ubicaciones.*',
                            'administracion.caracteristicas.*'
                        );
                    @endphp
                    <a href="{{ route($volverAConfiguracion ? 'administracion.configuracion' : 'administracion.dashboard') }}" class="admin-back-company">← {{ $volverAConfiguracion ? 'Configuración' : 'Dashboard' }}</a>
                @endunless

                @yield('contenido')
            </main>
        </div>
    </div>
</body>
</html>
