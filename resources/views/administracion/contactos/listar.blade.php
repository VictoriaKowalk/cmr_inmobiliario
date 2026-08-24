@extends('layouts.administracion')

@section('titulo', 'Oportunidades')

@section('contenido')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-700">CRM inmobiliario</p>
            <h1 class="mt-1 text-2xl font-semibold">Oportunidades</h1>
            <p class="mt-1 text-sm text-neutral-600">Consultas y tasaciones organizadas por etapa, responsable y prioridad.</p>
        </div>
        <div class="flex gap-2 text-sm">
            <a href="{{ route('administracion.consultas.listar') }}" class="border border-neutral-300 px-3 py-2">Sólo consultas</a>
            <a href="{{ route('administracion.tasaciones.listar') }}" class="border border-neutral-300 px-3 py-2">Sólo tasaciones</a>
        </div>
    </div>

    <nav class="mb-6 flex border-b border-neutral-300" aria-label="Secciones de consultas">
        <a href="{{ route('administracion.contactos.listar') }}"
           class="border-b-2 border-emerald-700 px-5 py-3 text-sm font-semibold text-emerald-800"
           aria-current="page">
            Bandeja
        </a>
        @if (auth()->user()->puedeSupervisar())
        <a href="{{ route('administracion.contactos.metricas') }}"
           class="border-b-2 border-transparent px-5 py-3 text-sm font-semibold text-neutral-500 hover:text-neutral-900">
            Métricas
        </a>
        @endif
    </nav>

    <form method="GET" class="mb-5 grid gap-3 border border-neutral-200 bg-white p-4 md:grid-cols-2 xl:grid-cols-4">
        <div>
            <label class="mb-1 block text-xs font-medium">Buscar</label>
            <input name="buscar" value="{{ $busqueda }}" placeholder="Nombre, correo, teléfono o propiedad"
                   class="h-10 w-full border border-neutral-300 px-3 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium">Tipo</label>
            <select name="tipo" class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm">
                <option value="todos">Todos</option>
                <option value="consulta_general" @selected($tipo === 'consulta_general')>Consulta general</option>
                <option value="consulta_propiedad" @selected($tipo === 'consulta_propiedad')>Consulta por propiedad</option>
                <option value="tasacion" @selected($tipo === 'tasacion')>Tasación</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium">Etapa</label>
            <select name="estado" class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm">
                <option value="todos">Todas</option>
                @foreach ($estadosSeguimiento as $estadoSeguimiento)
                    <option value="{{ $estadoSeguimiento->value }}" @selected($estado === $estadoSeguimiento->value)>
                        {{ $estadoSeguimiento->etiqueta() }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium">Responsable</label>
            <select name="responsable" class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm">
                <option value="">Todos</option>
                <option value="sin_asignar" @selected($responsableId === 'sin_asignar')>Sin asignar</option>
                @foreach ($responsables as $responsable)
                    <option value="{{ $responsable->id }}" @selected((string) $responsableId === (string) $responsable->id)>
                        {{ $responsable->nombreCompleto() }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium">Prioridad</label>
            <select name="prioridad" class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm">
                <option value="todas">Todas</option>
                @foreach ($prioridades as $opcionPrioridad)
                    <option value="{{ $opcionPrioridad->value }}" @selected($prioridad === $opcionPrioridad->value)>
                        {{ $opcionPrioridad->etiqueta() }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium">Lectura</label>
            <select name="lectura" class="h-10 w-full border border-neutral-300 bg-white px-3 text-sm">
                <option value="todas">Todas</option>
                <option value="sin_leer" @selected($lectura === 'sin_leer')>Sin leer</option>
                <option value="leidas" @selected($lectura === 'leidas')>Leídas</option>
            </select>
        </div>
        <div class="flex items-end gap-2 md:col-span-2">
            <button class="h-10 bg-neutral-900 px-4 text-sm font-semibold text-white">Filtrar</button>
            <a href="{{ route('administracion.contactos.listar') }}" class="inline-flex h-10 items-center text-sm text-neutral-600">Limpiar</a>
        </div>
    </form>

    <div class="overflow-x-auto border border-neutral-200 bg-white shadow-sm">
        <table class="w-full min-w-[1240px] text-left text-sm">
            <thead class="border-b bg-neutral-50 text-xs uppercase text-neutral-500">
                <tr>
                    <th class="px-5 py-3">Contacto</th>
                    <th class="px-5 py-3">Tipo / referencia</th>
                    <th class="px-5 py-3">Etapa</th>
                    <th class="px-5 py-3">Prioridad</th>
                    <th class="px-5 py-3">Responsable / tarea</th>
                    <th class="px-5 py-3">Recibido</th>
                    <th class="px-5 py-3 text-right">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100">
                @forelse ($contactos as $contacto)
                    <tr @class(['bg-sky-50/40' => $contacto->leida_en === null])>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                @if ($contacto->leida_en === null)<span class="size-2 rounded-full bg-sky-500"></span>@endif
                                <p class="font-semibold">{{ $contacto->nombre }}</p>
                            </div>
                            <p class="mt-1 text-xs text-neutral-500">{{ $contacto->telefono ?: 'Sin teléfono' }} · {{ $contacto->email ?: 'Sin email' }}</p>
                        </td>
                        <td class="max-w-sm px-5 py-4">
                            <span class="bg-neutral-100 px-2 py-1 text-xs font-semibold">{{ $contacto->tipo_etiqueta }}</span>
                            <p class="mt-2 text-xs text-neutral-600">{{ $contacto->referencia }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $contacto->estado->clasesBadge() }}">
                                {{ $contacto->estado->etiqueta() }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-1 text-xs font-semibold {{ $contacto->prioridad->clasesBadge() }}">
                                {{ $contacto->prioridad->etiqueta() }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-medium">{{ $contacto->responsable ?: 'Sin asignar' }}</p>
                            <p class="mt-1 max-w-xs text-xs text-neutral-500">
                                {{ $contacto->proxima_tarea ?: 'Sin próxima tarea' }}
                                @if ($contacto->proxima_tarea_en) · {{ $contacto->proxima_tarea_en->format('d/m H:i') }}@endif
                            </p>
                        </td>
                        <td class="px-5 py-4 text-neutral-600">{{ $contacto->recibida_en->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-4 text-right"><a href="{{ $contacto->url }}" class="font-semibold text-emerald-700">Gestionar</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-neutral-500">No se encontraron oportunidades.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($contactos->hasPages())
            <div class="border-t px-5 py-4">{{ $contactos->links() }}</div>
        @endif
    </div>
@endsection
