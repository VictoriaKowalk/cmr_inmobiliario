@if ($errors->any())
    <div class="mb-5 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">
        {{ $errors->first() }}
    </div>
@endif

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="pais" class="mb-2 block text-sm font-medium">País</label>
        <input id="pais"
               name="pais"
               list="sugerencias-pais"
               value="{{ old('pais', $ubicacion?->pais ?? 'Argentina') }}"
               maxlength="100"
               required
               autofocus
               class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100">
        <datalist id="sugerencias-pais">@foreach ($sugerenciasUbicacion['pais'] as $opcion)<option value="{{ $opcion }}">@endforeach</datalist>
    </div>

    <div>
        <label for="zona" class="mb-2 block text-sm font-medium">Zona o región</label>
        <input id="zona"
               name="zona"
               list="sugerencias-zona"
               value="{{ old('zona', $ubicacion?->zona) }}"
               maxlength="150"
               placeholder="G.B.A. Zona Norte"
               class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100">
        <datalist id="sugerencias-zona">@foreach ($sugerenciasUbicacion['zona'] as $opcion)<option value="{{ $opcion }}">@endforeach</datalist>
    </div>

    <div>
        <label for="localidad" class="mb-2 block text-sm font-medium">Localidad</label>
        <input id="localidad"
               name="localidad"
               list="sugerencias-localidad"
               value="{{ old('localidad', $ubicacion?->localidad) }}"
               maxlength="150"
               placeholder="Tigre"
               class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100">
        <datalist id="sugerencias-localidad">@foreach ($sugerenciasUbicacion['localidad'] as $opcion)<option value="{{ $opcion }}">@endforeach</datalist>
    </div>

    <div>
        <label for="categoria_barrio" class="mb-2 block text-sm font-medium">Categoría de barrio</label>
        <input id="categoria_barrio"
               name="categoria_barrio"
               list="sugerencias-categoria-barrio"
               value="{{ old('categoria_barrio', $ubicacion?->categoria_barrio) }}"
               maxlength="150"
               placeholder="Countries/B.Cerrado (Tigre)"
               class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100">
        <datalist id="sugerencias-categoria-barrio">@foreach ($sugerenciasUbicacion['categoria_barrio'] as $opcion)<option value="{{ $opcion }}">@endforeach</datalist>
    </div>

    <div>
        <label for="barrio_principal" class="mb-2 block text-sm font-medium">Barrio principal o desarrollo</label>
        <input id="barrio_principal"
               name="barrio_principal"
               list="sugerencias-barrio-principal"
               value="{{ old('barrio_principal', $ubicacion?->barrio_principal) }}"
               maxlength="150"
               placeholder="Nordelta"
               class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100">
        <datalist id="sugerencias-barrio-principal">@foreach ($sugerenciasUbicacion['barrio_principal'] as $opcion)<option value="{{ $opcion }}">@endforeach</datalist>
    </div>

    <div>
        <label for="barrio" class="mb-2 block text-sm font-medium">Barrio, sub-barrio o sector</label>
        <input id="barrio"
               name="barrio"
               list="sugerencias-barrio"
               value="{{ old('barrio', $ubicacion?->barrio) }}"
               maxlength="150"
               placeholder="El Yacht"
               class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100">
        <datalist id="sugerencias-barrio">@foreach ($sugerenciasUbicacion['barrio'] as $opcion)<option value="{{ $opcion }}">@endforeach</datalist>
    </div>
</div>

<p class="mt-5 border-l-2 border-neutral-300 pl-3 text-sm text-neutral-600">
    La ruta completa se generará automáticamente con los campos informados.
</p>

<div class="mt-7 flex flex-wrap gap-3">
    <button type="submit"
            class="inline-flex h-11 items-center bg-emerald-700 px-5 text-sm font-semibold text-white hover:bg-emerald-800">
        {{ $textoBoton }}
    </button>
    <a href="{{ route('administracion.ubicaciones.listar') }}"
       class="inline-flex h-11 items-center px-4 text-sm font-medium text-neutral-600 hover:text-neutral-950">
        Cancelar
    </a>
</div>
