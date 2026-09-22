<?php

namespace Database\Seeders;

use App\Models\TipoUbicacion;
use App\Models\Ubicacion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UbicacionesTokkoNordeltaSeeder extends Seeder
{
    private const RUTA_NORDELTA_TOKKO = [
        'Argentina',
        'G.B.A. Zona Norte',
        'Tigre',
        'Countries/B.Cerrado (Tigre)',
        'Nordelta',
    ];

    public function run(): void
    {
        $archivo = database_path('datos/tokko/nordelta.json');

        if (!is_file($archivo)) {
            $this->command?->warn('No se encontró database/datos/tokko/nordelta.json.');

            return;
        }

        $datos = json_decode((string) file_get_contents($archivo), true, 512, JSON_THROW_ON_ERROR);
        $tipos = TipoUbicacion::query()->pluck('id', 'codigo');
        $nordelta = $this->obtenerNordelta($tipos['localidad']);
        $barrios = [];
        $cantidadBarrios = 0;
        $cantidadSubbarrios = 0;

        foreach ($datos['objects'] ?? [] as $registro) {
            if (($registro['type'] ?? null) !== 'Barrio') {
                continue;
            }

            $partes = $this->partesDeRuta((string) ($registro['full_location'] ?? ''));

            if (!$this->perteneceANordelta($partes)) {
                continue;
            }

            $nivelesPosteriores = array_slice($partes, count(self::RUTA_NORDELTA_TOKKO));

            if (count($nivelesPosteriores) !== 1) {
                continue;
            }

            // Tokko también devuelve “Nordelta” como barrio. Es un duplicado
            // de la localidad padre y no debe generar la ruta Nordelta > Nordelta.
            if ($this->normalizarNombre($nivelesPosteriores[0]) === 'nordelta') {
                continue;
            }

            $barrio = $this->guardarUbicacion(
                $nordelta,
                $tipos['barrio'],
                $nivelesPosteriores[0]
            );
            $barrios[$this->normalizarNombre($barrio->nombre)] = $barrio;
            $cantidadBarrios++;
        }

        foreach ($datos['objects'] ?? [] as $registro) {
            if (($registro['type'] ?? null) !== 'Barrio') {
                continue;
            }

            $partes = $this->partesDeRuta((string) ($registro['full_location'] ?? ''));

            if (!$this->perteneceANordelta($partes)) {
                continue;
            }

            $nivelesPosteriores = array_slice($partes, count(self::RUTA_NORDELTA_TOKKO));

            if (count($nivelesPosteriores) !== 2) {
                continue;
            }

            $barrioPadre = $barrios[$this->normalizarNombre($nivelesPosteriores[0])] ?? null;

            if ($barrioPadre === null) {
                continue;
            }

            $this->guardarUbicacion($barrioPadre, $tipos['subbarrio'], $nivelesPosteriores[1]);
            $cantidadSubbarrios++;
        }

        $this->command?->info("Ubicaciones Tokko de Nordelta: {$cantidadBarrios} barrios y {$cantidadSubbarrios} subbarrios procesados.");
    }

    private function obtenerNordelta(int $tipoLocalidadId): Ubicacion
    {
        return Ubicacion::query()
            ->where('tipo_ubicacion_id', $tipoLocalidadId)
            ->where('nombre_normalizado', 'nordelta')
            ->orderBy('id')
            ->firstOrFail();
    }

    private function guardarUbicacion(Ubicacion $padre, int $tipoId, string $nombre): Ubicacion
    {
        $nombre = trim($nombre);
        $nombreNormalizado = $this->normalizarNombre($nombre);

        return Ubicacion::query()->updateOrCreate(
            [
                'ubicacion_padre_id' => $padre->id,
                'tipo_ubicacion_id' => $tipoId,
                'nombre_normalizado' => $nombreNormalizado,
            ],
            [
                'nombre' => $nombre,
                'nombre_completo' => "{$padre->nombre_completo} | {$nombre}",
                'pais' => 'Argentina',
                'origen' => 'tokko',
                'activa' => true,
            ]
        );
    }

    /** @return array<int, string> */
    private function partesDeRuta(string $ruta): array
    {
        return array_values(array_filter(array_map('trim', explode('|', $ruta))));
    }

    /** @param array<int, string> $partes */
    private function perteneceANordelta(array $partes): bool
    {
        return array_slice($partes, 0, count(self::RUTA_NORDELTA_TOKKO)) === self::RUTA_NORDELTA_TOKKO;
    }

    private function normalizarNombre(string $nombre): string
    {
        return Str::lower(trim(Str::ascii($nombre)));
    }
}
