@extends('layouts.administracion')

@section('titulo', 'Propiedades')

@section('contenido')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-700">Administración</p>
            <h1 class="mt-1 text-2xl font-semibold">Propiedades</h1>
            <p class="mt-1 text-sm text-neutral-600">Gestioná inmuebles y sus operaciones comerciales.</p>
        </div>
        <a href="{{ route('administracion.propiedades.crear') }}"
           class="inline-flex h-11 items-center justify-center bg-emerald-700 px-4 text-sm font-semibold text-white hover:bg-emerald-800">
            Nueva propiedad
        </a>
    </div>

    <form method="GET"
          class="mb-5 grid gap-3 border border-neutral-200 bg-white p-4 shadow-sm md:grid-cols-2 xl:grid-cols-[minmax(220px,1fr)_170px_160px_190px_150px_auto]">
        <div>
            <label for="buscar" class="mb-1 block text-xs font-medium text-neutral-600">Buscar</label>
            <input id="buscar" name="buscar" value="{{ $busqueda }}"
                   placeholder="Código, título o ubicación"
                   class="h-10 w-full border border-neutral-300 px-3 text-sm outline-none focus:border-emerald-700">
        </div>
        <div>
            <label for="tipo_operacion" class="mb-1 block text-xs font-medium text-neutral-600">Operación</label>
            <select id="tipo_operacion" name="tipo_operacion"
                    class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm">
                <option value="">Todas</option>
                @foreach ($tiposOperacion as $tipo)
                    <option value="{{ $tipo->value }}" @selected($tipoOperacionSeleccionado === $tipo->value)>
                        {{ match($tipo->value) {
                            'venta' => 'Venta',
                            'alquiler' => 'Alquiler',
                            default => 'Alquiler temporal',
                        } }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="estado" class="mb-1 block text-xs font-medium text-neutral-600">Estado</label>
            <select id="estado" name="estado"
                    class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm">
                <option value="">Todos</option>
                @foreach ($estadosOperacion as $estadoOperacion)
                    <option value="{{ $estadoOperacion->value }}" @selected($estadoSeleccionado === $estadoOperacion->value)>
                        {{ ucfirst($estadoOperacion->value) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="visibilidad" class="mb-1 block text-xs font-medium text-neutral-600">Registros</label>
            <select id="visibilidad" name="visibilidad"
                    class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm">
                <option value="activas" @selected($visibilidad === 'activas')>Activas</option>
                <option value="eliminadas" @selected($visibilidad === 'eliminadas')>Eliminadas</option>
            </select>
        </div>
        <div>
            <label for="revision" class="mb-1 block text-xs font-medium text-neutral-600">Revisión</label>
            <select id="revision" name="revision"
                    class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm">
                <option value="todas" @selected($revision === 'todas')>Todas</option>
                <option value="sin_imagen" @selected($revision === 'sin_imagen')>Publicadas sin imagen</option>
                <option value="sin_portada" @selected($revision === 'sin_portada')>Publicadas sin portada</option>
                <option value="sin_precio" @selected($revision === 'sin_precio')>Publicadas sin precio</option>
                <option value="sin_operacion_publicada" @selected($revision === 'sin_operacion_publicada')>Sin operación publicada</option>
                <option value="destacadas" @selected($revision === 'destacadas')>Destacadas</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button class="h-10 bg-neutral-900 px-4 text-sm font-semibold text-white">Filtrar</button>
            <a href="{{ route('administracion.propiedades.listar') }}"
               class="inline-flex h-10 items-center px-2 text-sm text-neutral-600">Limpiar</a>
        </div>
    </form>

    <div class="overflow-hidden border border-neutral-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px] text-left text-sm">
                <thead class="border-b border-neutral-200 bg-neutral-50 text-xs uppercase text-neutral-500">
                    <tr>
                        <th class="w-32 px-5 py-3">Portada</th>
                        <th class="px-5 py-3">Propiedad</th>
                        <th class="px-5 py-3">Ubicación</th>
                        <th class="px-5 py-3">Operaciones</th>
                        <th class="px-5 py-3">Destacada</th>
                        <th class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse ($propiedades as $propiedad)
                        <tr class="transition hover:bg-neutral-50">
                            <td class="px-5 py-4">
                                <div class="aspect-[4/3] w-24 overflow-hidden border border-neutral-200 bg-neutral-100">
                                    @if ($propiedad->imagenPortada)
                                        <img src="{{ $propiedad->imagenPortada->obtenerUrlPublica() }}"
                                             alt="Portada de {{ $propiedad->titulo }}"
                                             class="h-full w-full object-cover"
                                             loading="lazy">
                                    @else
                                        <div class="flex h-full items-center justify-center px-2 text-center text-xs text-neutral-400">
                                            Sin imagen
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold">{{ $propiedad->titulo }}</p>
                                <p class="mt-1 text-xs text-neutral-500">
                                    {{ $propiedad->codigo_interno }} · {{ $propiedad->tipoPropiedad->nombre }}
                                </p>
                            </td>
                            <td class="max-w-xs px-5 py-4 text-neutral-600">
                                {{ $propiedad->ubicacion->nombre_completo }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="property-operations">
                                    @foreach ($propiedad->operaciones as $operacion)
                                        <span @class([
                                            'property-operation-badge',
                                            'is-published' => $operacion->estado->value === 'publicada',
                                            'is-paused' => $operacion->estado->value === 'pausada',
                                            'is-closed' => ! in_array($operacion->estado->value, ['publicada', 'pausada'], true),
                                        ])>
                                            <i aria-hidden="true"></i>
                                            <span>{{ str_replace('_', ' ', ucfirst($operacion->tipo_operacion->value)) }}</span>
                                            <small>{{ ucfirst($operacion->estado->value) }}</small>
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @if ($propiedad->estaDestacada())
                                    <span class="property-featured-badge"><span aria-hidden="true">★</span> Sí</span>
                                @else
                                    <span class="text-neutral-500">No</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-3">
                                    @if ($propiedad->trashed())
                                        <form method="POST" action="{{ route('administracion.propiedades.restaurar', $propiedad->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="font-medium text-emerald-700">Restaurar</button>
                                        </form>
                                    @else
                                        <a href="{{ route('administracion.propiedades.mostrar', $propiedad) }}"
                                           class="font-medium text-neutral-600">Ver</a>
                                        <a href="{{ route('administracion.propiedades.editar', $propiedad) }}"
                                           class="font-medium text-emerald-700">Editar</a>
                                        <form method="POST" action="{{ route('administracion.propiedades.cambiar-destacada', $propiedad) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="font-medium text-amber-700">
                                                {{ $propiedad->estaDestacada() ? 'Quitar destaque' : 'Destacar' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-neutral-500">
                                No se encontraron propiedades.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($propiedades->hasPages())
            <div class="border-t border-neutral-200 px-5 py-4">{{ $propiedades->links() }}</div>
        @endif
    </div>
@endsection
