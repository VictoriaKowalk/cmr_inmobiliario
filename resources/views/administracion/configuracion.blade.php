@extends('layouts.administracion')
@section('titulo', 'Configuración')
@section('contenido')
    <header class="settings-heading"><p>Configuración</p><h1>Configuración</h1><span>Definí los catálogos y opciones que se usan al cargar tus propiedades.</span></header>
    <section class="settings-grid" aria-label="Opciones de configuración">
        <a href="{{ route('administracion.tipos-propiedad.listar') }}"><span class="settings-icon"><svg viewBox="0 0 24 24"><path d="M20 13 13 20a2 2 0 0 1-3 0l-6-6a2 2 0 0 1 0-3l7-7h7a2 2 0 0 1 2 2v7Z"/><circle cx="15" cy="9" r="1"/></svg></span><div><h2>Tipos de propiedad</h2><p>Definí los tipos de inmuebles con los que trabaja tu inmobiliaria.</p></div><i>›</i></a>
        <a href="{{ route('administracion.ubicaciones.listar') }}"><span class="settings-icon"><svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></span><div><h2>Ubicaciones</h2><p>Gestioná zonas, localidades, barrios y urbanizaciones.</p></div><i>›</i></a>
        <a href="{{ route('administracion.caracteristicas.listar') }}"><span class="settings-icon"><svg viewBox="0 0 24 24"><path d="m12 3 2.2 4.5L19 8.2l-3.5 3.4.8 4.8-4.3-2.3-4.3 2.3.8-4.8L5 8.2l4.8-.7L12 3Z"/></svg></span><div><h2>Características</h2><p>Definí ambientes, servicios, amenities y otras opciones.</p></div><i>›</i></a>
    </section>
@endsection
