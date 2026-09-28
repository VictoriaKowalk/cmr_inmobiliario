@extends('layouts.administracion')
@section('titulo', 'Zonas de trabajo')
@section('contenido')
    <header class="locations-heading">
        <div>
            <a href="{{ route('administracion.ubicaciones.listar') }}" class="locations-back">← Ubicaciones</a>
            <p class="locations-heading__eyebrow">Configuración</p>
            <h1>Zonas de trabajo</h1>
            <p>Elegí las zonas donde comercializás propiedades. Cada zona incluye todas las ubicaciones que contiene.</p>
        </div>
    </header>

    <section class="coverage-card">
        <div class="coverage-card__intro">
            <h2>Agregar una zona</h2>
            <p>Podés elegir, por ejemplo, Zona Norte, un partido como Pilar o una localidad específica.</p>
        </div>
        <form method="POST" action="{{ route('administracion.ubicaciones.zonas-de-trabajo.guardar') }}" class="coverage-add-form" data-autocomplete-ubicacion data-url="{{ route('administracion.ubicaciones.buscar', ['cobertura' => 1]) }}" data-autocomplete-ayuda-inicial="Buscá una zona, partido, localidad, barrio o subbarrio." data-autocomplete-ayuda-seleccion="Esta zona y todos sus niveles inferiores estarán disponibles para cargar propiedades.">
            @csrf
            <label>
                <span>Zona o ubicación</span>
                <input type="text" autocomplete="off" placeholder="Ej.: Zona Norte o Pilar" data-autocomplete-entrada>
                <input type="hidden" name="ubicacion_id" data-autocomplete-id>
            </label>
            <div class="coverage-autocomplete-results hidden" data-autocomplete-resultados></div>
            <p class="coverage-help" data-autocomplete-ayuda>Buscá una zona, partido, localidad, barrio o subbarrio.</p>
            <button type="submit">Agregar zona</button>
        </form>
    </section>

    <section class="coverage-card coverage-card--list">
        <div class="coverage-card__intro">
            <h2>Tus zonas de trabajo</h2>
            <p>El buscador de ubicaciones para propiedades se limitará a estas zonas.</p>
        </div>
        @if ($empresa->zonasCobertura->isEmpty())
            <div class="coverage-empty"><strong>Todavía no definiste zonas de trabajo.</strong><span>Mientras no agregues una, el buscador seguirá mostrando todas las ubicaciones.</span></div>
        @else
            <ul class="coverage-list">
                @foreach ($empresa->zonasCobertura as $zona)
                    <li>
                        <div><strong>{{ $zona->nombre }}</strong><span>{{ $zona->nombre_completo }} · {{ $zona->tipoUbicacion?->nombre }}</span></div>
                        <form method="POST" action="{{ route('administracion.ubicaciones.zonas-de-trabajo.eliminar', $zona) }}">@csrf @method('DELETE')<button>Quitar</button></form>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection
