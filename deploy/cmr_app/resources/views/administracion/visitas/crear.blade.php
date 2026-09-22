@extends('layouts.administracion')
@section('titulo', 'Coordinar visita')
@section('contenido')
    <div class="mb-6"><p class="text-sm font-medium text-emerald-700">Agenda comercial</p><h1 class="mt-1 text-2xl font-semibold">Coordinar visita</h1></div>
    <form method="POST" action="{{ route('administracion.visitas.guardar') }}" class="grid max-w-5xl gap-5 border border-neutral-200 bg-white p-6 shadow-sm md:grid-cols-2">
        @csrf
        @if($consulta)<input type="hidden" name="consulta_id" value="{{ $consulta->id }}">@endif
        @if($tasacion)<input type="hidden" name="tasacion_id" value="{{ $tasacion->id }}">@endif
        <div><label class="mb-2 block text-sm font-medium">Propiedad</label><select name="propiedad_id" required class="h-11 w-full border px-3"><option value="">Seleccionar</option>@foreach($propiedades as $propiedad)<option value="{{ $propiedad->id }}" @selected(old('propiedad_id', $consulta?->propiedad_id) == $propiedad->id)>{{ $propiedad->codigo_interno }} · {{ $propiedad->titulo }}</option>@endforeach</select></div>
        <div><label class="mb-2 block text-sm font-medium">Asesor</label><select name="asesor_id" required class="h-11 w-full border px-3"><option value="">Seleccionar</option>@foreach($responsables as $responsable)<option value="{{ $responsable->id }}" @selected(old('asesor_id', $consulta?->responsable_id ?? $tasacion?->responsable_id) == $responsable->id)>{{ $responsable->nombreCompleto() }}</option>@endforeach</select></div>
        <div><label class="mb-2 block text-sm font-medium">Interesado</label><input name="interesado_nombre" required value="{{ old('interesado_nombre', $consulta?->nombre ?? $tasacion?->nombre) }}" class="h-11 w-full border px-3"></div>
        <div><label class="mb-2 block text-sm font-medium">Estado inicial</label><select name="estado" class="h-11 w-full border px-3">@foreach($estadosVisita as $estadoVisita)<option value="{{ $estadoVisita->value }}">{{ $estadoVisita->etiqueta() }}</option>@endforeach</select></div>
        <div><label class="mb-2 block text-sm font-medium">Email</label><input type="email" name="interesado_email" value="{{ old('interesado_email', $consulta?->email ?? $tasacion?->email) }}" class="h-11 w-full border px-3"></div>
        <div><label class="mb-2 block text-sm font-medium">Teléfono</label><input name="interesado_telefono" value="{{ old('interesado_telefono', $consulta?->telefono ?? $tasacion?->telefono) }}" class="h-11 w-full border px-3"></div>
        <div><label class="mb-2 block text-sm font-medium">Inicio</label><input type="datetime-local" name="inicio" required value="{{ old('inicio') }}" class="h-11 w-full border px-3"></div>
        <div>
            <label class="mb-2 block text-sm font-medium">Fin <span class="font-normal text-neutral-500">(opcional)</span></label>
            <input type="datetime-local" name="fin" value="{{ old('fin') }}" class="h-11 w-full border px-3">
            <p class="mt-1 text-xs text-neutral-500">Si no se indica, se reservará una hora desde el inicio.</p>
        </div>
        <div class="md:col-span-2"><label class="mb-2 block text-sm font-medium">Lugar o punto de encuentro</label><input name="lugar" value="{{ old('lugar') }}" class="h-11 w-full border px-3"></div>
        <div class="md:col-span-2"><label class="mb-2 block text-sm font-medium">Observaciones internas</label><textarea name="observaciones" rows="4" class="w-full border px-3 py-2">{{ old('observaciones') }}</textarea></div>
        @if($errors->any())<div class="md:col-span-2 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">{{ $errors->first() }}</div>@endif
        <div class="md:col-span-2 flex gap-3"><button class="h-11 bg-emerald-700 px-5 font-semibold text-white">Coordinar visita</button><a href="{{ route('administracion.visitas.listar') }}" class="inline-flex h-11 items-center px-3">Cancelar</a></div>
    </form>
@endsection
