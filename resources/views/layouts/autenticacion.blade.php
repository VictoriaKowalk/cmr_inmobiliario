<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#10121a">
    <title>@yield('titulo', 'Administración') | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-panel min-h-screen antialiased">
    <main class="auth-shell">
        <section class="auth-showcase" aria-label="Presentación del sistema">
            <div class="auth-brand">
                <strong>NODDO</strong>
                <small>CRM inmobiliario</small>
            </div>

            <div class="auth-showcase-content">
                <div class="auth-copy">
                    <p>Gestión inmobiliaria inteligente</p>
                    <h1>Todo tu negocio,<br><span>en un solo lugar.</span></h1>
                    <p>Propiedades, oportunidades y visitas organizadas para que tu equipo pueda enfocarse en vender.</p>
                </div>

            </div>

        </section>

        <section class="auth-form-area">
            <div class="auth-mobile-brand">
                <strong>NODDO</strong>
                <small>CRM inmobiliario</small>
            </div>
            <div class="auth-form-card">
                @yield('contenido')
            </div>
        </section>
    </main>
</body>
</html>
