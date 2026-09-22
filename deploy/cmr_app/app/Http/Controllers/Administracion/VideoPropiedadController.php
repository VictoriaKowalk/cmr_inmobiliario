<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\GuardarVideoPropiedadRequest;
use App\Models\Propiedad;
use App\Models\VideoPropiedad;
use App\Services\ServicioVideosPropiedad;
use Illuminate\Http\RedirectResponse;
use InvalidArgumentException;

class VideoPropiedadController extends Controller
{
    public function guardar(
        GuardarVideoPropiedadRequest $solicitud,
        Propiedad $propiedad,
        ServicioVideosPropiedad $servicioVideos
    ): RedirectResponse {
        try {
            $servicioVideos->guardarVideoDesdeYoutube(
                $propiedad,
                $solicitud->validated('youtube_url'),
                $solicitud->validated('titulo')
            );
        } catch (InvalidArgumentException $error) {
            return back()
                ->withErrors(['youtube_url' => $error->getMessage()], 'videos')
                ->withInput();
        }

        return back()->with('estado', 'El video se cargó correctamente.');
    }

    public function eliminar(
        Propiedad $propiedad,
        VideoPropiedad $video,
        ServicioVideosPropiedad $servicioVideos
    ): RedirectResponse {
        abort_unless($video->propiedad_id === $propiedad->id, 404);

        $servicioVideos->eliminarVideo($video);

        return back()->with('estado', 'El video se eliminó correctamente.');
    }
}
