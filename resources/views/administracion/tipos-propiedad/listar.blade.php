@extends('layouts.administracion')

@section('titulo', 'Tipos de propiedad')

@section('contenido')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-700">Configuración</p>
            <h1 class="mt-1 text-2xl font-semibold">Tipos de propiedad</h1>
            <p class="mt-1 text-sm text-neutral-600">
                Administrá las opciones disponibles al cargar una propiedad.
            </p>
        </div>
        <a href="{{ route('administracion.tipos-propiedad.crear') }}"
           class="inline-flex h-11 items-center justify-center bg-emerald-700 px-4 text-sm font-semibold text-white hover:bg-emerald-800">
            Nuevo tipo
        </a>
    </div>

    <form method="GET"
          action="{{ route('administracion.tipos-propiedad.listar') }}"
          class="mb-5 grid gap-3 border border-neutral-200 bg-white p-4 sm:grid-cols-[minmax(220px,1fr)_180px_auto]">
        <div>
            <label for="buscar" class="mb-1 block text-xs font-medium text-neutral-600">Buscar</label>
            <input id="buscar"
                   name="buscar"
                   value="{{ $busqueda }}"
                   placeholder="Nombre del tipo"
                   class="h-10 w-full border border-neutral-300 px-3 text-sm outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100">
        </div>
        <div>
            <label for="estado" class="mb-1 block text-xs font-medium text-neutral-600">Estado</label>
            <select id="estado"
                    name="estado"
                    class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm outline-none focus:border-emerald-700">
                <option value="todos" @selected($estado === 'todos')>Todos</option>
                <option value="activos" @selected($estado === 'activos')>Activos</option>
                <option value="inactivos" @selected($estado === 'inactivos')>Inactivos</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit"
                    class="h-10 bg-neutral-900 px-4 text-sm font-semibold text-white hover:bg-neutral-800">
                Filtrar
            </button>
            @if ($busqueda !== '' || $estado !== 'todos')
                <a href="{{ route('administracion.tipos-propiedad.listar') }}"
                   class="inline-flex h-10 items-center px-3 text-sm font-medium text-neutral-600 hover:text-neutral-950">
                    Limpiar
                </a>
            @endif
        </div>
    </form>

    <div class="overflow-hidden border border-neutral-200 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="border-b border-neutral-200 bg-neutral-50 text-xs uppercase text-neutral-500">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Nombre</th>
                        <th class="w-32 px-5 py-3 font-semibold">Estado</th>
                        <th class="w-56 px-5 py-3 text-right font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse ($tiposPropiedad as $tipoPropiedad)
                        <tr>
                            <td class="px-5 py-4 font-medium">{{ $tipoPropiedad->nombre }}</td>
                            <td class="px-5 py-4">
                                <span @class([
                                    'inline-flex px-2 py-1 text-xs font-semibold',
                                    'bg-emerald-50 text-emerald-800' => $tipoPropiedad->activo,
                                    'bg-neutral-100 text-neutral-600' => ! $tipoPropiedad->activo,
                                ])>
                                    {{ $tipoPropiedad->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('administracion.tipos-propiedad.editar', $tipoPropiedad) }}"
                                       class="font-medium text-emerald-700 hover:text-emerald-900">
                                        Editar
                                    </a>
                                    <form method="POST"
                                          action="{{ route('administracion.tipos-propiedad.cambiar-estado', $tipoPropiedad) }}"
                                          onsubmit="return confirm('{{ $tipoPropiedad->activo ? '¿Desactivar este tipo de propiedad?' : '¿Activar este tipo de propiedad?' }}')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="font-medium text-neutral-600 hover:text-neutral-950">
                                            {{ $tipoPropiedad->activo ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center text-neutral-500">
                                No se encontraron tipos de propiedad.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tiposPropiedad->hasPages())
            <div class="border-t border-neutral-200 px-5 py-4">
                {{ $tiposPropiedad->links() }}
            </div>
        @endif
    </div>
@endsection
