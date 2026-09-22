<?php

namespace App\Console\Commands;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DescargarUbicacionesTokkoZonaNorte extends Command
{
    protected $signature = 'ubicaciones:descargar-tokko-zona-norte
                            {--limite=20 : Cantidad máxima de fichas a descargar en esta ejecución}
                            {--pausa=3 : Segundos de espera entre cada consulta}';

    protected $description = 'Descarga por lotes las fichas públicas de Tokko de Zona Norte, respetando el límite de consultas.';

    public function handle(): int
    {
        $directorioRaiz = database_path('datos/tokko/zona-norte');
        $directorioNodos = "{$directorioRaiz}/nodos";
        $limite = max(1, (int) $this->option('limite'));
        $pausa = max(1, (int) $this->option('pausa'));

        if (!is_dir($directorioRaiz)) {
            $this->error('No se encontraron los nueve archivos raíz de Zona Norte.');

            return self::FAILURE;
        }

        if (!is_dir($directorioNodos) && !mkdir($directorioNodos, 0755, true) && !is_dir($directorioNodos)) {
            $this->error('No se pudo crear el directorio de fichas de Tokko.');

            return self::FAILURE;
        }

        $identificadores = $this->obtenerIdentificadores($directorioRaiz);
        $pendientes = array_values(array_filter(
            $identificadores,
            fn (int $id) => !is_file("{$directorioNodos}/{$id}.json")
        ));

        if ($pendientes === []) {
            $this->info('No hay fichas de primer nivel pendientes de descargar.');

            return self::SUCCESS;
        }

        $descargados = 0;

        foreach (array_slice($pendientes, 0, $limite) as $id) {
            try {
                $respuesta = Http::acceptJson()
                    ->withOptions([
                        'verify' => database_path('certificados/cacert.pem'),
                    ])
                    ->timeout(20)
                    ->get("https://www.tokkobroker.com/api/v1/location/{$id}/", [
                        'lang' => 'es_ar',
                        'format' => 'json',
                    ]);
            } catch (ConnectionException $exception) {
                $this->error('No se pudo establecer una conexión segura con Tokko. Verificá database/certificados/cacert.pem.');

                return self::FAILURE;
            }

            if ($respuesta->status() === 429) {
                $this->warn("Tokko limitó las consultas después de {$descargados} descargas. Volvé a ejecutar el comando más tarde.");

                break;
            }

            if (!$respuesta->successful()) {
                $this->warn("No se pudo descargar la ficha {$id} (HTTP {$respuesta->status()}).");

                continue;
            }

            file_put_contents(
                "{$directorioNodos}/{$id}.json",
                json_encode($respuesta->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
            );

            $descargados++;
            $this->line("Descargada ficha {$id} ({$descargados}/{$limite}).");

            if ($descargados < $limite) {
                sleep($pausa);
            }
        }

        $restantes = count($pendientes) - $descargados;
        $this->info("Descargadas: {$descargados}. Pendientes de primer nivel: {$restantes}.");

        return self::SUCCESS;
    }

    /** @return array<int, int> */
    private function obtenerIdentificadores(string $directorioRaiz): array
    {
        $identificadores = [];

        foreach (glob("{$directorioRaiz}/*.json") ?: [] as $archivo) {
            $datos = json_decode((string) file_get_contents($archivo), true, 512, JSON_THROW_ON_ERROR);

            foreach ($datos['divisions'] ?? [] as $division) {
                if (isset($division['id'])) {
                    $identificadores[] = (int) $division['id'];
                }
            }
        }

        sort($identificadores);

        return array_values(array_unique($identificadores));
    }
}
