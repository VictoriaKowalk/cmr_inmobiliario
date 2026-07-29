<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('titulo', 'Panel') | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-neutral-100 text-neutral-950 antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[240px_1fr]">
        <aside class="border-b border-neutral-800 bg-neutral-950 text-white lg:min-h-screen lg:border-b-0 lg:border-r">
            <div class="flex h-16 items-center justify-between px-5 lg:h-20">
                <a href="{{ route('administracion.dashboard') }}" class="font-semibold">
                    {{ config('app.name') }}
                </a>
                <span class="text-xs uppercase text-emerald-400">Admin</span>
            </div>

            <nav class="flex gap-1 overflow-x-auto px-3 pb-3 lg:block lg:space-y-1 lg:pb-0">
                <a href="{{ route('administracion.dashboard') }}"
                   @class([
                       'block whitespace-nowrap border-l-2 px-4 py-3 text-sm font-medium',
                       'border-emerald-400 bg-neutral-900' => request()->routeIs('administracion.dashboard'),
                       'border-transparent text-neutral-300 hover:bg-neutral-900' => ! request()->routeIs('administracion.dashboard'),
                   ])>
                    Dashboard
                </a>
                <a href="{{ route('administracion.propiedades.listar') }}"
                   @class([
                       'block whitespace-nowrap border-l-2 px-4 py-3 text-sm font-medium',
                       'border-emerald-400 bg-neutral-900' => request()->routeIs('administracion.propiedades.*'),
                       'border-transparent text-neutral-300 hover:bg-neutral-900' => ! request()->routeIs('administracion.propiedades.*'),
                   ])>
                    Propiedades
                </a>
                <a href="{{ route('administracion.contactos.listar') }}"
                   @class([
                       'block whitespace-nowrap border-l-2 px-4 py-3 text-sm font-medium',
                       'border-emerald-400 bg-neutral-900' => request()->routeIs('administracion.contactos.*') || request()->routeIs('administracion.consultas.*') || request()->routeIs('administracion.tasaciones.*'),
                       'border-transparent text-neutral-300 hover:bg-neutral-900' => ! request()->routeIs('administracion.contactos.*') && ! request()->routeIs('administracion.consultas.*') && ! request()->routeIs('administracion.tasaciones.*'),
                   ])>
                    Consultas
                </a>
                <a href="{{ route('administracion.visitas.listar') }}"
                   @class([
                       'block whitespace-nowrap border-l-2 px-4 py-3 text-sm font-medium',
                       'border-emerald-400 bg-neutral-900' => request()->routeIs('administracion.visitas.*'),
                       'border-transparent text-neutral-300 hover:bg-neutral-900' => ! request()->routeIs('administracion.visitas.*'),
                   ])>
                    Agenda
                </a>
                <p class="px-4 pb-1 pt-5 text-xs font-semibold uppercase text-neutral-600">
                    Configuración
                </p>
                <a href="{{ route('administracion.tipos-propiedad.listar') }}"
                   @class([
                       'block whitespace-nowrap border-l-2 px-4 py-3 text-sm font-medium',
                       'border-emerald-400 bg-neutral-900' => request()->routeIs('administracion.tipos-propiedad.*'),
                       'border-transparent text-neutral-300 hover:bg-neutral-900' => ! request()->routeIs('administracion.tipos-propiedad.*'),
                   ])>
                    Tipos de propiedad
                </a>
                <a href="{{ route('administracion.ubicaciones.listar') }}"
                   @class([
                       'block whitespace-nowrap border-l-2 px-4 py-3 text-sm font-medium',
                       'border-emerald-400 bg-neutral-900' => request()->routeIs('administracion.ubicaciones.*'),
                       'border-transparent text-neutral-300 hover:bg-neutral-900' => ! request()->routeIs('administracion.ubicaciones.*'),
                   ])>
                    Ubicaciones
                </a>
                <a href="{{ route('administracion.caracteristicas.listar') }}"
                   @class([
                       'block whitespace-nowrap border-l-2 px-4 py-3 text-sm font-medium',
                       'border-emerald-400 bg-neutral-900' => request()->routeIs('administracion.caracteristicas.*'),
                       'border-transparent text-neutral-300 hover:bg-neutral-900' => ! request()->routeIs('administracion.caracteristicas.*'),
                   ])>
                    Características
                </a>
                <a href="{{ route('administracion.usuarios.listar') }}"
                   @class([
                       'block whitespace-nowrap border-l-2 px-4 py-3 text-sm font-medium',
                       'border-emerald-400 bg-neutral-900' => request()->routeIs('administracion.usuarios.*'),
                       'border-transparent text-neutral-300 hover:bg-neutral-900' => ! request()->routeIs('administracion.usuarios.*'),
                   ])>
                    Administradores
                </a>
                <a href="{{ route('administracion.cuenta.editar') }}"
                   @class([
                       'block whitespace-nowrap border-l-2 px-4 py-3 text-sm font-medium',
                       'border-emerald-400 bg-neutral-900' => request()->routeIs('administracion.cuenta.*'),
                       'border-transparent text-neutral-300 hover:bg-neutral-900' => ! request()->routeIs('administracion.cuenta.*'),
                   ])>
                    Mi cuenta
                </a>
            </nav>
        </aside>

        <div class="min-w-0">
            <header class="flex min-h-16 items-center justify-between border-b border-neutral-200 bg-white px-5 sm:px-8">
                <div>
                    <p class="text-xs font-medium uppercase text-neutral-500">Panel privado</p>
                    <p class="text-sm font-semibold">{{ auth()->user()->nombre }}</p>
                </div>
                <form method="POST" action="{{ route('administracion.salir') }}">
                    @csrf
                    <button type="submit"
                            class="text-sm font-medium text-neutral-600 hover:text-neutral-950">
                        Cerrar sesión
                    </button>
                </form>
            </header>

            <main class="p-5 sm:p-8">
                @if (session('estado'))
                    <div class="mb-6 border-l-4 border-emerald-600 bg-emerald-50 p-4 text-sm text-emerald-900">
                        {{ session('estado') }}
                    </div>
                @endif

                @yield('contenido')
            </main>
        </div>
    </div>
</body>
</html>
