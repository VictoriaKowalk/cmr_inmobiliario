<?php

declare(strict_types=1);

require dirname(__DIR__).'/vendor/autoload.php';

$app = require dirname(__DIR__).'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$conexion = Illuminate\Support\Facades\DB::connection();
$driver = $conexion->getDriverName();

echo "Motor: {$driver}\n";

$tablas = $driver === 'mysql'
    ? collect($conexion->select('SHOW TABLES'))->map(fn (object $fila) => array_values((array) $fila)[0])
    : collect($conexion->select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'"))->pluck('name');

foreach ($tablas->sort()->values() as $tabla) {
    $cantidad = $conexion->table($tabla)->count();
    echo str_pad($tabla, 32)." {$cantidad}\n";
}
