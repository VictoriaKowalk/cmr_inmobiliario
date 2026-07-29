@extends('layouts.administracion')

@section('titulo', 'Tasaciones')

@section('contenido')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-700">Contactos</p>
            <h1 class="mt-1 text-2xl font-semibold">Tasaciones</h1>
            <p class="mt-1 text-sm text-neutral-600">Solicitudes de tasación recibidas desde la web.</p>
        </div>
        <div class="inline-flex border border-neutral-200 bg-white p-1">
            <a href="{{ route('administracion.consultas.listar') }}"
               class="inline-flex h-9 items-center px-4 text-sm font-semibold text-neutral-600 hover:text-neutral-950">
                Consultas
            </a>
            <a href="{{ route('administracion.tasaciones.listar') }}"
               class="inline-flex h-9 items-center bg-neutral-950 px-4 text-sm font-semibold text-white">
                Tasaciones
            </a>
        </div>
    </div>

    <form method="GET"
          class="mb-5 grid gap-3 border border-neutral-200 bg-white p-4 shadow-sm md:grid-cols-2 xl:grid-cols-[minmax(220px,1fr)_180px_150px_auto]">
        <div>
            <label for="buscar" class="mb-1 block text-xs font-medium text-neutral-600">Buscar</label>
            <input id="buscar" name="buscar" value="{{ $busqueda }}"
                   placeholder="Nombre, teléfono, email, tipo o ubicación"
                   class="h-10 w-full border border-neutral-300 px-3 text-sm outline-none focus:border-emerald-700">
        </div>
        <div>
            <label for="estado" class="mb-1 block text-xs font-medium text-neutral-600">Estado</label>
            <select id="estado" name="estado"
                    class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm">
                <option value="todos">Todos</option>
                @foreach ($estadosSeguimiento as $estadoSeguimiento)
                    <option value="{{ $estadoSeguimiento->value }}" @selected($estado === $estadoSeguimiento->value)>
                        {{ $estadoSeguimiento->etiqueta() }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="lectura" class="mb-1 block text-xs font-medium text-neutral-600">Lectura</label>
            <select id="lectura" name="lectura"
                    class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm">
                <option value="todas" @selected($lectura === 'todas')>Todas</option>
                <option value="sin_leer" @selected($lectura === 'sin_leer')>Sin leer</option>
                <option value="leidas" @selected($lectura === 'leidas')>Leídas</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button class="h-10 bg-neutral-900 px-4 text-sm font-semibold text-white">Filtrar</button>
            <a href="{{ route('administracion.tasaciones.listar') }}"
               class="inline-flex h-10 items-center px-2 text-sm text-neutral-600">Limpiar</a>
        </div>
    </form>

    <div class="overflow-hidden border border-neutral-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px] text-left text-sm">
                <thead class="border-b border-neutral-200 bg-neutral-50 text-xs uppercase text-neutral-500">
                    <tr>
                        <th class="px-5 py-3">Contacto</th>
                        <th class="px-5 py-3">Propiedad a tasar</th>
                        <th class="px-5 py-3">Estado</th>
                        <th class="px-5 py-3">Recibida</th>
                        <th class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse ($tasaciones as $tasacion)
                        <tr @class([
                            'transition hover:bg-neutral-50',
                            'bg-sky-50/40' => $tasacion->leida_en === null,
                        ])>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    @if ($tasacion->leida_en === null)
                                        <span class="size-2 rounded-full bg-sky-500"></span>
                                    @endif
                                    <p class="font-semibold">{{ $tasacion->nombre }}</p>
                                </div>
                                <p class="mt-1 text-xs text-neutral-500">
                                    {{ $tasacion->telefono ?: 'Sin teléfono' }} · {{ $tasacion->email ?: 'Sin email' }}
                                </p>
                            </td>
                            <td class="max-w-sm px-5 py-4 text-neutral-600">
                                <p class="font-medium text-neutral-800">{{ $tasacion->tipoPropiedad?->nombre ?? 'Sin tipo indicado' }}</p>
                                <p class="mt-1 truncate">{{ $tasacion->ubicacion_texto }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $tasacion->estado_seguimiento->clasesBadge() }}">
                                    {{ $tasacion->estado_seguimiento->etiqueta() }}
                                </span>
                                @if ($tasacion->leida_en === null)
                                    <span class="ml-1 inline-flex items-center bg-white px-2 py-1 text-xs font-semibold text-sky-700 ring-1 ring-inset ring-sky-100">
                                        Sin leer
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-neutral-600">
                                {{ $tasacion->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('administracion.tasaciones.mostrar', $tasacion) }}"
                                   class="inline-flex h-9 items-center justify-center border border-neutral-300 px-3 text-sm font-semibold text-neutral-800 hover:border-emerald-700 hover:text-emerald-700">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-neutral-500">
                                No se encontraron tasaciones.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($tasaciones->hasPages())
            <div class="border-t border-neutral-200 px-5 py-4">{{ $tasaciones->links() }}</div>
        @endif
    </div>
@endsection
