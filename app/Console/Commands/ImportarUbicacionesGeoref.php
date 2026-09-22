<?php

namespace App\Console\Commands;

use App\Models\TipoUbicacion;
use App\Models\Ubicacion;
use Illuminate\Console\Command;
use Illuminate\Support\Str;


/**
 * “migrador” de localidades
 * Importa:
 * - Provincias
 * - Departamentos
 * - Municipios
 * - Localidades
 * No importa zonas comerciales, barrios ni subbarrios: esos son datos propios de cada inmobiliaria y se cargan desde el panel de Ubicaciones.
 * 
 * Para usarlo en otra base:
 * 
 * 1- Crear e importar el script SQL
 * 2- Configurás en .env el nombre de la nueva base
 * 3- Copiar dentro de: database/datos/georef/ los CSV de Georef (provincias, departamentos, municipios y localidades)
 * 4- Ejecutar: php artisan ubicaciones:importar-georef 
 * 
 * */
class ImportarUbicacionesGeoref extends Command
{
    protected $signature = 'ubicaciones:importar-georef
                            {--directorio= : Directorio que contiene los CSV de Georef}';

    protected $description = 'Importa provincias, departamentos, municipios y localidades de Georef.';

    /** @var array<string, int> */
    private array $tipos = [];

    /** @var array<string, string> */
    private array $nombresTipos = [];

    /** @var array<string, int> */
    private array $provincias = [];

    /** @var array<string, int> */
    private array $departamentos = [];

    /** @var array<string, int> */
    private array $municipios = [];

    /** @var array<string, int> */
    private array $resultado = [
        'provincias' => 0,
        'departamentos' => 0,
        'municipios' => 0,
        'localidades' => 0,
    ];

    public function handle(): int
    {
        $directorio = $this->option('directorio')
            ?: database_path('datos/georef');

        foreach (['provincias', 'departamentos', 'municipios', 'localidades'] as $archivo) {
            if (!is_file("{$directorio}/{$archivo}.csv")) {
                $this->error("No se encontró {$archivo}.csv en {$directorio}.");

                return self::FAILURE;
            }
        }

        $this->tipos = TipoUbicacion::query()->pluck('id', 'codigo')->all();
        $this->nombresTipos = TipoUbicacion::query()->pluck('nombre', 'codigo')->all();
        $argentina = $this->obtenerArgentina();

        $this->importarProvincias("{$directorio}/provincias.csv", $argentina);
        $this->importarDepartamentos("{$directorio}/departamentos.csv");
        $this->importarMunicipios("{$directorio}/municipios.csv");
        $this->importarLocalidades("{$directorio}/localidades.csv");

        $this->newLine();
        $this->table(['Tipo', 'Registros procesados'], collect($this->resultado)
            ->map(fn(int $cantidad, string $tipo) => [Str::headline($tipo), $cantidad])
            ->values()
            ->all());

        return self::SUCCESS;
    }

    private function obtenerArgentina(): Ubicacion
    {
        return Ubicacion::query()->updateOrCreate(
            [
                'tipo_ubicacion_id' => $this->tipos['pais'],
                'nombre_normalizado' => 'argentina',
            ],
            [
                'ubicacion_padre_id' => null,
                'nombre' => 'Argentina',
                'nombre_completo' => 'Argentina',
                'pais' => 'Argentina',
                'origen' => 'georef',
                'activa' => true,
            ]
        );
    }

    private function importarProvincias(string $archivo, Ubicacion $argentina): void
    {
        foreach ($this->leerCsv($archivo) as $fila) {
            $ubicacion = $this->guardarUbicacion(
                'provincia',
                $fila['id'],
                $fila['nombre'],
                $argentina->id
            );

            $this->provincias[$fila['id']] = $ubicacion->id;
            $this->resultado['provincias']++;
        }
    }

    private function importarDepartamentos(string $archivo): void
    {
        foreach ($this->leerCsv($archivo) as $fila) {
            $padreId = $this->provincias[$fila['provincia_id']] ?? null;

            if ($padreId === null) {
                $this->warn("Departamento omitido sin provincia: {$fila['nombre']}.");

                continue;
            }

            $ubicacion = $this->guardarUbicacion(
                'departamento',
                $fila['id'],
                $fila['nombre'],
                $padreId
            );

            $this->departamentos[$fila['id']] = $ubicacion->id;
            $this->resultado['departamentos']++;
        }
    }

