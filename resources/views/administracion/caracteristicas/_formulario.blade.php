@if ($errors->any())<div class="mb-5 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">{{ $errors->first() }}</div>@endif
<div class="space-y-5">
    <div>
        <label for="nombre" class="mb-2 block text-sm font-medium">Nombre</label>
        <input id="nombre" name="nombre" required maxlength="100"
               value="{{ old('nombre', $caracteristica?->nombre) }}"
               class="h-11 w-full border border-neutral-300 px-3">
    </div>
    <div>
        <label for="categoria" class="mb-2 block text-sm font-medium">Categoría</label>
        <select id="categoria" name="categoria" required class="h-11 w-full border border-neutral-300 bg-white px-3">
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->value }}" @selected(old('categoria', $caracteristica?->categoria?->value) === $categoria->value)>{{ $categoria->etiqueta() }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mt-7 flex gap-3">
    <button class="h-11 bg-emerald-700 px-5 text-sm font-semibold text-white">{{ $textoBoton }}</button>
    <a href="{{ route('administracion.caracteristicas.listar') }}" class="inline-flex h-11 items-center px-4 text-sm text-neutral-600">Cancelar</a>
</div>
