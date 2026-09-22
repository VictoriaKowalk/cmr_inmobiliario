<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('codigo') | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-neutral-100 p-5 text-neutral-950">
    <main class="w-full max-w-xl border border-neutral-200 bg-white p-8 text-center shadow-sm">
        <p class="text-sm font-semibold uppercase tracking-widest text-emerald-700">
            Error @yield('codigo')
        </p>
        <h1 class="mt-3 text-2xl font-semibold">@yield('titulo')</h1>
        <p class="mt-3 text-neutral-600">@yield('mensaje')</p>
        <a href="{{ url('/') }}"
           class="mt-7 inline-flex h-11 items-center bg-neutral-950 px-5 text-sm font-semibold text-white">
            Volver al inicio
        </a>
    </main>
</body>
</html>
