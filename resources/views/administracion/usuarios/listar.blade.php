@extends('layouts.administracion')
@section('titulo', 'Administradores')
@section('contenido')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-700">Configuración</p>
            <h1 class="mt-1 text-2xl font-semibold">Administradores</h1>
            <p class="mt-1 text-sm text-neutral-600">Gestioná quién puede ingresar al panel.</p>
        </div>
        <a href="{{ route('administracion.usuarios.crear') }}"
           class="inline-flex h-11 items-center justify-center bg-emerald-700 px-4 text-sm font-semibold text-white">
            Nuevo administrador
        </a>
    </div>
    @if ($errors->has('usuario'))
        <div class="mb-5 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">
            {{ $errors->first('usuario') }}
        </div>
    @endif
    <form method="GET" class="mb-5 flex flex-wrap gap-3 border border-neutral-200 bg-white p-4">
        <input name="buscar" value="{{ $busqueda }}" placeholder="Nombre o correo"
               class="h-10 min-w-64 flex-1 border border-neutral-300 px-3 text-sm">
        <select name="estado" class="h-10 border border-neutral-300 bg-white px-3 text-sm">
            <option value="todos">Todos</option>
            <option value="activos" @selected($estado === 'activos')>Activos</option>
            <option value="inactivos" @selected($estado === 'inactivos')>Inactivos</option>
        </select>
        <button class="h-10 bg-neutral-900 px-4 text-sm font-semibold text-white">Filtrar</button>
    </form>
    <div class="overflow-x-auto border border-neutral-200 bg-white shadow-sm">
        <table class="w-full min-w-[760px] text-left text-sm">
            <thead class="border-b bg-neutral-50 text-xs uppercase text-neutral-500">
                <tr><th class="px-5 py-3">Administrador</th><th class="px-5 py-3">Estado</th><th class="px-5 py-3">Último acceso</th><th class="px-5 py-3 text-right">Acciones</th></tr>
            </thead>
            <tbody class="divide-y divide-neutral-100">
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td class="px-5 py-4"><p class="font-semibold">{{ $usuario->nombre }} {{ $usuario->apellido }}</p><p class="mt-1 text-xs text-neutral-500">{{ $usuario->email }}</p></td>
                        <td class="px-5 py-4">{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</td>
                        <td class="px-5 py-4 text-neutral-600">{{ $usuario->ultimo_acceso_en?->format('d/m/Y H:i') ?? 'Nunca' }}</td>
                        <td class="px-5 py-4"><div class="flex justify-end gap-3">
                            <a href="{{ route('administracion.usuarios.editar', $usuario) }}" class="font-medium text-emerald-700">Editar</a>
                            <form method="POST" action="{{ route('administracion.usuarios.cambiar-estado', $usuario) }}">
                                @csrf @method('PATCH')
                                <button class="font-medium text-neutral-700" @disabled(auth()->user()->is($usuario))>
                                    {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </div></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($usuarios->hasPages())<div class="border-t px-5 py-4">{{ $usuarios->links() }}</div>@endif
    </div>
@endsection
