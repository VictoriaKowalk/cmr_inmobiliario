<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;
use ZipArchive;

class CrearBackupSistema extends Command
{
    protected $signature = 'sistema:backup {--force : Ejecutar aunque BACKUP_ENABLED sea false}';

    protected $description = 'Crea un backup comprimido de MySQL y los archivos públicos';

    public function handle(): int
    {
        if (! config('backup.habilitado') && ! $this->option('force')) {
            $this->warn('Los backups están deshabilitados. Configurá BACKUP_ENABLED=true.');

            return self::FAILURE;
        }

        if (config('database.default') !== 'mysql') {
            $this->error('El comando de producción requiere una conexión MySQL.');

            return self::FAILURE;
        }

        $directorio = (string) config('backup.ruta');
        File::ensureDirectoryExists($directorio);
        $temporal = $directorio.DIRECTORY_SEPARATOR.'tmp-'.str()->uuid();
        File::ensureDirectoryExists($temporal);

        try {
            $sql = $temporal.DIRECTORY_SEPARATOR.'base-datos.sql';
            $this->exportarMysql($sql);

            $nombre = 'backup-'.now()->format('Y-m-d_H-i-s').'.zip';
            $destino = $directorio.DIRECTORY_SEPARATOR.$nombre;
            $this->crearZip($destino, $sql);
            $this->eliminarBackupsVencidos($directorio);

            $this->info("Backup creado: {$destino}");

            return self::SUCCESS;
        } catch (Throwable $error) {
            report($error);
            $this->error('No se pudo crear el backup: '.$error->getMessage());

            return self::FAILURE;
        } finally {
            File::deleteDirectory($temporal);
        }
    }

    private function exportarMysql(string $destino): void
    {
        $conexion = config('database.connections.mysql');
        $proceso = new Process([
            (string) config('backup.mysqldump'),
            '--single-transaction',
            '--quick',
            '--skip-lock-tables',
            '--host='.(string) $conexion['host'],
            '--port='.(string) $conexion['port'],
            '--user='.(string) $conexion['username'],
            '--result-file='.$destino,
            (string) $conexion['database'],
        ]);
        $proceso->setTimeout(600);
        $proceso->setEnv([
            ...getenv(),
            'MYSQL_PWD' => (string) ($conexion['password'] ?? ''),
        ]);
        $proceso->run();

        if (! $proceso->isSuccessful() || ! File::exists($destino)) {
            throw new RuntimeException(
                trim($proceso->getErrorOutput()) ?: 'mysqldump no generó el archivo.'
            );
        }
    }

    private function crearZip(string $destino, string $sql): void
    {
        $zip = new ZipArchive;

        if ($zip->open($destino, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('No se pudo crear el archivo ZIP.');
        }

        $zip->addFile($sql, 'base-datos.sql');
        $directorioArchivos = storage_path('app/public');

        if (File::isDirectory($directorioArchivos)) {
            foreach (File::allFiles($directorioArchivos) as $archivo) {
                $relativa = str_replace(
                    '\\',
                    '/',
                    $archivo->getRelativePathname()
                );
                $zip->addFile($archivo->getPathname(), 'archivos/'.$relativa);
            }
        }

        if (! $zip->close()) {
            throw new RuntimeException('No se pudo finalizar el archivo ZIP.');
        }
    }

    private function eliminarBackupsVencidos(string $directorio): void
    {
        $dias = max(1, (int) config('backup.retencion_dias', 14));
        $limite = now()->subDays($dias)->getTimestamp();

        foreach (File::glob($directorio.DIRECTORY_SEPARATOR.'backup-*.zip') as $archivo) {
            if (File::lastModified($archivo) < $limite) {
                File::delete($archivo);
            }
        }
    }
}
