@extends('layouts.administracion')

@section('titulo', 'Ubicaciones')

@section('contenido')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-700">Configuración</p>
            <h1 class="mt-1 text-2xl font-semibold">Ubicaciones</h1>
            <p class="mt-1 text-sm text-neutral-600">
                Administrá las rutas geográficas disponibles para las propiedades.
            </p>
        </div>
        <a href="{{ route('administracion.ubicaciones.crear') }}"
           class="inline-flex h-11 items-center justify-center bg-emerald-700 px-4 text-sm font-semibold text-white hover:bg-emerald-800">
            Nueva ubicación
        </a>
    </div>

    <form method="GET"
          action="{{ route('administracion.ubicaciones.listar') }}"
          class="mb-5 grid gap-3 border border-neutral-200 bg-white p-4 sm:grid-cols-[minmax(220px,1fr)_180px_auto]">
        <div>
            <label for="buscar" class="mb-1 block text-xs font-medium text-neutral-600">Buscar</label>
            <input id="buscar"
                   name="buscar"
                   value="{{ $busqueda }}"
                   placeholder="País, zona, localidad o barrio"
                   class="h-10 w-full border border-neutral-300 px-3 text-sm outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100">
        </div>
        <div>
            <label for="estado" class="mb-1 block text-xs font-medium text-neutral-600">Estado</label>
            <select id="estado"
                    name="estado"
                    class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm outline-none focus:border-emerald-700">
                <option value="todas" @selected($estado === 'todas')>Todas</option>
                <option value="activas" @selected($estado === 'activas')>Activas</option>
                <option value="inactivas" @selected($estado === 'inactivas')>Inactivas</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit"
                    class="h-10 bg-neutral-900 px-4 text-sm font-semibold text-white hover:bg-neutral-800">
                Filtrar
            </button>
            @if ($busqueda !== '' || $estado !== 'todas')
                <a href="{{ route('administracion.ubicaciones.listar') }}"
                   class="inline-flex h-10 items-center px-3 text-sm font-medium text-neutral-600 hover:text-neutral-950">
                    Limpiar
                </a>
            @endif
        </div>
    </form>

    <div class="overflow-hidden border border-neutral-200 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="border-b border-neutral-200 bg-neutral-50 text-xs uppercase text-neutral-500">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Ruta completa</th>
                        <th class="w-32 px-5 py-3 font-semibold">Estado</th>
                        <th class="w-56 px-5 py-3 text-right font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse ($ubicaciones as $ubicacion)
                        <tr>
                            <td class="px-5 py-4">
                                <p class="font-medium">{{ $ubicacion->nombre_completo }}</p>
                                <p class="mt-1 text-xs text-neutral-500">
                                    {{ $ubicacion->localidad ?? $ubicacion->zona ?? $ubicacion->pais }}
                                </p>
                            </td>
                            <td class="px-5 py-4">
                                <span @class([
                                    'inline-flex px-2 py-1 text-xs font-semibold',
                                    'bg-emerald-50 text-emerald-800' => $ubicacion->activa,
                                    'bg-neutral-100 text-neutral-600' => ! $ubicacion->activa,
                                ])>
                                    {{ $ubicacion->activa ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('administracion.ubicaciones.editar', $ubicacion) }}"
                                       class="font-medium text-emerald-700 hover:text-emerald-900">
                                        Editar
                                    </a>
                                    <form method="POST"
                                          action="{{ route('administracion.ubicaciones.cambiar-estado', $ubicacion) }}"
                                          onsubmit="return confirm('{{ $ubicacion->activa ? '¿Desactivar esta ubicación?' : '¿Activar esta ubicación?' }}')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="font-medium text-neutral-600 hover:text-neutral-950">
                                            {{ $ubicacion->activa ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center text-neutral-500">
                                No se encontraron ubicaciones.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($ubicaciones->hasPages())
            <div class="border-t border-neutral-200 px-5 py-4">
                {{ $ubicaciones->links() }}
            </div>
        @endif
    </div>
@endsection
