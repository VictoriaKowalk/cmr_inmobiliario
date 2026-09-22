<?php

namespace App\Http\Controllers\Administracion;

use App\Enums\EstadoSeguimiento;
use App\Enums\PrioridadOportunidad;
use App\Http\Controllers\Controller;
use App\Models\Consulta;
use App\Models\Tasacion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ContactoController extends Controller
{
    public function listar(Request $solicitud): View
    {
        $busqueda = trim((string) $solicitud->query('buscar'));
        $tipo = $solicitud->query('tipo', 'todos');
        $estado = $solicitud->query('estado', 'todos');
        $lectura = $solicitud->query('lectura', 'todas');
        $responsableId = $solicitud->query('responsable');
        $prioridad = $solicitud->query('prioridad', 'todas');

        $contactos = collect();

        if (in_array($tipo, ['todos', 'consulta_general', 'consulta_propiedad'], true)) {
            $contactos = $contactos->concat(
                $this->consultas($busqueda, $tipo, $estado, $lectura, $responsableId, $prioridad)
            );
        }

        if (in_array($tipo, ['todos', 'tasacion'], true)) {
            $contactos = $contactos->concat(
                $this->tasaciones($busqueda, $estado, $lectura, $responsableId, $prioridad)
            );
        }

        $contactos = $contactos->sortByDesc('recibida_en')->values();
        $pagina = max(1, (int) $solicitud->query('page', 1));
        $porPagina = 20;

        $paginador = new LengthAwarePaginator(
            $contactos->forPage($pagina, $porPagina)->values(),
            $contactos->count(),
            $porPagina,
            $pagina,
            [
                'path' => $solicitud->url(),
                'query' => $solicitud->query(),
            ]
        );

        return view('administracion.contactos.listar', [
            'contactos' => $paginador,
            'estadosSeguimiento' => EstadoSeguimiento::cases(),
            'busqueda' => $busqueda,
            'tipo' => $tipo,
            'estado' => $estado,
            'lectura' => $lectura,
            'responsableId' => $responsableId,
            'prioridad' => $prioridad,
            'responsables' => Usuario::query()
                ->where('activo', true)
                ->orderBy('nombre')
                ->get(),
            'prioridades' => PrioridadOportunidad::cases(),
        ]);
    }

    private function consultas(
        string $busqueda,
        string $tipo,
        string $estado,
        string $lectura,
        mixed $responsableId,
        string $prioridad
    ): Collection {
        return Consulta::query()
            ->with(['propiedad', 'responsable'])
            ->when(auth()->user()->esAsesor(), fn ($consulta) => $consulta
                ->where('responsable_id', auth()->id()))
            ->when($tipo === 'consulta_general', fn ($consulta) => $consulta
                ->whereNull('propiedad_id'))
            ->when($tipo === 'consulta_propiedad', fn ($consulta) => $consulta
                ->whereNotNull('propiedad_id'))
            ->when($estado !== 'todos', fn ($consulta) => $consulta
                ->where('estado_seguimiento', $estado))
            ->when($lectura === 'sin_leer', fn ($consulta) => $consulta
                ->whereNull('leida_en'))
            ->when($lectura === 'leidas', fn ($consulta) => $consulta
                ->whereNotNull('leida_en'))
            ->when($responsableId === 'sin_asignar', fn ($consulta) => $consulta
                ->whereNull('responsable_id'))
            ->when(is_numeric($responsableId), fn ($consulta) => $consulta
                ->where('responsable_id', $responsableId))
            ->when($prioridad !== 'todas', fn ($consulta) => $consulta
                ->where('prioridad', $prioridad))
            ->when($busqueda !== '', fn ($consulta) => $consulta
                ->where(fn ($subconsulta) => $subconsulta
                    ->where('nombre', 'like', "%{$busqueda}%")
                    ->orWhere('email', 'like', "%{$busqueda}%")
                    ->orWhere('telefono', 'like', "%{$busqueda}%")
                    ->orWhereHas('propiedad', fn ($propiedades) => $propiedades
                        ->where('codigo_interno', 'like', "%{$busqueda}%")
                        ->orWhere('titulo', 'like', "%{$busqueda}%"))))
            ->get()
            ->map(fn (Consulta $consulta) => (object) [
                'tipo' => $consulta->propiedad_id
                    ? 'consulta_propiedad'
                    : 'consulta_general',
                'tipo_etiqueta' => $consulta->propiedad_id
                    ? 'Consulta por propiedad'
                    : 'Consulta general',
                'nombre' => $consulta->nombre,
                'email' => $consulta->email,
                'telefono' => $consulta->telefono,
                'referencia' => $consulta->propiedad
                    ? "{$consulta->propiedad->codigo_interno} · {$consulta->propiedad->titulo}"
                    : 'Contacto general',
                'estado' => $consulta->estado_seguimiento,
                'prioridad' => $consulta->prioridad,
                'responsable' => $consulta->responsable?->nombreCompleto(),
                'proxima_tarea' => $consulta->proxima_tarea,
                'proxima_tarea_en' => $consulta->proxima_tarea_en,
                'leida_en' => $consulta->leida_en,
                'recibida_en' => $consulta->created_at,
                'url' => route('administracion.consultas.mostrar', $consulta),
            ]);
    }

    private function tasaciones(
        string $busqueda,
        string $estado,
        string $lectura,
        mixed $responsableId,
        string $prioridad
    ): Collection {
        return Tasacion::query()
            ->with(['tipoPropiedad', 'responsable'])
            ->when(auth()->user()->esAsesor(), fn ($consulta) => $consulta
                ->where('responsable_id', auth()->id()))
            ->when($estado !== 'todos', fn ($consulta) => $consulta
                ->where('estado_seguimiento', $estado))
            ->when($lectura === 'sin_leer', fn ($consulta) => $consulta
                ->whereNull('leida_en'))
            ->when($lectura === 'leidas', fn ($consulta) => $consulta
                ->whereNotNull('leida_en'))
            ->when($responsableId === 'sin_asignar', fn ($consulta) => $consulta
                ->whereNull('responsable_id'))
            ->when(is_numeric($responsableId), fn ($consulta) => $consulta
                ->where('responsable_id', $responsableId))
            ->when($prioridad !== 'todas', fn ($consulta) => $consulta
                ->where('prioridad', $prioridad))
            ->when($busqueda !== '', fn ($consulta) => $consulta
                ->where(fn ($subconsulta) => $subconsulta
                    ->where('nombre', 'like', "%{$busqueda}%")
                    ->orWhere('email', 'like', "%{$busqueda}%")
                    ->orWhere('telefono', 'like', "%{$busqueda}%")
                    ->orWhere('ubicacion_texto', 'like', "%{$busqueda}%")
                    ->orWhereHas('tipoPropiedad', fn ($tipos) => $tipos
                        ->where('nombre', 'like', "%{$busqueda}%"))))
            ->get()
            ->map(fn (Tasacion $tasacion) => (object) [
                'tipo' => 'tasacion',
                'tipo_etiqueta' => 'Tasación',
                'nombre' => $tasacion->nombre,
                'email' => $tasacion->email,
                'telefono' => $tasacion->telefono,
                'referencia' => ($tasacion->tipoPropiedad?->nombre ?? 'Sin tipo')
                    .' · '.$tasacion->ubicacion_texto,
                'estado' => $tasacion->estado_seguimiento,
                'prioridad' => $tasacion->prioridad,
                'responsable' => $tasacion->responsable?->nombreCompleto(),
                'proxima_tarea' => $tasacion->proxima_tarea,
                'proxima_tarea_en' => $tasacion->proxima_tarea_en,
                'leida_en' => $tasacion->leida_en,
                'recibida_en' => $tasacion->created_at,
                'url' => route('administracion.tasaciones.mostrar', $tasacion),
            ]);
    }
}
