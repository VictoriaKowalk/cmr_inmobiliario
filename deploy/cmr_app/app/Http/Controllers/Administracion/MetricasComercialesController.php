<?php

namespace App\Http\Controllers\Administracion;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MetricasComercialesController extends DashboardController
{
    public function mostrarMetricas(Request $request): View
    {
        $periodo = $request->validate([
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);
        $desde = Carbon::parse($periodo['desde'] ?? now()->subDays(29)->toDateString())
            ->startOfDay();
        $hasta = Carbon::parse($periodo['hasta'] ?? now()->toDateString())
            ->endOfDay();

        return view('administracion.contactos.metricas', [
            ...$this->indicadoresComerciales($desde, $hasta),
            'desde' => $desde,
            'hasta' => $hasta,
        ]);
    }
}
