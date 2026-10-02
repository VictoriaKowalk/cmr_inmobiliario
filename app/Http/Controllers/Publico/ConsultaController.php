<?php

namespace App\Http\Controllers\Publico;

use App\Enums\EstadoOperacion;
use App\Http\Controllers\Controller;
use App\Http\Requests\Publico\GuardarConsultaPublicaRequest;
use App\Models\Consulta;
use App\Models\Propiedad;
use App\Models\Usuario;
use App\Notifications\NotificacionNuevoContacto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ConsultaController extends Controller
{
    public function crearGeneral(): View
    {
        return view('web-publica.temas.inmobiliaria.contacto');
    }

    public function guardarGeneral(
        GuardarConsultaPublicaRequest $solicitud
    ): RedirectResponse {
        $consulta = Consulta::query()->create([
            ...$solicitud->safe()->except([
                'operacion_propiedad_id',
                'sitio_web',
            ]),
            'propiedad_id' => null,
            'operacion_propiedad_id' => null,
        ]);
        $consulta->inicializarOportunidad();
        $this->notificarAdministradores($consulta);

        return back()
            ->withInput([])
            ->with('estado', 'Recibimos tu consulta. Te vamos a contactar a la brevedad.');
    }

    public function crearPropiedad(Propiedad $propiedad): View
    {
        abort_unless(
            $propiedad->operaciones()
                ->where('estado', EstadoOperacion::PUBLICADA->value)
                ->exists(),
            404
        );

        return view('publico.consultas.propiedad', [
            'propiedad' => $propiedad->load(['operaciones' => fn ($operaciones) => $operaciones
                ->where('estado', EstadoOperacion::PUBLICADA->value)]),
        ]);
    }

    public function guardarPropiedad(
        GuardarConsultaPublicaRequest $solicitud,
        Propiedad $propiedad
    ): RedirectResponse {
        abort_unless(
            $propiedad->operaciones()
                ->where('estado', EstadoOperacion::PUBLICADA->value)
                ->exists(),
            404
        );

        $operacionId = $solicitud->input('operacion_propiedad_id');

        if ($operacionId !== null) {
            $perteneceAPropiedad = $propiedad->operaciones()
                ->whereKey($operacionId)
                ->where('estado', EstadoOperacion::PUBLICADA->value)
                ->exists();

            abort_unless($perteneceAPropiedad, 422);
        }

        $consulta = Consulta::query()->create([
            ...$solicitud->safe()->except([
                'operacion_propiedad_id',
                'sitio_web',
            ]),
            'propiedad_id' => $propiedad->id,
            'operacion_propiedad_id' => $operacionId,
        ]);
        $consulta->inicializarOportunidad();
        $this->notificarAdministradores($consulta);

        return back()
            ->withInput([])
            ->with('estado', 'Recibimos tu consulta por esta propiedad. Te vamos a contactar a la brevedad.');
    }

    private function notificarAdministradores(Consulta $consulta): void
    {
        if (! config('services.notificaciones_administracion.habilitadas')) {
            return;
        }

        Notification::send(
            Usuario::query()->where('activo', true)->get(),
            new NotificacionNuevoContacto($consulta)
        );
    }
}
