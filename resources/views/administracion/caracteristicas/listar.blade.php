@extends('layouts.administracion')
@section('titulo', 'Características')
@section('contenido')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div><p class="text-sm font-medium text-emerald-700">Configuración</p><h1 class="mt-1 text-2xl font-semibold">Características</h1><p class="mt-1 text-sm text-neutral-600">Servicios, ambientes, amenities y opciones de las propiedades.</p></div>
        <a href="{{ route('administracion.caracteristicas.crear') }}" class="inline-flex h-11 items-center bg-emerald-700 px-4 text-sm font-semibold text-white">Nueva característica</a>
    </div>
    <form method="GET" class="mb-5 flex flex-wrap gap-3 border border-neutral-200 bg-white p-4">
        <input name="buscar" value="{{ $busqueda }}" placeholder="Buscar" class="h-10 min-w-56 flex-1 border border-neutral-300 px-3">
        <select name="categoria" class="h-10 border border-neutral-300 bg-white px-3"><option value="">Todas las categorías</option>@foreach ($categorias as $categoria)<option value="{{ $categoria->value }}" @selected($categoriaSeleccionada === $categoria->value)>{{ $categoria->etiqueta() }}</option>@endforeach</select>
        <select name="estado" class="h-10 border border-neutral-300 bg-white px-3"><option value="todas">Todas</option><option value="activas" @selected($estado === 'activas')>Activas</option><option value="inactivas" @selected($estado === 'inactivas')>Inactivas</option></select>
        <button class="h-10 bg-neutral-900 px-4 text-sm font-semibold text-white">Filtrar</button>
    </form>
    <div class="overflow-x-auto border border-neutral-200 bg-white">
        <table class="w-full min-w-[680px] text-left text-sm"><thead class="border-b bg-neutral-50 text-xs uppercase text-neutral-500"><tr><th class="px-5 py-3">Nombre</th><th class="px-5 py-3">Categoría</th><th class="px-5 py-3">Estado</th><th class="px-5 py-3 text-right">Acciones</th></tr></thead>
            <tbody class="divide-y">@foreach ($caracteristicas as $caracteristica)<tr><td class="px-5 py-4 font-semibold">{{ $caracteristica->nombre }}</td><td class="px-5 py-4">{{ $caracteristica->categoria->etiqueta() }}</td><td class="px-5 py-4">{{ $caracteristica->activa ? 'Activa' : 'Inactiva' }}</td><td class="px-5 py-4"><div class="flex justify-end gap-3"><a href="{{ route('administracion.caracteristicas.editar', $caracteristica) }}" class="font-medium text-emerald-700">Editar</a><form method="POST" action="{{ route('administracion.caracteristicas.cambiar-estado', $caracteristica) }}">@csrf @method('PATCH')<button class="font-medium text-neutral-700">{{ $caracteristica->activa ? 'Desactivar' : 'Activar' }}</button></form></div></td></tr>@endforeach</tbody>
        </table>
        @if ($caracteristicas->hasPages())<div class="border-t px-5 py-4">{{ $caracteristicas->links() }}</div>@endif
    </div>
@endsection
