<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('titulo', 'Administración') | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-neutral-100 text-neutral-950 antialiased">
    <main class="grid min-h-screen lg:grid-cols-[minmax(320px,0.9fr)_minmax(480px,1.1fr)]">
        <section class="hidden bg-neutral-950 p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="text-sm font-semibold uppercase tracking-[0.18em] text-emerald-400">
                {{ config('app.name') }}
            </div>
            <div class="max-w-md">
                <p class="mb-5 text-sm font-medium text-neutral-400">Panel de administración</p>
                <h1 class="text-4xl font-semibold leading-tight">
                    Propiedades, contactos y operaciones en un solo lugar.
                </h1>
            </div>
            <p class="text-sm text-neutral-500">Acceso exclusivo para personal autorizado.</p>
        </section>

        <section class="flex items-center justify-center px-5 py-12 sm:px-10">
            <div class="w-full max-w-md">
                @yield('contenido')
            </div>
        </section>
    </main>
</body>
</html>
