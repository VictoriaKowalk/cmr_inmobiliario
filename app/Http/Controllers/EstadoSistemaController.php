<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EstadoSistemaController extends Controller
{
    public function mostrar(): JsonResponse
    {
        $baseDisponible = $this->comprobarBase();
        $storageDisponible = $this->comprobarStorage();
        $saludable = $baseDisponible && $storageDisponible;

        return response()->json([
            'estado' => $saludable ? 'ok' : 'error',
            'comprobaciones' => [
                'base_datos' => $baseDisponible,
                'storage' => $storageDisponible,
            ],
            'fecha' => now()->toIso8601String(),
        ], $saludable ? 200 : 503);
    }

    private function comprobarBase(): bool
    {
        try {
            DB::select('select 1');

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    private function comprobarStorage(): bool
    {
        $ruta = 'health-check/'.str()->uuid().'.txt';

        try {
            if (! Storage::disk('local')->put($ruta, 'ok')) {
                return false;
            }

            return Storage::disk('local')->exists($ruta);
        } catch (Throwable) {
            return false;
        } finally {
            Storage::disk('local')->delete($ruta);
        }
    }
}
