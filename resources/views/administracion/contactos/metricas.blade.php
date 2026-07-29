@extends('layouts.administracion')

@section('titulo', 'Métricas comerciales')

@section('contenido')
    <div class="mb-6">
        <p class="text-sm font-medium text-emerald-700">Consultas</p>
        <h1 class="mt-1 text-2xl font-semibold">Gestión comercial</h1>
        <p class="mt-1 text-sm text-neutral-600">Seguimiento de oportunidades e indicadores de rendimiento.</p>
    </div>

    <nav class="mb-6 flex border-b border-neutral-300" aria-label="Secciones de consultas">
        <a href="{{ route('administracion.contactos.listar') }}"
           class="border-b-2 border-transparent px-5 py-3 text-sm font-semibold text-neutral-500 hover:text-neutral-900">
            Bandeja
        </a>
        <a href="{{ route('administracion.contactos.metricas') }}"
           class="border-b-2 border-emerald-700 px-5 py-3 text-sm font-semibold text-emerald-800"
           aria-current="page">
            Métricas
        </a>
    </nav>

    @include('administracion.dashboard._indicadores_comerciales')
@endsection
