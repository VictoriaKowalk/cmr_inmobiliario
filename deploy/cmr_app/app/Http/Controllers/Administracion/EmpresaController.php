<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\ActualizarEmpresaRequest;
use App\Models\Empresa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmpresaController extends Controller
{
    public function editar(): View
    {
        return view('administracion.empresa.editar', [
            'empresa' => Empresa::query()->firstOrCreate([], [
                'nombre_comercial' => config('app.name'),
                'zona_horaria' => config('app.timezone'),
            ]),
            'zonasHorarias' => [
                'America/Argentina/Buenos_Aires' => 'Buenos Aires (Argentina)',
                'America/Argentina/Cordoba' => 'Córdoba (Argentina)',
                'America/Argentina/Mendoza' => 'Mendoza (Argentina)',
                'America/Montevideo' => 'Montevideo (Uruguay)',
                'America/Santiago' => 'Santiago (Chile)',
                'America/Asuncion' => 'Asunción (Paraguay)',
            ],
        ]);
    }

    public function actualizar(ActualizarEmpresaRequest $solicitud): RedirectResponse
    {
        $empresa = Empresa::query()->firstOrCreate([], [
            'nombre_comercial' => config('app.name'),
            'zona_horaria' => config('app.timezone'),
        ]);
        $datos = $solicitud->safe()->except(['logo', 'eliminar_logo']);

        if ($solicitud->boolean('eliminar_logo') || $solicitud->hasFile('logo')) {
            if ($empresa->logo_ruta) {
                Storage::disk('public')->delete($empresa->logo_ruta);
            }
            $datos['logo_ruta'] = null;
        }

        if ($solicitud->hasFile('logo')) {
            $datos['logo_ruta'] = $solicitud->file('logo')->store('empresa', 'public');
        }

        $empresa->update($datos);

        return redirect()->route('administracion.empresa.editar')
            ->with('estado', 'Los datos de la empresa se actualizaron correctamente.');
    }
}
