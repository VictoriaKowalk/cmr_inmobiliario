@if ($errors->any())<div class="features-form-error">{{ $errors->first() }}</div>@endif
<div class="features-form-fields">
    <div>
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" required maxlength="100"
               value="{{ old('nombre', $caracteristica?->nombre) }}"
               class="features-form-control">
    </div>
    <div>
        <label for="categoria">Categoría</label>
        <select id="categoria" name="categoria" required class="features-form-control">
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->value }}" @selected(old('categoria', $caracteristica?->categoria?->value) === $categoria->value)>{{ $categoria->etiqueta() }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="features-form-actions">
    <button>{{ $textoBoton }}</button>
    <a href="{{ route('administracion.caracteristicas.listar') }}">Cancelar</a>
</div>
