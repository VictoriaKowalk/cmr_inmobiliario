@extends('layouts.administracion')
@section('titulo', 'Tipos de propiedad')
@section('contenido')
    <header class="users-heading">
        <div><h1>Tipos de propiedad</h1><p>Administrá las opciones disponibles y seleccioná los tipos de propiedad con los que trabajará tu inmobiliaria.</p></div>
        <a href="{{ route('administracion.tipos-propiedad.crear') }}" class="users-new-button"><span aria-hidden="true">+</span> Nuevo tipo</a>
    </header>
    <section class="users-list-card">
        <header><div><h2>LISTADO DE TIPOS DE PROPIEDAD</h2><p>{{ $tiposPropiedad->total() }} tipos registrados</p></div></header>
        <form method="GET" action="{{ route('administracion.tipos-propiedad.listar') }}" class="users-filters">
            <div class="users-search"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input name="buscar" value="{{ $busqueda }}" placeholder="Buscar tipo de propiedad" aria-label="Buscar tipo de propiedad"></div>
            <select name="estado" aria-label="Filtrar por estado"><option value="todos">Todos los estados</option><option value="activos" @selected($estado === 'activos')>Activos</option><option value="inactivos" @selected($estado === 'inactivos')>Inactivos</option></select>
            <button>Filtrar</button>@if ($busqueda !== '' || $estado !== 'todos')<a href="{{ route('administracion.tipos-propiedad.listar') }}">Limpiar</a>@endif
        </form>
        <div class="users-table-wrap"><table class="users-table property-types-table">
            <thead><tr><th>Nombre</th><th>Estado</th><th class="text-right">Acciones</th></tr></thead>
            <tbody>@forelse ($tiposPropiedad as $tipoPropiedad)<tr>
                <td><strong>{{ $tipoPropiedad->nombre }}</strong></td>
                <td><span @class(['users-status', 'is-active' => $tipoPropiedad->activo, 'is-inactive' => ! $tipoPropiedad->activo])><i></i>{{ $tipoPropiedad->activo ? 'Activo' : 'Inactivo' }}</span></td>
                <td class="text-right"><form method="POST" action="{{ route('administracion.tipos-propiedad.cambiar-estado', $tipoPropiedad) }}" class="inline-block" data-property-type-status-form>@csrf @method('PATCH')<button type="button" data-property-type-status-open data-property-type-name="{{ $tipoPropiedad->nombre }}" data-property-type-action="{{ $tipoPropiedad->activo ? 'desactivar' : 'activar' }}" @class(['property-type-status-action', 'is-deactivate' => $tipoPropiedad->activo, 'is-activate' => ! $tipoPropiedad->activo])>{{ $tipoPropiedad->activo ? 'Desactivar' : 'Activar' }}</button></form></td>
            </tr>@empty<tr><td colspan="3" class="users-empty">No se encontraron tipos de propiedad.</td></tr>@endforelse</tbody>
        </table></div>
        @if ($tiposPropiedad->hasPages())<footer>{{ $tiposPropiedad->links() }}</footer>@endif
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
