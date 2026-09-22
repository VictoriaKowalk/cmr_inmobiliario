<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\GuardarImagenPropiedadRequest;
use App\Http\Requests\Administracion\OrdenarImagenesPropiedadRequest;
use App\Models\ImagenPropiedad;
use App\Models\Propiedad;
use App\Services\ServicioImagenesPropiedad;
use Illuminate\Http\RedirectResponse;

class ImagenPropiedadController extends Controller
{
    public function guardar(
        GuardarImagenPropiedadRequest $solicitud,
        Propiedad $propiedad,
        ServicioImagenesPropiedad $servicioImagenes
    ): RedirectResponse {
        $servicioImagenes->guardarImagenes(
            $propiedad,
            $solicitud->file('imagenes')
        );

        return back()->with('estado', 'Las imágenes se cargaron correctamente.');
    }

    public function ordenar(
        OrdenarImagenesPropiedadRequest $solicitud,
        Propiedad $propiedad,
        ServicioImagenesPropiedad $servicioImagenes
    ): RedirectResponse {
        $servicioImagenes->reordenarImagenes(
            $propiedad,
            $solicitud->validated('ordenes')
        );

        return back()->with('estado', 'El orden de las imágenes se actualizó.');
    }

    public function marcarComoPortada(
        Propiedad $propiedad,
        ImagenPropiedad $imagen,
        ServicioImagenesPropiedad $servicioImagenes
    ): RedirectResponse {
        $this->verificarPertenencia($propiedad, $imagen);
        $servicioImagenes->marcarPortada($imagen);

        return back()->with('estado', 'La imagen quedó seleccionada como portada.');
    }

    public function eliminar(
        Propiedad $propiedad,
        ImagenPropiedad $imagen,
        ServicioImagenesPropiedad $servicioImagenes
    ): RedirectResponse {
        $this->verificarPertenencia($propiedad, $imagen);
        $servicioImagenes->eliminarImagen($imagen);

        return back()->with('estado', 'La imagen se eliminó correctamente.');
    }

    private function verificarPertenencia(
        Propiedad $propiedad,
        ImagenPropiedad $imagen
    ): void {
        abort_unless(
            $imagen->propiedad_id === $propiedad->id,
            404
        );
    }
}
