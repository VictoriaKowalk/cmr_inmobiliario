@extends('layouts.administracion')

@section('titulo', 'Nueva propiedad')

@section('contenido')
    <div class="mb-6">
        <p class="text-sm font-medium text-emerald-700">Propiedades</p>
        <h1 class="mt-1 text-2xl font-semibold">Nueva propiedad</h1>
        <p class="mt-1 text-sm text-neutral-600">Completá los datos y activá al menos una operación.</p>
    </div>

    <form method="POST"
          action="{{ route('administracion.propiedades.guardar') }}"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @include('administracion.propiedades._formulario', [
            'propiedad' => null,
            'textoBoton' => 'Crear propiedad',
            'mostrarAcciones' => false,
        ])

        <section class="border border-neutral-200 bg-white p-5 sm:p-6">
            <h2 class="text-lg font-semibold">Fotos</h2>
            <p class="mt-1 text-sm text-neutral-600">
                Podés cargar hasta 20 fotos JPG, PNG o WebP, de hasta 10 MB cada una
                (200 MB en total). La primera será la portada.
            </p>

            @if ($errors->has('imagenes') || $errors->has('imagenes.*'))
                <div class="mt-5 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">
                    {{ $errors->first('imagenes') ?: $errors->first('imagenes.*') }}
                </div>
            @endif

            <div class="mt-5 border border-dashed border-neutral-300 bg-neutral-50 p-5">
                <label for="imagenes" class="block text-sm font-semibold">Seleccionar imágenes</label>
                <input id="imagenes"
                       name="imagenes[]"
                       type="file"
                       accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                       multiple
                       data-selector-imagenes
                       data-maximo-archivos="20"
                       data-maximo-bytes="10485760"
                       data-maximo-total-bytes="209715200"
                       class="mt-3 block w-full text-sm file:mr-4 file:border-0 file:bg-neutral-900 file:px-4 file:py-2 file:font-semibold file:text-white">
                <p data-error-imagenes="imagenes"
                   class="mt-3 hidden border-l-4 border-red-600 bg-red-50 p-3 text-sm text-red-900"></p>
                <div data-vista-previa="imagenes"
                     class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-6"></div>
            </div>
        </section>

        <section class="border border-neutral-200 bg-white p-5 sm:p-6">
            <h2 class="text-lg font-semibold">Video</h2>
            <p class="mt-1 text-sm text-neutral-600">
                Si tenés un recorrido de la propiedad, podés agregar su enlace de YouTube.
            </p>
            <div class="mt-5 grid gap-5 lg:grid-cols-2">
                <div>
                    <label for="video_titulo" class="mb-2 block text-sm font-medium">Título opcional</label>
                    <input id="video_titulo"
                           name="video_titulo"
                           value="{{ old('video_titulo') }}"
                           maxlength="150"
                           class="h-10 w-full border border-neutral-300 px-3 text-sm">
                    @error('video_titulo')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="youtube_url" class="mb-2 block text-sm font-medium">URL de YouTube</label>
                    <input id="youtube_url"
                           name="youtube_url"
                           type="url"
                           value="{{ old('youtube_url') }}"
                           placeholder="https://www.youtube.com/watch?v=..."
                           class="h-10 w-full border border-neutral-300 px-3 text-sm">
                    @error('youtube_url')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        @error('guardado')
            <div class="border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">
                {{ $message }}
            </div>
        @enderror

        <div class="flex flex-wrap gap-3">
            <button type="submit"
                    class="inline-flex h-11 items-center bg-emerald-700 px-5 text-sm font-semibold text-white hover:bg-emerald-800">
                Crear propiedad
            </button>
            <a href="{{ route('administracion.propiedades.listar') }}"
               class="inline-flex h-11 items-center px-4 text-sm font-medium text-neutral-600">
                Cancelar
            </a>
        </div>
    </form>
@endsection
