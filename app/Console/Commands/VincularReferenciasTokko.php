<?php

namespace App\Console\Commands;

use App\Models\Ubicacion;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class VincularReferenciasTokko extends Command
{
    protected $signature = 'ubicaciones:vincular-referencias-tokko {--reporte : Muestra las pendientes por tipo} {--exportar-pendientes : Genera un CSV para revisión}';
    protected $description = 'Completa id_tokko y ruta_tokko en las ubicaciones importadas desde Tokko.';

    public function handle(): int
    {
        if ($this->option('reporte')) {
            $this->table(['Tipo', 'Pendientes'], Ubicacion::query()->where('origen', 'tokko')->whereNull('id_tokko')
                ->join('tipos_ubicacion as tipo', 'tipo.id', '=', 'ubicaciones.tipo_ubicacion_id')
                ->selectRaw('tipo.nombre as tipo, count(*) as cantidad')->groupBy('tipo.nombre')->orderByDesc('cantidad')->get()
                ->map(fn ($fila) => [$fila->tipo, $fila->cantidad]));
            return self::SUCCESS;
        }
        if ($this->option('exportar-pendientes')) {
            $archivo = storage_path('app/reportes/ubicaciones_tokko_pendientes.csv');
            if (!is_dir(dirname($archivo))) mkdir(dirname($archivo), 0755, true);
            $salida = fopen($archivo, 'wb');
            fputcsv($salida, ['tipo', 'nombre', 'padre', 'ruta_crm']);
            Ubicacion::query()->with(['padre', 'tipoUbicacion'])->where('origen', 'tokko')->whereNull('id_tokko')->orderBy('nombre_completo')->each(fn (Ubicacion $u) => fputcsv($salida, [$u->tipoUbicacion?->nombre, $u->nombre, $u->padre?->nombre, $u->nombre_completo]));
            fclose($salida); $this->info("Reporte generado: {$archivo}"); return self::SUCCESS;
        }
        $archivos = [
            ...glob(database_path('datos/tokko/zona-norte/*.json')),
            ...glob(database_path('datos/tokko/zona-norte/nodos/*.json')),
            ...glob(database_path('datos/tokko/zona-norte/nodos-segundo/*.json')),
            ...glob(database_path('datos/tokko/zona-norte/nodos-tercero/*.json')),
        ];
        $resultado = ['vinculadas' => 0, 'ambiguas' => 0, 'sin_coincidencia' => 0];

        foreach ($archivos as $archivo) {
            $dato = json_decode((string) file_get_contents($archivo), true, 512, JSON_THROW_ON_ERROR);
            $ruta = $dato['full_location'] ?? null;
            $idTokko = $dato['id'] ?? null;
            $nombre = $dato['name'] ?? null;
            if (!$ruta || !$idTokko || !$nombre) continue;
            $partes = array_map('trim', explode('|', $ruta));
            $partido = $partes[2] ?? '';
            $normalizado = Str::lower(trim(Str::ascii($nombre)));
            $candidatas = Ubicacion::query()->with('padre')->where('origen', 'tokko')->where('nombre_normalizado', $normalizado)
                ->where('nombre_completo', 'like', "%Partido de {$partido}%")->get();
            if ($candidatas->count() > 1) {
                $padreTokko = collect(array_slice($partes, 0, -1))->reverse()->first(fn ($parte) => !Str::startsWith($parte, 'Countries/'));
                if ($padreTokko) {
                    $padreNormalizado = Str::lower(trim(Str::ascii($padreTokko)));
                    $candidatas = $candidatas->filter(fn (Ubicacion $ubicacion) => $ubicacion->padre?->nombre_normalizado === $padreNormalizado)->values();
                }
            }
            if ($candidatas->count() === 1) {
                $candidatas->first()->update(['id_tokko' => $idTokko, 'ruta_tokko' => $ruta]);
                $resultado['vinculadas']++;
            } elseif ($candidatas->count() > 1) $resultado['ambiguas']++;
            else $resultado['sin_coincidencia']++;
        }
        $this->table(['Resultado', 'Cantidad'], collect($resultado)->map(fn($v,$k) => [Str::headline($k),$v])->values());
        return self::SUCCESS;
    }
}
