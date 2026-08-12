@extends('layouts.administracion')

@section('titulo', 'Dashboard')

@section('contenido')
    @php
        $saludo = now()->hour < 12 ? 'Buenos días' : (now()->hour < 20 ? 'Buenas tardes' : 'Buenas noches');
        $alertasTotales = $propiedadesPublicadasSinImagen + $propiedadesPublicadasSinPortada
            + $operacionesPublicadasSinPrecio + $contactosSinLeer + $tareasVencidas;
        $puntosContactos = $tendenciaDiaria->values()->map(function ($dia, $indice) use ($tendenciaDiaria, $maximoTendencia) {
            $x = $tendenciaDiaria->count() > 1 ? $indice * 100 / ($tendenciaDiaria->count() - 1) : 50;
            $y = 92 - ($dia['contactos'] * 76 / $maximoTendencia);
            return number_format($x, 2, '.', '').','.number_format($y, 2, '.', '');
        })->implode(' ');
        $puntosVisitas = $tendenciaDiaria->values()->map(function ($dia, $indice) use ($tendenciaDiaria, $maximoTendencia) {
            $x = $tendenciaDiaria->count() > 1 ? $indice * 100 / ($tendenciaDiaria->count() - 1) : 50;
            $y = 92 - ($dia['visitas'] * 76 / $maximoTendencia);
            return number_format($x, 2, '.', '').','.number_format($y, 2, '.', '');
        })->implode(' ');
        $porcentajePublicadas = ($cantidadPublicadas + $cantidadPausadas) > 0
            ? round($cantidadPublicadas * 100 / ($cantidadPublicadas + $cantidadPausadas)) : 0;
    @endphp

    <header class="dashboard-heading">
        <div>
            <p class="dashboard-eyebrow">Resumen de actividad</p>
            <h1>{{ $saludo }}, {{ auth()->user()->nombre }}</h1>
            <p>Esto es lo que está pasando hoy en tu inmobiliaria.</p>
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
        </div>
    </header>

    <section class="dashboard-kpis" aria-label="Indicadores principales">
        <a href="{{ route('administracion.propiedades.listar', ['estado' => 'publicada']) }}" class="dashboard-kpi dashboard-kpi--mint">
            <span class="dashboard-kpi__icon">⌂</span>
            <span class="dashboard-kpi__value">{{ $cantidadPublicadas }}</span>
            <span class="dashboard-kpi__label">Propiedades publicadas</span>
            <span class="dashboard-kpi__detail">{{ $cantidadDestacadas }} destacadas</span>
        </a>
        <a href="{{ route('administracion.contactos.listar', ['lectura' => 'sin_leer']) }}" class="dashboard-kpi dashboard-kpi--blue">
            <span class="dashboard-kpi__icon">↗</span>
            <span class="dashboard-kpi__value">{{ $contactosPeriodo }}</span>
            <span class="dashboard-kpi__label">Contactos recibidos</span>
            <span class="dashboard-kpi__detail">{{ $contactosSinLeer }} sin leer</span>
        </a>
        <a href="{{ route('administracion.visitas.listar') }}" class="dashboard-kpi dashboard-kpi--violet">
            <span class="dashboard-kpi__icon">◇</span>
            <span class="dashboard-kpi__value">{{ $visitasPeriodo }}</span>
            <span class="dashboard-kpi__label">Visitas coordinadas</span>
            <span class="dashboard-kpi__detail">En el período seleccionado</span>
        </a>
        <a href="{{ route('administracion.contactos.listar') }}" class="dashboard-kpi dashboard-kpi--amber">
            <span class="dashboard-kpi__icon">◎</span>
            <span class="dashboard-kpi__value">{{ $operacionesGanadas }}</span>
            <span class="dashboard-kpi__label">Oportunidades ganadas</span>
            <span class="dashboard-kpi__detail">{{ number_format($conversionVisitaOperacion, 1, ',', '.') }}% desde visita</span>
        </a>
    </section>

    <div class="dashboard-grid dashboard-grid--main">
        <article class="dashboard-card dashboard-activity">
            <header class="dashboard-card__header">
                <div><h2>Actividad comercial</h2><p>Contactos y visitas de los últimos {{ $tendenciaDiaria->count() }} días</p></div>
                <div class="dashboard-legend"><span><i class="is-mint"></i>Contactos</span><span><i class="is-violet"></i>Visitas</span></div>
            </header>
            <div class="dashboard-line-chart" role="img" aria-label="Evolución diaria de contactos y visitas">
                <span class="grid-line" style="top: 16%"></span><span class="grid-line" style="top: 41%"></span><span class="grid-line" style="top: 66%"></span><span class="grid-line" style="top: 92%"></span>
                <svg viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <defs>
                        <linearGradient id="areaContactos" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#a9e6df" stop-opacity=".32"/><stop offset="1" stop-color="#a9e6df" stop-opacity="0"/></linearGradient>
                    </defs>
                    <polygon points="0,100 {{ $puntosContactos }} 100,100" fill="url(#areaContactos)"/>
                    <polyline points="{{ $puntosContactos }}" class="line-contactos"/>
                    <polyline points="{{ $puntosVisitas }}" class="line-visitas"/>
                </svg>
            </div>
            <footer class="dashboard-chart-axis"><span>{{ $tendenciaDiaria->first()['fecha']->format('d/m') }}</span><span>{{ $tendenciaDiaria->get((int) floor(($tendenciaDiaria->count() - 1) / 2))['fecha']->format('d/m') }}</span><span>{{ $tendenciaDiaria->last()['fecha']->format('d/m') }}</span></footer>
        </article>

        <article class="dashboard-card">
            <header class="dashboard-card__header"><div><h2>Embudo comercial</h2><p>Conversión por etapa</p></div><a href="{{ route('administracion.contactos.metricas') }}">Ver métricas</a></header>
            <div class="dashboard-funnel">
                @foreach ($embudoComercial as $indice => $fila)
                    @php $ancho = $fila['cantidad'] ? max(18, round($fila['cantidad'] * 100 / $maximoEmbudo)) : 8; @endphp
                    <div class="dashboard-funnel__row">
                        <span>{{ $fila['etapa'] }}</span><strong>{{ $fila['cantidad'] }}</strong>
                        <i><b style="width: {{ $ancho }}%; --funnel-index: {{ $indice }}"></b></i>
                    </div>
                @endforeach
            </div>
            <div class="dashboard-conversion"><strong>{{ number_format($conversionConsultaVisita, 1, ',', '.') }}%</strong><span>conversión de contacto a visita</span></div>
        </article>
    </div>

    <div class="dashboard-grid dashboard-grid--secondary">
        <article class="dashboard-card">
            <header class="dashboard-card__header"><div><h2>Propiedades con más demanda</h2><p>Ranking por consultas recibidas</p></div><a href="{{ route('administracion.propiedades.listar') }}">Ver todas</a></header>
            <div class="dashboard-ranking">
                @forelse ($demandaPropiedades as $fila)
                    <div class="dashboard-ranking__row">
                        <span class="dashboard-ranking__number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div><strong>{{ $fila['propiedad']?->titulo ?? 'Propiedad eliminada' }}</strong><small>{{ $fila['propiedad']?->codigo_interno ?? 'Sin código' }}</small></div>
                        <i><b style="width: {{ round($fila['consultas'] * 100 / $maximoDemanda) }}%"></b></i>
                        <span class="dashboard-ranking__badge">{{ $fila['consultas'] }}</span>
                    </div>
                @empty
                    <div class="dashboard-empty">Todavía no hay consultas asociadas a propiedades.</div>
                @endforelse
            </div>
        </article>

        <article class="dashboard-card dashboard-inventory">
            <header class="dashboard-card__header"><div><h2>Estado del inventario</h2><p>Disponibilidad actual</p></div></header>
            <div class="dashboard-donut" style="--value: {{ $porcentajePublicadas }}">
                <div><strong>{{ $porcentajePublicadas }}%</strong><span>publicado</span></div>
            </div>
            <div class="dashboard-inventory__legend">
                <span><i class="is-mint"></i>Publicadas <strong>{{ $cantidadPublicadas }}</strong></span>
                <span><i class="is-muted"></i>Pausadas <strong>{{ $cantidadPausadas }}</strong></span>
            </div>
        </article>

        <article class="dashboard-card">
            <header class="dashboard-card__header"><div><h2>Próximas visitas</h2><p>Agenda inmediata</p></div><a href="{{ route('administracion.visitas.listar') }}">Ver agenda</a></header>
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
        </article>
    </div>

    <section class="dashboard-alerts">
        <header><div><h2>Centro de atención</h2><p>{{ $alertasTotales ? $alertasTotales.' puntos requieren revisión' : 'Todo está al día' }}</p></div></header>
        <div>
            <a href="{{ route('administracion.contactos.listar', ['lectura' => 'sin_leer']) }}"><span class="is-blue">{{ $contactosSinLeer }}</span><div><strong>Contactos sin leer</strong><small>Responder nuevas oportunidades</small></div></a>
            <a href="{{ route('administracion.contactos.listar') }}"><span class="is-amber">{{ $tareasVencidas }}</span><div><strong>Seguimientos vencidos</strong><small>Reprogramar tareas comerciales</small></div></a>
            <a href="{{ route('administracion.propiedades.listar', ['revision' => 'sin_imagen']) }}"><span class="is-violet">{{ $propiedadesPublicadasSinImagen }}</span><div><strong>Publicadas sin imagen</strong><small>Completar contenido visual</small></div></a>
            <a href="{{ route('administracion.propiedades.listar', ['revision' => 'sin_precio']) }}"><span class="is-mint">{{ $operacionesPublicadasSinPrecio }}</span><div><strong>Operaciones sin precio</strong><small>Revisar información comercial</small></div></a>
        </div>
    </section>
@endsection
