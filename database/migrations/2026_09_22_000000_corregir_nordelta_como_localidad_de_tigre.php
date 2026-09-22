<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $nordelta = DB::table('ubicaciones as ubicacion')
            ->join('ubicaciones as padre', 'padre.id', '=', 'ubicacion.ubicacion_padre_id')
            ->join('tipos_ubicacion as tipo', 'tipo.id', '=', 'ubicacion.tipo_ubicacion_id')
            ->where('ubicacion.nombre_normalizado', 'nordelta')
            ->where('padre.nombre_normalizado', 'benavidez')
            ->where('tipo.codigo', 'barrio')
            ->select('ubicacion.*', 'padre.ubicacion_padre_id as municipio_id')
            ->first();

        if ($nordelta === null || $nordelta->municipio_id === null) {
            return;
        }

        $tipoLocalidadId = DB::table('tipos_ubicacion')
            ->where('codigo', 'localidad')
            ->value('id');

        $municipio = DB::table('ubicaciones')
            ->find($nordelta->municipio_id);

        if ($tipoLocalidadId === null || $municipio === null) {
            return;
        }

        $nordeltaCorrecto = DB::table('ubicaciones')
            ->where('ubicacion_padre_id', $municipio->id)
            ->where('tipo_ubicacion_id', $tipoLocalidadId)
            ->where('nombre_normalizado', 'nordelta')
            ->first();

        if ($nordeltaCorrecto !== null) {
            DB::table('propiedades')
                ->where('ubicacion_id', $nordelta->id)
                ->update(['ubicacion_id' => $nordeltaCorrecto->id]);

            DB::table('ubicaciones')
                ->where('ubicacion_padre_id', $nordelta->id)
                ->update(['ubicacion_padre_id' => $nordeltaCorrecto->id]);

            DB::table('ubicaciones')
                ->where('id', $nordelta->id)
                ->delete();

            $nordeltaId = $nordeltaCorrecto->id;
        } else {
            $nordeltaId = $nordelta->id;

            DB::table('ubicaciones')
                ->where('id', $nordeltaId)
                ->update([
                    'ubicacion_padre_id' => $municipio->id,
                    'tipo_ubicacion_id' => $tipoLocalidadId,
                    'nombre_completo' => "{$municipio->nombre_completo} | Nordelta",
                    'updated_at' => now(),
                ]);
        }

        $rutaNordelta = DB::table('ubicaciones')
            ->where('id', $nordeltaId)
            ->value('nombre_completo');

        $this->actualizarRutasHijas($nordeltaId, $rutaNordelta);
    }

    private function actualizarRutasHijas(int $ubicacionPadreId, string $rutaPadre): void
    {
        DB::table('ubicaciones')
            ->where('ubicacion_padre_id', $ubicacionPadreId)
            ->orderBy('id')
            ->each(function (object $hija) use ($rutaPadre): void {
                $rutaHija = "{$rutaPadre} | {$hija->nombre}";

                DB::table('ubicaciones')
                    ->where('id', $hija->id)
                    ->update(['nombre_completo' => $rutaHija, 'updated_at' => now()]);

                $this->actualizarRutasHijas($hija->id, $rutaHija);
            });
    }

    public function down(): void
    {
        // La corrección representa la jerarquía vigente y no se revierte.
    }
};
