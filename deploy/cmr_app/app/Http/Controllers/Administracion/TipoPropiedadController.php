<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\GuardarTipoPropiedadRequest;
use App\Models\TipoPropiedad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TipoPropiedadController extends Controller
{
    public function listar(Request $solicitud): View
    {
        $busqueda = trim((string) $solicitud->query('buscar'));
        $estado = $solicitud->query('estado', 'todos');

        $tiposPropiedad = TipoPropiedad::query()
            ->when(
                $busqueda !== '',
                fn ($consulta) => $consulta
                    ->where('nombre', 'like', "%{$busqueda}%")
            )
            ->when(
                $estado === 'activos',
                fn ($consulta) => $consulta->where('activo', true)
            )
            ->when(
                $estado === 'inactivos',
                fn ($consulta) => $consulta->where('activo', false)
            )
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('administracion.tipos-propiedad.listar', [
            'tiposPropiedad' => $tiposPropiedad,
            'busqueda' => $busqueda,
            'estado' => $estado,
        ]);
    }

    public function crear(): View
    {
        return view('administracion.tipos-propiedad.crear');
    }

    public function guardar(
        GuardarTipoPropiedadRequest $solicitud
    ): RedirectResponse {
        TipoPropiedad::query()->create([
            'nombre' => $solicitud->string('nombre')->toString(),
            'activo' => true,
        ]);

        return redirect()
            ->route('administracion.tipos-propiedad.listar')
            ->with('estado', 'El tipo de propiedad se creó correctamente.');
    }

    public function editar(TipoPropiedad $tipoPropiedad): View
    {
        return view('administracion.tipos-propiedad.editar', [
            'tipoPropiedad' => $tipoPropiedad,
        ]);
    }

    public function actualizar(
        GuardarTipoPropiedadRequest $solicitud,
        TipoPropiedad $tipoPropiedad
    ): RedirectResponse {
        $tipoPropiedad->update([
            'nombre' => $solicitud->string('nombre')->toString(),
        ]);

        return redirect()
            ->route('administracion.tipos-propiedad.listar')
            ->with('estado', 'El tipo de propiedad se actualizó correctamente.');
    }

    public function cambiarEstado(
        TipoPropiedad $tipoPropiedad
    ): RedirectResponse {
        $tipoPropiedad->update([
            'activo' => ! $tipoPropiedad->activo,
        ]);

        $mensaje = $tipoPropiedad->activo
            ? 'El tipo de propiedad quedó activo.'
            : 'El tipo de propiedad quedó inactivo.';

        return back()->with('estado', $mensaje);
    }
}
