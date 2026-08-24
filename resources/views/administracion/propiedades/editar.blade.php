@extends('layouts.administracion')

@section('titulo', 'Editar propiedad')

@section('contenido')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-700">{{ $propiedad->codigo_interno }}</p>
            <h1 class="mt-1 text-2xl font-semibold">Editar propiedad</h1>
        </div>
        <a href="{{ route('administracion.propiedades.mostrar', $propiedad) }}"
           class="text-sm font-medium text-emerald-700">Ver resumen</a>
    </div>

    <form method="POST"
          action="{{ route('administracion.propiedades.actualizar', $propiedad) }}"
          class="space-y-6">
        @csrf
        @method('PUT')
        @include('administracion.propiedades._formulario', [
            'propiedad' => $propiedad,
            'textoBoton' => 'Guardar cambios',
        ])
    </form>

    <section class="mt-8 border border-neutral-200 bg-white p-5 sm:p-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold">Imágenes</h2>
                <p class="mt-1 text-sm text-neutral-600">
                    Podés cargar hasta 20 fotos JPG, PNG o WebP por vez, de hasta
                    10 MB cada una (200 MB en total).
                </p>
            </div>
            <p class="text-sm text-neutral-500">{{ $propiedad->imagenes->count() }} imágenes</p>
        </div>

        @if ($errors->has('imagenes') || $errors->has('imagenes.*'))
            <div class="mt-5 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">
                {{ $errors->first('imagenes') ?: $errors->first('imagenes.*') }}
            </div>
        @endif

        <form method="POST"
              action="{{ route('administracion.propiedades.imagenes.guardar', $propiedad) }}"
              enctype="multipart/form-data"
              class="mt-5 border border-dashed border-neutral-300 bg-neutral-50 p-5">
            @csrf
            <label for="imagenes" class="block text-sm font-semibold">Seleccionar imágenes</label>
            <p class="mt-1 text-xs text-neutral-500">
                Podés elegir imágenes en varias tandas; las nuevas se agregarán a las ya seleccionadas.
            </p>
            <input id="imagenes"
                   name="imagenes[]"
                   type="file"
                   accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                   multiple
                   required
                   data-selector-imagenes
                   data-maximo-archivos="20"
                   data-maximo-bytes="10485760"
                   data-maximo-total-bytes="209715200"
                   class="mt-3 block w-full text-sm file:mr-4 file:border-0 file:bg-neutral-900 file:px-4 file:py-2 file:font-semibold file:text-white">
            <p data-error-imagenes="imagenes"
               class="mt-3 hidden border-l-4 border-red-600 bg-red-50 p-3 text-sm text-red-900"></p>
            <div data-vista-previa="imagenes"
                 class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-6"></div>
            <button type="submit"
                    class="mt-5 inline-flex h-10 items-center bg-emerald-700 px-4 text-sm font-semibold text-white hover:bg-emerald-800">
                Subir imágenes
            </button>
        </form>

        @if ($propiedad->imagenes->isNotEmpty())
            <form method="POST"
                  action="{{ route('administracion.propiedades.imagenes.ordenar', $propiedad) }}"
                  class="mt-6"
                  data-formulario-orden-imagenes>
                @csrf
                @method('PUT')
                <div class="mb-4 border-l-2 border-neutral-300 pl-3 text-sm text-neutral-600">
                    Arrastrá las tarjetas para ordenarlas. La primera posición será la primera imagen de la galería.
                </div>
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3"
                     data-lista-ordenable>
                    @foreach ($propiedad->imagenes as $imagen)
                        <article class="overflow-hidden border border-neutral-200 bg-white transition"
                                 data-imagen-ordenable
                                 data-imagen-id="{{ $imagen->id }}">
                            <input type="hidden"
                                   name="ordenes[{{ $imagen->id }}]"
                                   value="{{ $loop->iteration }}"
                                   data-orden-imagen>
                            <div class="flex h-10 items-center justify-between border-b border-neutral-200 bg-neutral-50 px-2">
                                <button type="button"
                                        draggable="true"
                                        data-asa-arrastre
                                        class="flex h-8 flex-1 cursor-grab items-center justify-between px-1 text-xs font-semibold text-neutral-600 active:cursor-grabbing">
                                    <span>Arrastrar</span>
                                    <span data-posicion-imagen>Posición {{ $loop->iteration }}</span>
                                </button>
                                <div class="ml-2 flex gap-1">
                                    <button type="button"
                                            data-mover-imagen="antes"
                                            title="Mover antes"
                                            aria-label="Mover imagen antes"
                                            class="flex size-8 items-center justify-center border border-neutral-300 bg-white text-sm font-semibold">
                                        ↑
                                    </button>
                                    <button type="button"
                                            data-mover-imagen="despues"
                                            title="Mover después"
                                            aria-label="Mover imagen después"
                                            class="flex size-8 items-center justify-center border border-neutral-300 bg-white text-sm font-semibold">
                                        ↓
                                    </button>
                                </div>
                            </div>
                            <div class="relative aspect-[4/3] bg-neutral-100">
                                <img src="{{ $imagen->obtenerUrlPublica() }}"
                                     alt="{{ $imagen->nombre_original ?: 'Imagen de '.$propiedad->titulo }}"
                                     class="h-full w-full object-cover">
                                @if ($imagen->portada)
                                    <span class="absolute left-3 top-3 bg-emerald-700 px-2 py-1 text-xs font-semibold text-white">
                                        Portada
                                    </span>
                                @endif
                            </div>
                            <div class="p-4">
                                <p class="truncate text-xs text-neutral-500"
                                   title="{{ $imagen->nombre_original }}">
                                    {{ $imagen->nombre_original }}
                                </p>
                            </div>
                            <div class="flex items-center justify-between border-t border-neutral-200 px-4 py-3">
                                @if (! $imagen->portada)
                                    <button type="submit"
                                            form="portada-imagen-{{ $imagen->id }}"
                                            class="text-sm font-medium text-emerald-700">
                                        Usar como portada
                                    </button>
                                @else
                                    <span class="text-sm text-neutral-500">Portada actual</span>
                                @endif
                                @if (auth()->user()->puedeSupervisar())
                                <button type="submit"
                                        form="eliminar-imagen-{{ $imagen->id }}"
                                        class="text-sm font-medium text-red-700">
                                    Eliminar
                                </button>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
                <button type="submit"
                        class="mt-5 inline-flex h-10 items-center bg-neutral-900 px-4 text-sm font-semibold text-white">
                    Guardar orden
                </button>
            </form>

            @foreach ($propiedad->imagenes as $imagen)
                <form id="portada-imagen-{{ $imagen->id }}"
                      method="POST"
                      action="{{ route('administracion.propiedades.imagenes.portada', [$propiedad, $imagen]) }}">
                    @csrf
                    @method('PATCH')
                </form>
                @if (auth()->user()->puedeSupervisar())
                <form id="eliminar-imagen-{{ $imagen->id }}"
                      method="POST"
                      action="{{ route('administracion.propiedades.imagenes.eliminar', [$propiedad, $imagen]) }}"
                      onsubmit="return confirm('¿Eliminar esta imagen?')">
                    @csrf
                    @method('DELETE')
                </form>
                @endif
            @endforeach
        @else
            <p class="mt-6 border border-neutral-200 p-6 text-center text-sm text-neutral-500">
                Esta propiedad todavía no tiene imágenes.
            </p>
        @endif
    </section>

    <section class="mt-8 border border-neutral-200 bg-white p-5 sm:p-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold">Videos</h2>
                <p class="mt-1 text-sm text-neutral-600">
                    Pegá un enlace de YouTube para agregarlo a la propiedad.
                </p>
            </div>
            <p class="text-sm text-neutral-500">{{ $propiedad->videos->count() }} videos</p>
        </div>

        @if ($errors->videos->has('youtube_url'))
            <div class="mt-5 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-900">
                {{ $errors->videos->first('youtube_url') }}
            </div>
        @endif

        <div class="mt-5">
            <form method="POST"
                  action="{{ route('administracion.propiedades.videos.guardar', $propiedad) }}"
                  class="border border-dashed border-neutral-300 bg-neutral-50 p-5 lg:max-w-2xl">
                @csrf
                <h3 class="font-semibold">Agregar video de YouTube</h3>
                <div class="mt-4">
                    <label for="titulo_video_youtube" class="mb-2 block text-sm font-medium">Título opcional</label>
                    <input id="titulo_video_youtube"
                           name="titulo"
                           maxlength="150"
                           class="h-10 w-full border border-neutral-300 px-3 text-sm">
                </div>
                <div class="mt-4">
                    <label for="youtube_url" class="mb-2 block text-sm font-medium">URL de YouTube</label>
                    <input id="youtube_url"
                           name="youtube_url"
                           type="url"
                           placeholder="https://www.youtube.com/watch?v=..."
                           class="h-10 w-full border border-neutral-300 px-3 text-sm">
                </div>
                <button type="submit"
                        class="mt-5 inline-flex h-10 items-center bg-emerald-700 px-4 text-sm font-semibold text-white hover:bg-emerald-800">
                    Agregar YouTube
                </button>
            </form>
        </div>

        @if ($propiedad->videos->isNotEmpty())
            <div class="mt-6 grid gap-4 lg:grid-cols-2">
                @foreach ($propiedad->videos as $video)
                    <article class="border border-neutral-200 bg-white">
                        <div class="aspect-video bg-neutral-100">
                            @if ($video->esYoutube())
                                <iframe src="{{ $video->obtenerUrlEmbedYoutube() }}"
                                        title="{{ $video->titulo ?: 'Video de '.$propiedad->titulo }}"
                                        class="h-full w-full"
                                        allowfullscreen
                                        loading="lazy"></iframe>
                            @else
                                <video src="{{ $video->obtenerUrlPublica() }}"
                                       controls
                                       preload="metadata"
                                       class="h-full w-full bg-black"></video>
                            @endif
                        </div>
                        <div class="flex items-center justify-between gap-4 p-4">
                            <div class="min-w-0">
                                <p class="truncate font-medium">
                                    {{ $video->titulo ?: ($video->esYoutube() ? 'Video de YouTube' : 'Video subido') }}
                                </p>
                                <p class="mt-1 text-xs uppercase text-neutral-500">{{ $video->tipo }}</p>
                            </div>
                            @if (auth()->user()->puedeSupervisar())
                            <form method="POST"
                                  action="{{ route('administracion.propiedades.videos.eliminar', [$propiedad, $video]) }}"
                                  onsubmit="return confirm('¿Eliminar este video?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-sm font-medium text-red-700">Eliminar</button>
                            </form>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="mt-6 border border-neutral-200 p-6 text-center text-sm text-neutral-500">
                Esta propiedad todavía no tiene videos.
            </p>
        @endif
    </section>
@endsection
