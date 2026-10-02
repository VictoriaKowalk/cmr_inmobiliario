<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Publico\GuardarTasacionPublicaRequest;
use App\Models\Tasacion;
use App\Models\TipoPropiedad;
use App\Models\Usuario;
use App\Notifications\NotificacionNuevoContacto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class TasacionController extends Controller
{
    public function crear(): View
    {
        return view('web-publica.temas.inmobiliaria.tasaciones', [
            'tiposPropiedad' => TipoPropiedad::query()
                ->where('activo', true)
                ->orderBy('nombre')
                ->get(),
        ]);
    }

    public function guardar(
        GuardarTasacionPublicaRequest $solicitud
    ): RedirectResponse {
        $tasacion = Tasacion::query()->create(
            $solicitud->safe()->except('sitio_web')
        );
        $tasacion->inicializarOportunidad();
        $this->notificarAdministradores($tasacion);

        return back()
            ->withInput([])
            ->with('estado', 'Recibimos tu solicitud de tasación. Te vamos a contactar a la brevedad.');
    }

    private function notificarAdministradores(Tasacion $tasacion): void
    {
        if (! config('services.notificaciones_administracion.habilitadas')) {
            return;
        }

        Notification::send(
            Usuario::query()->where('activo', true)->get(),
            new NotificacionNuevoContacto($tasacion)
        );
    }
}