    private function importarMunicipios(string $archivo): void
    {
        foreach ($this->leerCsv($archivo) as $fila) {
            $padreId = $this->provincias[$fila['provincia_id']] ?? null;
            $codigo = $fila['gobierno_local_id'] ?? null;
            $nombre = $fila['gobierno_local_nombre'] ?? null;

            if ($padreId === null || blank($codigo) || blank($nombre)) {
                $this->warn('Municipio omitido por datos incompletos de Georef.');

                continue;
            }

            $ubicacion = $this->guardarUbicacion('municipio', $codigo, $nombre, $padreId);
            $this->municipios[$codigo] = $ubicacion->id;
            $this->resultado['municipios']++;
        }
    }

    private function importarLocalidades(string $archivo): void
    {
        foreach ($this->leerCsv($archivo) as $fila) {
            $padreId = $this->municipios[$fila['gobierno_local_id'] ?? '']
                ?? $this->departamentos[$fila['departamento_id'] ?? '']
                ?? $this->provincias[$fila['provincia_id']]
                ?? null;

            if ($padreId === null) {
                $this->warn("Localidad omitida sin ubicación padre: {$fila['nombre']}.");

                continue;
            }

            $this->guardarUbicacion('localidad', $fila['id'], $fila['nombre'], $padreId);
            $this->resultado['localidades']++;
        }
    }

    private function guardarUbicacion(
        string $codigoTipo,
        string $codigoGeoref,
        string $nombre,
        int $ubicacionPadreId
    ): Ubicacion {
        $tipoId = $this->tipos[$codigoTipo];
        $nombreNormalizado = $this->normalizarNombre($nombre);
        $padre = Ubicacion::query()->findOrFail($ubicacionPadreId);

        $ubicacion = Ubicacion::query()
            ->where('tipo_ubicacion_id', $tipoId)
            ->where('codigo_georef', $codigoGeoref)
            ->first();

        if ($ubicacion === null) {
            $ubicacion = Ubicacion::query()
                ->where('ubicacion_padre_id', $ubicacionPadreId)
                ->where('tipo_ubicacion_id', $tipoId)
                ->where('nombre_normalizado', $nombreNormalizado)
                ->first();
        }

        $nombreCompleto = "{$padre->nombre_completo} | {$nombre}";

        if ($ubicacion === null) {
            $coincidente = Ubicacion::query()
                ->where('nombre_completo', $nombreCompleto)
                ->first();

            if ($coincidente?->tipo_ubicacion_id === $tipoId) {
                $ubicacion = $coincidente;
            }
        }

        $datos = [
            'ubicacion_padre_id' => $ubicacionPadreId,
            'tipo_ubicacion_id' => $tipoId,
            'nombre' => $nombre,
            'nombre_normalizado' => $nombreNormalizado,
            'codigo_georef' => $codigoGeoref,
            'nombre_completo' => $nombreCompleto,
            'pais' => 'Argentina',
            'origen' => 'georef',
            'activa' => true,
        ];

        if ($ubicacion !== null) {
            $ubicacion->update($datos);

            return $ubicacion;
        }

        return Ubicacion::query()->create($datos);
    }

    /** @return \Generator<int, array<string, string>> */
    private function leerCsv(string $archivo): \Generator
    {
        $manejador = fopen($archivo, 'rb');

        if ($manejador === false) {
            throw new \RuntimeException("No se pudo abrir {$archivo}.");
        }

        $encabezados = fgetcsv($manejador);

        if ($encabezados === false) {
            fclose($manejador);

            throw new \RuntimeException("El archivo {$archivo} no tiene encabezados.");
        }

        $encabezados[0] = preg_replace('/^\xEF\xBB\xBF/', '', $encabezados[0]);

        while (($fila = fgetcsv($manejador)) !== false) {
            if (count($fila) !== count($encabezados)) {
                continue;
            }

            yield array_combine($encabezados, $fila);
        }

        fclose($manejador);
    }

    private function normalizarNombre(string $nombre): string
    {
        return Str::lower(trim(Str::ascii($nombre)));
    }
}
