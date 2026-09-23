@extends('layouts.administracion')

@section('titulo', 'Dashboard')

@section('contenido')
    <header class="dashboard-heading">
        <div>
            <h1>Lo que está pasando hoy en tu inmobiliaria.</h1>
        </div>
        <form method="GET" class="dashboard-period" data-dashboard-period>
                <div class="dashboard-period__heading"><p class="dashboard-eyebrow">Período de análisis</p><small>Actualizá las métricas por fecha</small></div>
                <div class="dashboard-period__controls">
                    <div class="dashboard-period__dates">
                        <label><span>Desde</span><input type="date" name="desde" value="{{ $desde->toDateString() }}"></label>
                        <label><span>Hasta</span><input type="date" name="hasta" value="{{ $hasta->toDateString() }}"></label>
                    </div>
                    <div class="dashboard-period__shortcuts" aria-label="Atajos de período">
                    <button type="button" data-dashboard-period-shortcut="7">7 días</button>
                    <button type="button" data-dashboard-period-shortcut="15">15 días</button>
                    <button type="button" data-dashboard-period-shortcut="30">30 días</button>
                    </div>
                    <button type="submit" class="dashboard-period__apply dashboard-primary-action">Aplicar</button>
                </div>
        </form>
        <div class="dashboard-quick-actions">
            <p class="dashboard-eyebrow">Acciones rápidas</p>
            <a href="{{ route('administracion.propiedades.crear') }}" class="dashboard-secondary-action">
                Nueva propiedad
            </a>
            <a href="{{ route('administracion.contactos.listar') }}" class="dashboard-secondary-action">Nuevo contacto</a>
            <a href="{{ route('administracion.visitas.crear') }}" class="dashboard-secondary-action">Agendar visita</a>
        </div>
    </header>

    <div class="dashboard-layout">
    <div class="dashboard-layout__main">
    <header class="dashboard-kpis-heading"><p class="dashboard-eyebrow">Resumen de actividad</p></header>
    <section class="dashboard-kpis dashboard-kpis--all" aria-label="Indicadores clave del negocio">
        <a href="{{ route('administracion.propiedades.listar', ['estado' => 'publicada']) }}" class="dashboard-kpi dashboard-kpi--mint">
            <span class="dashboard-kpi__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 11 9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9Z"/></svg></span>
            <span class="dashboard-kpi__value">{{ $cantidadPublicadas }}</span>
            <span class="dashboard-kpi__label">Propiedades publicadas</span>
            <span class="dashboard-kpi__detail">{{ $cantidadDestacadas }} {{ $cantidadDestacadas === 1 ? 'destacada' : 'destacadas' }}</span>
        </a>
        <a href="{{ route('administracion.contactos.listar') }}" class="dashboard-kpi dashboard-kpi--blue">
            <span class="dashboard-kpi__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z"/></svg></span>
            <span class="dashboard-kpi__value">{{ $contactosPeriodo }}</span>
            <span class="dashboard-kpi__label">Contactos recibidos</span>
            <span class="dashboard-kpi__detail">{{ $contactosSinLeer }} sin leer</span>
        </a>
        <a href="{{ route('administracion.visitas.listar') }}" class="dashboard-kpi dashboard-kpi--violet">
            <span class="dashboard-kpi__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg></span>
            <span class="dashboard-kpi__value">{{ $visitasPeriodo }}</span>
            <span class="dashboard-kpi__label">Visitas coordinadas</span>
            <span class="dashboard-kpi__detail">En el período seleccionado</span>
        </a>
        <a href="{{ route('administracion.contactos.listar') }}" class="dashboard-kpi dashboard-kpi--amber">
            <span class="dashboard-kpi__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 14.8 8.7 21 9.6l-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z"/></svg></span>
            <span class="dashboard-kpi__value">{{ $operacionesGanadas }}</span>
            <span class="dashboard-kpi__label">Oportunidades ganadas</span>
            <span class="dashboard-kpi__detail">{{ number_format($conversionVisitaOperacion, 1, ',', '.') }}% desde visita</span>
        </a>
        <a href="{{ route('administracion.contactos.listar', ['lectura' => 'sin_leer']) }}" class="dashboard-kpi dashboard-kpi--blue">
            <span class="dashboard-kpi__heading"><span class="dashboard-kpi__icon">↗</span><span class="dashboard-kpi__label">Contactos sin leer</span></span><span class="dashboard-kpi__value">{{ $contactosSinLeer }}</span><span class="dashboard-kpi__detail">Responder nuevas oportunidades</span>
        </a>
        <a href="{{ route('administracion.contactos.listar', ['responsable' => 'sin_asignar']) }}" class="dashboard-kpi dashboard-kpi--violet">
            <span class="dashboard-kpi__heading"><span class="dashboard-kpi__icon">◇</span><span class="dashboard-kpi__label">Contactos sin asignar</span></span><span class="dashboard-kpi__value">{{ $contactosSinAsignar }}</span><span class="dashboard-kpi__detail">Asignar un responsable comercial</span>
        </a>
        <a href="{{ route('administracion.contactos.listar') }}" class="dashboard-kpi dashboard-kpi--amber">
            <span class="dashboard-kpi__heading"><span class="dashboard-kpi__icon">!</span><span class="dashboard-kpi__label">Seguimientos vencidos</span></span><span class="dashboard-kpi__value">{{ $tareasVencidas }}</span><span class="dashboard-kpi__detail">Retomar o reprogramar tareas</span>
        </a>
        <a href="{{ route('administracion.propiedades.listar', ['revision' => 'sin_imagen']) }}" class="dashboard-kpi dashboard-kpi--mint">
            <span class="dashboard-kpi__heading"><span class="dashboard-kpi__icon">⌂</span><span class="dashboard-kpi__label">Publicaciones incompletas</span></span><span class="dashboard-kpi__value">{{ $propiedadesPublicadasSinImagen + $operacionesPublicadasSinPrecio }}</span><span class="dashboard-kpi__detail">Revisar imágenes y precios</span>
        </a>
        <a href="{{ route('administracion.visitas.listar') }}" class="dashboard-kpi dashboard-kpi--blue">
            <span class="dashboard-kpi__heading"><span class="dashboard-kpi__icon">□</span><span class="dashboard-kpi__label">Visitas de hoy</span></span><span class="dashboard-kpi__value">{{ $visitasHoy }}</span><span class="dashboard-kpi__detail">Actividad programada para hoy</span>
        </a>
    </section>

    <section class="dashboard-attention" aria-label="Prioridades">
        <header class="dashboard-kpis-heading dashboard-attention__heading">
            <p class="dashboard-eyebrow">Prioridades</p>
        </header>
        <div class="dashboard-attention__grid">
            <a href="{{ route('administracion.contactos.listar', ['lectura' => 'sin_leer']) }}" class="dashboard-attention__item is-info">
                <span class="dashboard-attention__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></span><span><strong>{{ $contactosSinLeer }}</strong><b>Contactos sin leer</b><small>Responder nuevas oportunidades</small></span><i>›</i>
            </a>
            <a href="{{ route('administracion.contactos.listar', ['responsable' => 'sin_asignar']) }}" class="dashboard-attention__item is-blue">
                <span class="dashboard-attention__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="7" r="4"/><path d="M2 21v-2a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v2M19 8v6M16 11h6"/></svg></span><span><strong>{{ $contactosSinAsignar }}</strong><b>Contactos sin asignar</b><small>Definir responsable comercial</small></span><i>›</i>
            </a>
            <a href="{{ route('administracion.contactos.listar') }}" class="dashboard-attention__item is-warning">
                <span class="dashboard-attention__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span><span><strong>{{ $tareasVencidas }}</strong><b>Seguimientos vencidos</b><small>Retomar o reprogramar tareas</small></span><i>›</i>
            </a>
            <a href="{{ route('administracion.propiedades.listar', ['revision' => 'sin_imagen']) }}" class="dashboard-attention__item is-turquoise">
                <span class="dashboard-attention__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m21 15-5-5L5 20"/></svg></span><span><strong>{{ $propiedadesPublicadasSinImagen + $operacionesPublicadasSinPrecio }}</strong><b>Publicaciones incompletas</b><small>Revisar imágenes y precios</small></span><i>›</i>
            </a>
        </div>
    </section>
    </div>

    <section class="dashboard-agenda-section" aria-label="Agenda de próximas visitas">
        <header class="dashboard-kpis-heading dashboard-agenda__heading"><p class="dashboard-eyebrow">Agenda</p></header>
        <aside class="dashboard-agenda dashboard-card">
            <header class="dashboard-card__header"><div><h2>Hoy y próximas visitas</h2><p>Lo siguiente en tu calendario comercial</p></div><a href="{{ route('administracion.visitas.listar') }}">Ver todas</a></header>
            <div class="dashboard-visits">
                @forelse ($proximasVisitas as $visita)
                    <a href="{{ route('administracion.visitas.mostrar', $visita) }}">
                        <time><strong>{{ $visita->inicio->format('d') }}</strong><span>{{ mb_strtoupper($visita->inicio->translatedFormat('M')) }}</span></time>
                        <div><strong>{{ $visita->interesado_nombre }}</strong><small>{{ $visita->inicio->format('H:i') }} · {{ $visita->propiedad->titulo }}</small><small class="dashboard-visits__advisor">Asesor: {{ $visita->asesor?->nombreCompleto() ?? 'Sin asignar' }}</small></div>
                        <span>›</span>
                    </a>
                @empty
                    <div class="dashboard-empty">No hay visitas próximas coordinadas.</div>
                @endforelse
            </div>
            <a href="{{ route('administracion.visitas.crear') }}" class="dashboard-agenda__action"><span>+</span> Agendar visita</a>
        </aside>
    </section>
    <div class="dashboard-deep-analysis">
        @include('administracion.dashboard._analisis_dashboard', ['seccion' => 'actividad'])
    </div>
    <div class="dashboard-deep-analysis">
        @include('administracion.dashboard._analisis_dashboard', ['seccion' => 'secundario'])
    </div>
    </div>

@endsection
