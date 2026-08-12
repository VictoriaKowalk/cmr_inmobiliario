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
                <span>HS</span>
                <div><strong>{{ config('app.name') }}</strong><small>Real Estate CRM</small></div>
            </div>

            <div class="auth-copy">
                <p>Gestión inmobiliaria inteligente</p>
                <h1>Todo tu negocio,<br><span>en un solo lugar.</span></h1>
                <p>Propiedades, oportunidades y visitas organizadas para que tu equipo pueda enfocarse en vender.</p>
            </div>

            <div class="auth-preview" aria-hidden="true">
                <div class="auth-preview__sidebar"><i></i><i></i><i></i><i></i></div>
                <div class="auth-preview__content">
                    <div class="auth-preview__top"><span></span><i></i></div>
                    <div class="auth-preview__stats"><span><b>24</b><i></i></span><span><b>08</b><i></i></span><span><b>12</b><i></i></span></div>
                    <div class="auth-preview__chart"><span></span><svg viewBox="0 0 300 85" preserveAspectRatio="none"><polyline points="0,70 30,58 58,65 88,35 115,48 142,22 170,42 200,17 230,36 260,25 300,6"/></svg></div>
                </div>
            </div>

            <p class="auth-security"><span>✓</span> Acceso seguro para personal autorizado</p>
        </section>

        <section class="auth-form-area">
            <div class="auth-mobile-brand">
                <span>HS</span><strong>{{ config('app.name') }}</strong>
            </div>
            <div class="auth-form-card">
                @yield('contenido')
            </div>
            <p class="auth-footer">Sistema privado de gestión inmobiliaria</p>
        </section>
    </main>
</body>
</html>
