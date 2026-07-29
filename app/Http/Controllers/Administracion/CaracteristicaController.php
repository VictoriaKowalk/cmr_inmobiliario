<?php

namespace App\Http\Controllers\Administracion;

use App\Enums\CategoriaCaracteristica;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\GuardarCaracteristicaRequest;
use App\Models\Caracteristica;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaracteristicaController extends Controller
{
    public function listar(Request $solicitud): View
    {
        $busqueda = trim((string) $solicitud->query('buscar'));
        $categoria = $solicitud->query('categoria');
        $estado = $solicitud->query('estado', 'todas');

        $caracteristicas = Caracteristica::query()
            ->when($busqueda !== '', fn ($consulta) => $consulta
                ->where('nombre', 'like', "%{$busqueda}%"))
            ->when($categoria, fn ($consulta) => $consulta
                ->where('categoria', $categoria))
            ->when($estado === 'activas', fn ($consulta) => $consulta
                ->where('activa', true))
            ->when($estado === 'inactivas', fn ($consulta) => $consulta
                ->where('activa', false))
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->paginate(20)
            ->withQueryString();

        return view('administracion.caracteristicas.listar', [
            'caracteristicas' => $caracteristicas,
            'categorias' => CategoriaCaracteristica::cases(),
            'busqueda' => $busqueda,
            'categoriaSeleccionada' => $categoria,
            'estado' => $estado,
        ]);
    }

    public function crear(): View
    {
        return view('administracion.caracteristicas.crear', [
            'categorias' => CategoriaCaracteristica::cases(),
        ]);
    }

    public function guardar(
        GuardarCaracteristicaRequest $solicitud
    ): RedirectResponse {
        Caracteristica::query()->create([
            ...$solicitud->validated(),
            'activa' => true,
        ]);

        return redirect()->route('administracion.caracteristicas.listar')
            ->with('estado', 'La característica se creó correctamente.');
    }

    public function editar(Caracteristica $caracteristica): View
    {
        return view('administracion.caracteristicas.editar', [
            'caracteristica' => $caracteristica,
            'categorias' => CategoriaCaracteristica::cases(),
        ]);
    }

    public function actualizar(
        GuardarCaracteristicaRequest $solicitud,
        Caracteristica $caracteristica
    ): RedirectResponse {
        $caracteristica->update($solicitud->validated());

        return redirect()->route('administracion.caracteristicas.listar')
            ->with('estado', 'La característica se actualizó correctamente.');
    }

    public function cambiarEstado(
        Caracteristica $caracteristica
    ): RedirectResponse {
        $caracteristica->update(['activa' => ! $caracteristica->activa]);

        return back()->with(
            'estado',
            $caracteristica->activa
                ? 'La característica quedó activa.'
                : 'La característica quedó inactiva.'
        );
    }
}
