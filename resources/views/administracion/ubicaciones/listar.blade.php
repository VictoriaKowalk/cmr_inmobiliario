@extends('layouts.administracion')
@section('titulo', 'Ubicaciones')
@section('contenido')
    <header class="locations-heading">
        <div>
            <p class="locations-heading__eyebrow">Configuración</p>
            <h1>Ubicaciones</h1>
            <p>Organizá las ubicaciones que usás para clasificar y publicar tus propiedades.</p>
        </div>
        <div class="locations-heading__actions">
            <a href="{{ route('administracion.ubicaciones.zonas-de-trabajo') }}" class="locations-coverage-action">Zonas de trabajo</a>
            <a href="{{ route('administracion.ubicaciones.crear', ['padre' => $padre?->id]) }}" class="locations-create-action">Agregar ubicación</a>
        </div>
    </header>

    <nav class="locations-breadcrumb" aria-label="Zonas de trabajo y ruta de ubicaciones">
        @if($zonasTrabajo->isNotEmpty())
            <span>{{ $zonasTrabajo->count() === 1 ? 'Tu zona de trabajo es' : 'Tus zonas de trabajo son' }}</span>
            <strong>{{ $zonasTrabajo->pluck('nombre')->implode(' - ') }}</strong>
        @else
            <span>Definí tus zonas de trabajo para limitar las ubicaciones disponibles.</span>
        @endif
        @if($padre)<span aria-hidden="true">/</span><strong>{{ $padre->nombre_completo }}</strong>@endif
    </nav>

    <section class="locations-list-card">
        <header class="locations-list-card__header">
            <div>
                <h2>{{ $padre ? 'Ubicaciones dentro de '.$padre->nombre : ($zonasTrabajo->isNotEmpty() ? 'Ubicaciones de tus zonas de trabajo' : 'Ubicaciones principales') }}</h2>
                <p>{{ $padre ? 'Ubicaciones contenidas en esta zona.' : ($zonasTrabajo->isNotEmpty() ? 'Mostramos todas las ubicaciones incluidas en tus zonas de trabajo.' : 'Elegí una ubicación para navegar sus niveles inferiores.') }}</p>
            </div>
            <p class="locations-list-card__count">{{ $ubicaciones->total() }} {{ $ubicaciones->total() === 1 ? 'ubicación' : 'ubicaciones' }}</p>
        </header>

        <form method="GET" class="locations-search-form">
            @if($padre)<input type="hidden" name="padre" value="{{ $padre->id }}">@endif
            <label class="locations-search">
                <span>Buscar en esta ubicación</span>
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input name="buscar" value="{{ $busqueda }}" placeholder="Buscar por nombre o ruta" aria-label="Buscar por nombre o ruta">
            </label>
            <div class="locations-search-actions"><button>Buscar</button>@if($busqueda)<a href="{{ route('administracion.ubicaciones.listar', ['padre'=>$padre?->id]) }}">Limpiar</a>@endif</div>
        </form>

        <div class="locations-table-wrap"><table class="locations-table">
            <thead><tr><th>Ubicación</th><th>Tipo</th><th class="text-right">Acciones</th></tr></thead>
            <tbody class="divide-y divide-neutral-100">@forelse($ubicaciones as $ubicacion)<tr>
                <td>
                    <span class="locations-name">{{ $ubicacion->nombre }}</span>
                    <p>{{ $ubicacion->nombre_completo }}</p>
                </td>
                <td><span class="locations-type">{{ $ubicacion->tipoUbicacion?->nombre }}</span></td>
                <td class="text-right"><div class="locations-row-actions"><a href="{{ route('administracion.ubicaciones.editar',$ubicacion) }}">Editar</a></div></td>
            </tr>@empty<tr><td colspan="3" class="locations-empty">No hay ubicaciones en este nivel.</td></tr>@endforelse</tbody>
        </table></div>
        @if($ubicaciones->hasPages())<footer class="locations-pagination">{{ $ubicaciones->links() }}</footer>@endif
    </section>
@endsection
