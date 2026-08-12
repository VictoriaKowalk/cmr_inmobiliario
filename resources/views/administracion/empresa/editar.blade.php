@extends('layouts.administracion')

@section('titulo', 'Datos de la empresa')

@section('contenido')
    <header class="company-data-heading">
        <div>
            <p class="dashboard-eyebrow">Configuración básica</p>
            <h1>Datos de la empresa</h1>
            <p>Esta información identifica a la inmobiliaria dentro del sistema.</p>
        </div>
    </header>

    @if ($errors->any())
        <div class="company-form-errors" role="alert">
            <strong>Revisá los datos ingresados</strong>
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('administracion.empresa.actualizar') }}" enctype="multipart/form-data" class="company-data-layout">
        @csrf
        @method('PUT')

        <aside class="company-logo-card">
            <div class="company-logo-preview" data-company-logo-preview>
                @if ($empresa->logoUrl())
                    <img src="{{ $empresa->logoUrl() }}" alt="Logo actual de {{ $empresa->nombre_comercial }}" data-company-logo-image>
                    <span class="hidden" data-company-logo-fallback>{{ $empresa->iniciales() }}</span>
                @else
                    <img class="hidden" alt="Vista previa del logo" data-company-logo-image>
                    <span data-company-logo-fallback>{{ $empresa->iniciales() ?: 'HS' }}</span>
                @endif
            </div>
            <div><h2>Logo de la empresa</h2><p>Se mostrará en el panel administrativo. Usá una imagen cuadrada o apaisada.</p></div>
            <label class="company-logo-button">Seleccionar imagen<input type="file" name="logo" accept="image/jpeg,image/png,image/webp" data-company-logo-input></label>
            <small>JPG, PNG o WebP. Máximo 5 MB.</small>
            @if ($empresa->logo_ruta)
                <label class="company-remove-logo"><input type="checkbox" name="eliminar_logo" value="1"> Eliminar logo actual</label>
            @endif
        </aside>

        <section class="company-data-form">
            <header><h2>Información general</h2><p>Datos principales y canales de contacto.</p></header>
            <div class="company-form-grid">
                <label>Nombre comercial <span>*</span><input name="nombre_comercial" value="{{ old('nombre_comercial', $empresa->nombre_comercial) }}" maxlength="150" required></label>
                <label>Razón social<input name="razon_social" value="{{ old('razon_social', $empresa->razon_social) }}" maxlength="180" placeholder="Opcional"></label>
                <label>Correo electrónico<input name="email" type="email" value="{{ old('email', $empresa->email) }}" maxlength="255" placeholder="contacto@empresa.com"></label>
                <label>Teléfono<input name="telefono" value="{{ old('telefono', $empresa->telefono) }}" maxlength="50" placeholder="+54 11 0000-0000"></label>
                <label>WhatsApp<input name="whatsapp" value="{{ old('whatsapp', $empresa->whatsapp) }}" maxlength="50" placeholder="+54 9 11 0000-0000"></label>
                <label>Zona horaria <span>*</span><select name="zona_horaria" required>@foreach ($zonasHorarias as $valor => $etiqueta)<option value="{{ $valor }}" @selected(old('zona_horaria', $empresa->zona_horaria) === $valor)>{{ $etiqueta }}</option>@endforeach</select></label>
                <label class="company-field-wide">Dirección<input name="direccion" value="{{ old('direccion', $empresa->direccion) }}" maxlength="255" placeholder="Calle, número, localidad y provincia"></label>
            </div>
            <footer>
                <p><span>*</span> Campos obligatorios</p>
                <div><a href="{{ route('administracion.cuenta.editar') }}">Cancelar</a><button type="submit">Guardar cambios <span>→</span></button></div>
            </footer>
        </section>
    </form>
@endsection
