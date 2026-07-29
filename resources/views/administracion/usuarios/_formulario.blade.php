@if ($errors->any())
    <div class="mb-5 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">
        {{ $errors->first() }}
    </div>
@endif

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="nombre" class="mb-2 block text-sm font-medium">Nombre</label>
        <input id="nombre" name="nombre" required maxlength="100"
               value="{{ old('nombre', $usuario?->nombre) }}"
               class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
    </div>
    <div>
        <label for="apellido" class="mb-2 block text-sm font-medium">Apellido</label>
        <input id="apellido" name="apellido" maxlength="100"
               value="{{ old('apellido', $usuario?->apellido) }}"
               class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
    </div>
    <div class="sm:col-span-2">
        <label for="email" class="mb-2 block text-sm font-medium">Correo electrónico</label>
        <input id="email" name="email" type="email" required
               value="{{ old('email', $usuario?->email) }}"
               autocomplete="off"
               class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
    </div>
    <div>
        <label for="contrasenia" class="mb-2 block text-sm font-medium">
            {{ $usuario ? 'Nueva contraseña (opcional)' : 'Contraseña inicial' }}
        </label>
        <input id="contrasenia" name="contrasenia" type="password"
               autocomplete="new-password" @required(! $usuario)
               class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
        <p class="mt-2 text-xs text-neutral-500">Mínimo 10 caracteres, mayúsculas, minúsculas y números.</p>
    </div>
    <div>
        <label for="contrasenia_confirmation" class="mb-2 block text-sm font-medium">
            Confirmar contraseña
        </label>
        <input id="contrasenia_confirmation" name="contrasenia_confirmation"
               type="password" autocomplete="new-password" @required(! $usuario)
               class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
    </div>
</div>

<div class="mt-7 flex gap-3">
    <button class="h-11 bg-emerald-700 px-5 text-sm font-semibold text-white">
        {{ $textoBoton }}
    </button>
    <a href="{{ route('administracion.usuarios.listar') }}"
       class="inline-flex h-11 items-center px-4 text-sm text-neutral-600">Cancelar</a>
</div>
