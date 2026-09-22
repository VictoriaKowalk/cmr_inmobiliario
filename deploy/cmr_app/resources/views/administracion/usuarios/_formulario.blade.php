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
    <div class="sm:col-span-2">
        <label for="rol" class="mb-2 block text-sm font-medium">Rol</label>
        <select id="rol" name="rol" required class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
            @foreach (\App\Enums\RolUsuario::cases() as $rol)
                <option value="{{ $rol->value }}" @selected(old('rol', $usuario?->rol?->value ?? 'asesor') === $rol->value)>{{ $rol->etiqueta() }}</option>
            @endforeach
        </select>
        <p class="mt-2 text-xs text-neutral-500">Los permisos de cada rol se pueden consultar en la matriz de roles y permisos.</p>
    </div>
    <div>
        <label for="telefono" class="mb-2 block text-sm font-medium">Teléfono</label>
        <input id="telefono" name="telefono" maxlength="50" value="{{ old('telefono', $usuario?->telefono) }}" placeholder="Ej.: 11 4000-0000" class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
    </div>
    <div>
        <label for="celular" class="mb-2 block text-sm font-medium">Celular</label>
        <input id="celular" name="celular" maxlength="50" value="{{ old('celular', $usuario?->celular) }}" placeholder="Ej.: 11 5000-0000" class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
    </div>
    <div class="sm:col-span-2">
        <label for="direccion" class="mb-2 block text-sm font-medium">Dirección</label>
        <input id="direccion" name="direccion" maxlength="255" value="{{ old('direccion', $usuario?->direccion) }}" placeholder="Calle, número, localidad" class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
    </div>
    <div>
        <label for="dni" class="mb-2 block text-sm font-medium">DNI</label>
        <input id="dni" name="dni" maxlength="20" inputmode="numeric" value="{{ old('dni', $usuario?->dni) }}" placeholder="Ej.: 30.000.000" class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
    </div>
    <div>
        <label for="fecha_nacimiento" class="mb-2 block text-sm font-medium">Fecha de nacimiento</label>
        <input id="fecha_nacimiento" name="fecha_nacimiento" type="date" max="{{ now()->toDateString() }}" value="{{ old('fecha_nacimiento', $usuario?->fecha_nacimiento?->toDateString()) }}" class="h-11 w-full border border-neutral-300 px-3 outline-none focus:border-emerald-700">
    </div>
    <div>
        <label for="contrasenia" class="mb-2 block text-sm font-medium">
            {{ $usuario ? 'Nueva contraseña (opcional)' : 'Contraseña inicial' }}
        </label>
        <div class="user-password-field">
            <input id="contrasenia" name="contrasenia" type="password"
                   autocomplete="new-password" @required(! $usuario) data-password-input
                   class="h-11 w-full border border-neutral-300 px-3 pr-12 outline-none focus:border-emerald-700">
            <button type="button" data-password-toggle aria-label="Mostrar contraseña" aria-pressed="false">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.5"/></svg>
            </button>
        </div>
        <p class="mt-2 text-xs text-neutral-500">Mínimo 10 caracteres, mayúsculas, minúsculas y números.</p>
    </div>
    <div>
        <label for="contrasenia_confirmation" class="mb-2 block text-sm font-medium">
            Confirmar contraseña
        </label>
        <div class="user-password-field">
            <input id="contrasenia_confirmation" name="contrasenia_confirmation"
                   type="password" autocomplete="new-password" @required(! $usuario) data-password-input
                   class="h-11 w-full border border-neutral-300 px-3 pr-12 outline-none focus:border-emerald-700">
            <button type="button" data-password-toggle aria-label="Mostrar contraseña" aria-pressed="false">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.5"/></svg>
            </button>
        </div>
    </div>
</div>

<div class="mt-7 flex gap-3">
    <button class="h-11 bg-emerald-700 px-5 text-sm font-semibold text-white">
        {{ $textoBoton }}
    </button>
    <a href="{{ route('administracion.usuarios.listar') }}"
       class="inline-flex h-11 items-center px-4 text-sm text-neutral-600">Cancelar</a>
</div>
