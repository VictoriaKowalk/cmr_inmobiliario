@extends('layouts.administracion')
@section('titulo', 'Tipos de propiedad')
@section('contenido')
    <header class="property-types-heading">
        <div>
            <p class="property-types-heading__eyebrow">Configuración</p>
            <h1>Tipos de propiedad</h1>
            <p>Definí los tipos de propiedad disponibles para publicar y gestionar en tu inmobiliaria.</p>
        </div>
        <a href="{{ route('administracion.tipos-propiedad.crear') }}" class="property-types-create-action">Nuevo tipo</a>
    </header>

    <section class="property-types-list-card">
        <header class="property-types-list-card__header">
            <div>
                <h2>Tipos disponibles</h2>
                <p>Activá solo los tipos con los que trabajás actualmente.</p>
            </div>
            <p class="property-types-list-card__count">{{ $tiposPropiedad->total() }} {{ Str::plural('tipo', $tiposPropiedad->total()) }} registrados</p>
        </header>

        <form method="GET" action="{{ route('administracion.tipos-propiedad.listar') }}" class="property-types-filters">
            <label class="property-types-search">
                <span>Buscar</span>
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input name="buscar" value="{{ $busqueda }}" placeholder="Buscar por nombre" aria-label="Buscar tipo de propiedad">
            </label>
            <label class="property-types-state-filter">
                <span>Estado</span>
                <select name="estado" aria-label="Filtrar por estado">
                    <option value="todos">Todos</option>
                    <option value="activos" @selected($estado === 'activos')>Activos</option>
                    <option value="inactivos" @selected($estado === 'inactivos')>Inactivos</option>
                </select>
            </label>
            <div class="property-types-filter-actions">
                <button>Aplicar</button>
                @if ($busqueda !== '' || $estado !== 'todos')<a href="{{ route('administracion.tipos-propiedad.listar') }}">Limpiar</a>@endif
            </div>
        </form>

        <div class="property-types-table-wrap"><table class="property-types-table">
            <thead><tr><th>Nombre</th><th>Estado</th><th class="text-right">Acciones</th></tr></thead>
            <tbody>@forelse ($tiposPropiedad as $tipoPropiedad)<tr>
                <td><strong>{{ $tipoPropiedad->nombre }}</strong></td>
                <td><span @class(['property-types-status', 'is-active' => $tipoPropiedad->activo, 'is-inactive' => ! $tipoPropiedad->activo])><i></i>{{ $tipoPropiedad->activo ? 'Activo' : 'Inactivo' }}</span></td>
                <td class="text-right"><form method="POST" action="{{ route('administracion.tipos-propiedad.cambiar-estado', $tipoPropiedad) }}" class="inline-block" data-property-type-status-form>@csrf @method('PATCH')<button type="button" data-property-type-status-open data-property-type-name="{{ $tipoPropiedad->nombre }}" data-property-type-action="{{ $tipoPropiedad->activo ? 'desactivar' : 'activar' }}" @class(['property-type-status-action', 'is-deactivate' => $tipoPropiedad->activo, 'is-activate' => ! $tipoPropiedad->activo])>{{ $tipoPropiedad->activo ? 'Desactivar' : 'Activar' }}</button></form></td>
            </tr>@empty<tr><td colspan="3" class="property-types-empty">No se encontraron tipos de propiedad con estos filtros.</td></tr>@endforelse</tbody>
        </table></div>
        @if ($tiposPropiedad->hasPages())<footer class="property-types-pagination">{{ $tiposPropiedad->links() }}</footer>@endif
    </section>

    <div class="user-status-modal" data-property-type-status-modal hidden>
        <button type="button" class="user-status-modal__backdrop" data-property-type-status-close aria-label="Cerrar confirmación"></button>
        <section role="dialog" aria-modal="true" aria-labelledby="property-type-modal-title" tabindex="-1">
            <span class="user-status-modal__icon is-danger" data-property-type-modal-icon>!</span>
            <h2 id="property-type-modal-title" data-property-type-modal-title></h2>
            <p data-property-type-modal-message></p>
            <div><button type="button" data-property-type-status-close>Cancelar</button><button type="button" data-property-type-status-confirm class="is-danger"></button></div>
        </section>
    </div>
@endsection
