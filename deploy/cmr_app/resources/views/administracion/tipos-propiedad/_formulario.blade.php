@if ($errors->any())
    <div class="mb-5 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">
        {{ $errors->first() }}
    </div>
@endif

<div>
    <label for="nombre" class="mb-2 block text-sm font-medium">Nombre</label>
    <input id="nombre"
           name="nombre"
           value="{{ old('nombre', $tipoPropiedad?->nombre) }}"
           maxlength="100"
           required
           autofocus
           class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100">
    <p class="mt-2 text-xs text-neutral-500">Ejemplo: Departamento, Casa o Local.</p>
</div>

<div class="mt-7 flex flex-wrap gap-3">
    <button type="submit"
            class="inline-flex h-11 items-center bg-emerald-700 px-5 text-sm font-semibold text-white hover:bg-emerald-800">
        {{ $textoBoton }}
    </button>
    <a href="{{ route('administracion.tipos-propiedad.listar') }}"
       class="inline-flex h-11 items-center px-4 text-sm font-medium text-neutral-600 hover:text-neutral-950">
        Cancelar
    </a>
</div>
