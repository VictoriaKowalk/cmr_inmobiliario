<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', config('app.name')) | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-neutral-100 text-neutral-950 antialiased">
    <header class="border-b border-neutral-200 bg-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-5 py-5">
            <a href="{{ url('/') }}" class="font-semibold">{{ config('app.name') }}</a>
            <nav class="flex gap-4 text-sm font-medium text-neutral-600">
                <a href="{{ route('publico.consultas.crear') }}" class="hover:text-neutral-950">Contacto</a>
                <a href="{{ route('publico.tasaciones.crear') }}" class="hover:text-neutral-950">Tasación</a>
                <a href="{{ route('login') }}" class="hover:text-neutral-950">Panel</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-5 py-10">
        @if (session('estado'))
            <div class="mb-6 border-l-4 border-emerald-600 bg-emerald-50 p-4 text-sm text-emerald-900">
                {{ session('estado') }}
            </div>
        @endif

        @yield('contenido')
    </main>
</body>
</html>
