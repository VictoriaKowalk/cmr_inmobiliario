<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_ubicacion', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->string('nombre', 100);
            $table->unsignedTinyInteger('orden');
            $table->boolean('activo')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('reglas_tipos_ubicacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_ubicacion_padre_id')
                ->constrained('tipos_ubicacion')
                ->cascadeOnDelete();
            $table->foreignId('tipo_ubicacion_hijo_id')
                ->constrained('tipos_ubicacion')
                ->cascadeOnDelete();
            $table->boolean('activa')->default(true)->index();
            $table->timestamps();

            $table->unique(
                ['tipo_ubicacion_padre_id', 'tipo_ubicacion_hijo_id'],
                'reglas_tipos_ubicacion_padre_hijo_unica'
            );
        });

        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->foreignId('ubicacion_padre_id')
                ->nullable()
                ->after('id')
                ->constrained('ubicaciones')
                ->restrictOnDelete();
            $table->foreignId('tipo_ubicacion_id')
                ->nullable()
                ->after('ubicacion_padre_id')
                ->constrained('tipos_ubicacion')
                ->restrictOnDelete();
            $table->string('nombre', 150)->nullable()->after('tipo_ubicacion_id');
            $table->string('nombre_normalizado', 150)->nullable()->after('nombre');
            $table->string('codigo_georef', 100)->nullable()->index()->after('nombre_normalizado');
            $table->string('origen', 30)->default('personalizado')->index()->after('codigo_georef');

            $table->index(
                ['ubicacion_padre_id', 'tipo_ubicacion_id'],
                'ubicaciones_padre_tipo_indice'
            );
        });

        $ahora = now();
        $tipos = [
            ['codigo' => 'pais', 'nombre' => 'País', 'orden' => 1],
            ['codigo' => 'provincia', 'nombre' => 'Provincia', 'orden' => 2],
            ['codigo' => 'zona_comercial', 'nombre' => 'Zona comercial', 'orden' => 3],
            ['codigo' => 'partido', 'nombre' => 'Partido', 'orden' => 4],
            ['codigo' => 'departamento', 'nombre' => 'Departamento', 'orden' => 4],
            ['codigo' => 'comuna', 'nombre' => 'Comuna', 'orden' => 4],
            ['codigo' => 'municipio', 'nombre' => 'Municipio', 'orden' => 5],
            ['codigo' => 'localidad', 'nombre' => 'Localidad', 'orden' => 6],
            ['codigo' => 'barrio', 'nombre' => 'Barrio', 'orden' => 7],
            ['codigo' => 'subbarrio', 'nombre' => 'Subbarrio', 'orden' => 8],
        ];

        DB::table('tipos_ubicacion')->insert(
            array_map(
                fn (array $tipo) => [...$tipo, 'activo' => true, 'created_at' => $ahora, 'updated_at' => $ahora],
                $tipos
            )
        );

        $identificadoresTipos = DB::table('tipos_ubicacion')
            ->pluck('id', 'codigo');

        $reglas = [
            ['pais', 'provincia'],
            ['provincia', 'zona_comercial'],
            ['provincia', 'partido'],
            ['provincia', 'departamento'],
            ['provincia', 'comuna'],
            ['provincia', 'municipio'],
            ['provincia', 'localidad'],
            ['zona_comercial', 'partido'],
            ['zona_comercial', 'departamento'],
            ['zona_comercial', 'comuna'],
            ['zona_comercial', 'municipio'],
            ['zona_comercial', 'localidad'],
            ['partido', 'municipio'],
            ['partido', 'localidad'],
            ['partido', 'barrio'],
            ['departamento', 'municipio'],
            ['departamento', 'localidad'],
            ['departamento', 'barrio'],
            ['comuna', 'localidad'],
            ['comuna', 'barrio'],
            ['municipio', 'localidad'],
            ['municipio', 'barrio'],
            ['localidad', 'barrio'],
            ['localidad', 'subbarrio'],
            ['barrio', 'subbarrio'],
        ];

        DB::table('reglas_tipos_ubicacion')->insert(
            array_map(
                fn (array $regla) => [
                    'tipo_ubicacion_padre_id' => $identificadoresTipos[$regla[0]],
                    'tipo_ubicacion_hijo_id' => $identificadoresTipos[$regla[1]],
                    'activa' => true,
                    'created_at' => $ahora,
                    'updated_at' => $ahora,
                ],
                $reglas
            )
        );

        $this->migrarUbicacionesExistentes($identificadoresTipos->all());

        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->unique(
                ['ubicacion_padre_id', 'tipo_ubicacion_id', 'nombre_normalizado'],
                'ubicaciones_padre_tipo_nombre_unico'
            );
        });
    }

    public function down(): void
    {
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropUnique('ubicaciones_padre_tipo_nombre_unico');
            $table->dropIndex('ubicaciones_padre_tipo_indice');
            $table->dropForeign(['ubicacion_padre_id']);
            $table->dropForeign(['tipo_ubicacion_id']);
            $table->dropColumn([
                'ubicacion_padre_id',
                'tipo_ubicacion_id',
                'nombre',
                'nombre_normalizado',
                'codigo_georef',
                'origen',
            ]);
        });

        Schema::dropIfExists('reglas_tipos_ubicacion');
        Schema::dropIfExists('tipos_ubicacion');
    }

    /**
     * Conserva los ID de las ubicaciones utilizadas por propiedades existentes.
     * Las columnas planas se mantienen durante la transición de la aplicación.
     *
     * @param array<string, int> $identificadoresTipos
     */
    private function migrarUbicacionesExistentes(array $identificadoresTipos): void
    {
        $ubicaciones = DB::table('ubicaciones')
            ->whereNull('tipo_ubicacion_id')
            ->orderBy('id')
            ->get();

        foreach ($ubicaciones as $ubicacion) {
            $niveles = [
                ['campo' => 'pais', 'tipo' => 'pais'],
                ['campo' => 'zona', 'tipo' => 'zona_comercial'],
                ['campo' => 'localidad', 'tipo' => 'localidad'],
                ['campo' => 'barrio_principal', 'tipo' => 'barrio'],
                ['campo' => 'barrio', 'tipo' => 'subbarrio'],
            ];

            $nivelesConValor = array_values(array_filter(
                $niveles,
                fn (array $nivel) => filled($ubicacion->{$nivel['campo']})
            ));

            $padreId = null;
            $ruta = [];

            foreach ($nivelesConValor as $indice => $nivel) {
                $nombre = trim((string) $ubicacion->{$nivel['campo']});
                $ruta[] = $nombre;
                $esUltimoNivel = $indice === array_key_last($nivelesConValor);
                $datos = [
                    'ubicacion_padre_id' => $padreId,
                    'tipo_ubicacion_id' => $identificadoresTipos[$nivel['tipo']],
                    'nombre' => $nombre,
                    'nombre_normalizado' => $this->normalizarNombre($nombre),
                    'origen' => 'migrado',
                    'updated_at' => now(),
                ];

                if ($esUltimoNivel) {
                    DB::table('ubicaciones')
                        ->where('id', $ubicacion->id)
                        ->update($datos);

                    $padreId = $ubicacion->id;

                    continue;
                }

                $nodo = DB::table('ubicaciones')
                    ->where('ubicacion_padre_id', $padreId)
                    ->where('tipo_ubicacion_id', $identificadoresTipos[$nivel['tipo']])
                    ->where('nombre_normalizado', $datos['nombre_normalizado'])
                    ->first();

                if ($nodo === null) {
                    $padreId = DB::table('ubicaciones')->insertGetId([
                        ...$datos,
                        'pais' => $ubicacion->pais ?: 'Argentina',
                        'nombre_completo' => implode(' | ', $ruta),
                        'activa' => $ubicacion->activa,
                        'created_at' => now(),
                    ]);
                } else {
                    $padreId = $nodo->id;
                }
            }
        }
    }

    private function normalizarNombre(string $nombre): string
    {
        return Str::lower(trim(Str::ascii($nombre)));
    }
};
