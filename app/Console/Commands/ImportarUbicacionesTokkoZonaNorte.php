<?php

namespace App\Console\Commands;

use App\Models\TipoUbicacion;
use App\Models\Ubicacion;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportarUbicacionesTokkoZonaNorte extends Command
{
    protected $signature = 'ubicaciones:importar-tokko-zona-norte {--vista-previa : Solo informa cantidades} {--generar-sql : Genera el SQL manual desde la base actual}';
    protected $description = 'Importa localidades, barrios y subbarrios de Zona Norte desde las fichas descargadas de Tokko.';

    public function handle(): int
    {
        $tipos = TipoUbicacion::query()->pluck('id', 'codigo')->all();
        $raiz = database_path('datos/tokko/zona-norte');
        $primeros = collect(glob("{$raiz}/nodos/*.json") ?: [])
            ->mapWithKeys(fn ($archivo) => [(int) pathinfo($archivo, PATHINFO_FILENAME) => json_decode((string) file_get_contents($archivo), true, 512, JSON_THROW_ON_ERROR)]);
        $detalles = collect(glob("{$raiz}/nodos-segundo/*.json") ?: [])
            ->mapWithKeys(fn ($archivo) => [(int) pathinfo($archivo, PATHINFO_FILENAME) => json_decode((string) file_get_contents($archivo), true, 512, JSON_THROW_ON_ERROR)]);
        $partidos = collect(glob("{$raiz}/*.json") ?: [])->map(fn ($archivo) => json_decode((string) file_get_contents($archivo), true, 512, JSON_THROW_ON_ERROR));
        $cantidad = ['localidades' => 0, 'barrios' => 0, 'subbarrios' => 0];

        foreach ($partidos as $partido) {
            $nombrePartido = trim(explode('|', $partido['full_location'])[2]);
            $municipio = $this->municipio($nombrePartido, $tipos);

            foreach ($partido['divisions'] ?? [] as $primero) {
                $detallePrimero = $primeros->get((int) $primero['id']);
                if ($detallePrimero === null) continue;
                $esContenedor = Str::startsWith($detallePrimero['name'], 'Countries/');
                $localidad = $esContenedor ? null : $this->guardar($municipio, $tipos['localidad'], $detallePrimero['name']);
                if (!$esContenedor) $cantidad['localidades']++;

                foreach ($detallePrimero['divisions'] ?? [] as $segundo) {
                    $detalleSegundo = $detalles->get((int) $segundo['id']);
                    $padreBarrio = $localidad ?? $municipio;
                    $barrio = $this->buscarOGuardar($padreBarrio, $tipos['barrio'], $segundo['name']);
                    $cantidad['barrios']++;

                    foreach (($detalleSegundo['divisions'] ?? []) as $tercero) {
                        $tipoHijo = $barrio->tipo_ubicacion_id === $tipos['localidad'] ? $tipos['barrio'] : $tipos['subbarrio'];
                        $this->guardar($barrio, $tipoHijo, $tercero['name']);
                        $cantidad[$tipoHijo === $tipos['barrio'] ? 'barrios' : 'subbarrios']++;
                    }
                }
            }
        }

        $this->table(['Tipo', 'Procesados'], collect($cantidad)->map(fn ($valor, $clave) => [Str::headline($clave), $valor])->values());
        if ($this->option('generar-sql')) $this->generarSql($tipos);
        return self::SUCCESS;
    }

    private function municipio(string $nombre, array $tipos): Ubicacion
    {
        $argentina = $this->buscarOGuardar(null, $tipos['pais'], 'Argentina');
        $provincia = $this->buscarOGuardar($argentina, $tipos['provincia'], 'Buenos Aires');
        $zona = $this->buscarOGuardar($provincia, $tipos['zona_comercial'], 'G.B.A. Zona Norte');
        $partido = $this->buscarOGuardar($zona, $tipos['partido'], "Partido de {$nombre}");
        return $this->buscarOGuardar($partido, $tipos['municipio'], "Municipio de {$nombre}");
    }

    private function buscarOGuardar(?Ubicacion $padre, int $tipo, string $nombre): Ubicacion
    {
        $normalizado = Str::lower(trim(Str::ascii($nombre)));
        if ($padre !== null) {
            $existente = Ubicacion::query()->where('ubicacion_padre_id', $padre->id)->where('nombre_normalizado', $normalizado)->first();
            if ($existente !== null) return $existente;
        }
        return $this->guardar($padre, $tipo, $nombre);
    }

    private function guardar(?Ubicacion $padre, int $tipo, string $nombre): Ubicacion
    {
        $nombre = trim($nombre); $normalizado = Str::lower(Str::ascii($nombre));
        if ($this->option('vista-previa')) return new Ubicacion(['tipo_ubicacion_id' => $tipo, 'nombre' => $nombre, 'nombre_normalizado' => $normalizado]);
        return Ubicacion::query()->updateOrCreate(['ubicacion_padre_id' => $padre?->id, 'tipo_ubicacion_id' => $tipo, 'nombre_normalizado' => $normalizado], ['nombre' => $nombre, 'nombre_completo' => ($padre?->nombre_completo ? "{$padre->nombre_completo} | " : '').$nombre, 'pais' => 'Argentina', 'origen' => 'tokko', 'activa' => true]);
    }

    private function generarSql(array $tipos): void
    {
        $codigos = array_flip($tipos);
        $lineas = ['-- Ubicaciones de Zona Norte importadas desde Tokko.', '-- Ejecutar después de crear_base_cmr_inmobiliario.sql.'];
        $ubicaciones = Ubicacion::query()->with('padre')->whereIn('origen', ['georef', 'tokko', 'semilla'])->orderBy('nombre_completo')->get();
        foreach ($ubicaciones as $ubicacion) {
            if ($ubicacion->padre === null) continue;
            $escapar = fn (string $texto) => str_replace("'", "''", $texto);
            $tipo = $codigos[$ubicacion->tipo_ubicacion_id];
            $padre = $escapar($ubicacion->padre->nombre_completo);
            $nombre = $escapar($ubicacion->nombre);
            $normalizado = $escapar($ubicacion->nombre_normalizado);
            $completo = $escapar($ubicacion->nombre_completo);
            $origen = $escapar($ubicacion->origen);
            $idTokko = $ubicacion->id_tokko === null ? 'NULL' : (int) $ubicacion->id_tokko;
            $rutaTokko = $ubicacion->ruta_tokko === null ? 'NULL' : "'".$escapar($ubicacion->ruta_tokko)."'";
            $lineas[] = "INSERT INTO ubicaciones (ubicacion_padre_id,tipo_ubicacion_id,nombre,nombre_normalizado,origen,id_tokko,ruta_tokko,pais,nombre_completo,activa,created_at,updated_at) SELECT padre.id,tipo.id,'{$nombre}','{$normalizado}','{$origen}',{$idTokko},{$rutaTokko},'Argentina','{$completo}',1,NOW(),NOW() FROM ubicaciones padre JOIN tipos_ubicacion tipo WHERE padre.nombre_completo='{$padre}' AND tipo.codigo='{$tipo}' AND NOT EXISTS (SELECT 1 FROM ubicaciones existente WHERE existente.nombre_completo='{$completo}');";
        }
        file_put_contents(database_path('sql/ubicaciones_zona_norte_tokko.sql'), implode(PHP_EOL, $lineas).PHP_EOL);
        $this->info('SQL manual generado: database/sql/ubicaciones_zona_norte_tokko.sql');
    }
}
