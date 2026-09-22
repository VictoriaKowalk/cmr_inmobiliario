@extends('layouts.administracion')
@section('titulo', 'Ubicaciones')
@section('contenido')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div><p class="text-sm font-medium text-emerald-700">Configuración</p><h1 class="mt-1 text-2xl font-semibold">Ubicaciones</h1><p class="mt-1 text-sm text-neutral-600">Navegá el árbol territorial y agregá niveles personalizados.</p></div>
    <a href="{{ route('administracion.ubicaciones.crear', ['padre' => $padre?->id]) }}" class="inline-flex h-11 items-center justify-center bg-emerald-700 px-4 text-sm font-semibold text-white hover:bg-emerald-800">Agregar ubicación</a>
</div>
<div class="mb-5 border border-neutral-200 bg-white p-4 text-sm">
    <a href="{{ route('administracion.ubicaciones.listar') }}" class="text-emerald-700 hover:underline">Argentina</a>
    @if($padre) <span class="mx-2 text-neutral-400">/</span><span class="font-medium">{{ $padre->nombre_completo }}</span> @endif
</div>
<form method="GET" class="mb-5 flex gap-2"><input name="buscar" value="{{ $busqueda }}" placeholder="Buscar por nombre o ruta" class="h-10 min-w-0 flex-1 border border-neutral-300 px-3"><button class="h-10 bg-neutral-900 px-4 text-sm font-semibold text-white">Buscar</button>@if($busqueda)<a href="{{ route('administracion.ubicaciones.listar', ['padre'=>$padre?->id]) }}" class="px-3 py-2 text-sm">Limpiar</a>@endif</form>
<div class="overflow-hidden border border-neutral-200 bg-white"><table class="w-full text-left text-sm"><thead class="bg-neutral-50 text-xs uppercase text-neutral-500"><tr><th class="px-5 py-3">Ubicación</th><th class="px-5 py-3">Tipo</th><th class="px-5 py-3">Origen</th><th class="px-5 py-3">Acciones</th></tr></thead><tbody class="divide-y divide-neutral-100">@forelse($ubicaciones as $ubicacion)<tr><td class="px-5 py-4"><a href="{{ route('administracion.ubicaciones.listar', ['padre'=>$ubicacion->id]) }}" class="font-medium text-emerald-700 hover:underline">{{ $ubicacion->nombre }}</a><p class="mt-1 text-xs text-neutral-500">{{ $ubicacion->nombre_completo }}</p></td><td class="px-5 py-4">{{ $ubicacion->tipoUbicacion?->nombre }}</td><td class="px-5 py-4">{{ ucfirst($ubicacion->origen) }}</td><td class="px-5 py-4"><a class="text-emerald-700" href="{{ route('administracion.ubicaciones.editar',$ubicacion) }}">Editar</a> <a class="ml-3 text-neutral-700" href="{{ route('administracion.ubicaciones.listar',['padre'=>$ubicacion->id]) }}">Ver hijos ({{ $ubicacion->hijos_count }})</a></td></tr>@empty<tr><td colspan="4" class="px-5 py-10 text-center text-neutral-500">No hay ubicaciones en este nivel.</td></tr>@endforelse</tbody></table>@if($ubicaciones->hasPages())<div class="p-4">{{ $ubicaciones->links() }}</div>@endif</div>
@endsection
