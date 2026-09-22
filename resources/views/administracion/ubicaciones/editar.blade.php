@extends('layouts.administracion')
@section('titulo', 'Editar ubicación')
@section('contenido')
<div class="mb-6"><p class="text-sm font-medium text-emerald-700">Ubicaciones</p><h1 class="mt-1 text-2xl font-semibold">Editar ubicación</h1></div>
<form method="POST" action="{{ route('administracion.ubicaciones.actualizar',$ubicacion) }}" class="max-w-2xl border border-neutral-200 bg-white p-6">@csrf @method('PUT') @include('administracion.ubicaciones._formulario',['ubicacion'=>$ubicacion,'padre'=>null,'textoBoton'=>'Guardar cambios'])</form>
@endsection
