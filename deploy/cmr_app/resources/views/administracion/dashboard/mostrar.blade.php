@extends('layouts.administracion')

@section('titulo', 'Dashboard')

@section('contenido')
    <header class="dashboard-heading">
        <div>
            <p class="dashboard-eyebrow">Resumen de actividad</p>
            <h1>Lo que está pasando hoy en tu inmobiliaria.</h1>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" class="dashboard-period">
                <label><span>Desde</span><input type="date" name="desde" value="{{ $desde->toDateString() }}"></label>
                <label><span>Hasta</span><input type="date" name="hasta" value="{{ $hasta->toDateString() }}"></label>
                <button>Actualizar</button>
            </form>
            <a href="{{ route('administracion.propiedades.crear') }}" class="dashboard-primary-action">
                <span>+</span> Nueva propiedad
            </a>
            <a href="{{ route('administracion.visitas.crear') }}" class="dashboard-secondary-action">Agendar visita</a>
        </div>
    </header>

    <div class="dashboard-layout">
    <div class="dashboard-layout__main">
    <section class="dashboard-kpis dashboard-kpis--all" aria-label="Indicadores principales y tareas pendientes">
        <a href="{{ route('administracion.propiedades.listar', ['estado' => 'publicada']) }}" class="dashboard-kpi dashboard-kpi--mint">
            <span class="dashboard-kpi__heading"><span class="dashboard-kpi__icon">⌂</span><span class="dashboard-kpi__label">Propiedades publicadas</span></span>
            <span class="dashboard-kpi__value">{{ $cantidadPublicadas }}</span>
            <span class="dashboard-kpi__detail">{{ $cantidadDestacadas }} {{ $cantidadDestacadas === 1 ? 'destacada' : 'destacadas' }}</span>
        </a>
        <a href="{{ route('administracion.contactos.listar', ['lectura' => 'sin_leer']) }}" class="dashboard-kpi dashboard-kpi--blue">
            <span class="dashboard-kpi__heading"><span class="dashboard-kpi__icon">↗</span><span class="dashboard-kpi__label">Contactos recibidos</span></span>
            <span class="dashboard-kpi__value">{{ $contactosPeriodo }}</span>
            <span class="dashboard-kpi__detail">{{ $contactosSinLeer }} sin leer</span>
        </a>
        <a href="{{ route('administracion.visitas.listar') }}" class="dashboard-kpi dashboard-kpi--violet">
            <span class="dashboard-kpi__heading"><span class="dashboard-kpi__icon">◇</span><span class="dashboard-kpi__label">Visitas coordinadas</span></span>
            <span class="dashboard-kpi__value">{{ $visitasPeriodo }}</span>
            <span class="dashboard-kpi__detail">En el período seleccionado</span>
        </a>
        <a href="{{ route('administracion.contactos.listar') }}" class="dashboard-kpi dashboard-kpi--amber">
            <span class="dashboard-kpi__heading"><span class="dashboard-kpi__icon">◎</span><span class="dashboard-kpi__label">Oportunidades ganadas</span></span>
            <span class="dashboard-kpi__value">{{ $operacionesGanadas }}</span>
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
    </div>

    <aside class="dashboard-agenda dashboard-card" aria-label="Agenda de próximas visitas">
            <header class="dashboard-card__header"><div><p class="dashboard-eyebrow">Agenda</p><h2>Próximas visitas</h2><p>Tu actividad programada</p></div><a href="{{ route('administracion.visitas.listar') }}">Ver todas</a></header>
            <div class="dashboard-visits">
                @forelse ($proximasVisitas as $visita)
                    <a href="{{ route('administracion.visitas.mostrar', $visita) }}">
                        <time><strong>{{ $visita->inicio->format('d') }}</strong><span>{{ mb_strtoupper($visita->inicio->translatedFormat('M')) }}</span></time>
                        <div><strong>{{ $visita->interesado_nombre }}</strong><small>{{ $visita->propiedad->titulo }} · {{ $visita->inicio->format('H:i') }}</small></div>
                        <span>›</span>
                    </a>
                @empty
                    <div class="dashboard-empty">No hay visitas próximas coordinadas.</div>
                @endforelse
            </div>
            <a href="{{ route('administracion.visitas.crear') }}" class="dashboard-agenda__action"><span>+</span> Agendar visita</a>
    </aside>
    </div>

@endsection
