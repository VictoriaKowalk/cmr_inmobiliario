<?php

namespace App\Services;

use App\Models\ImagenPropiedad;
use App\Models\Propiedad;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ServicioImagenesPropiedad
{
    /**
     * @param  array<int, UploadedFile>  $archivos
     */
    public function guardarImagenes(
        Propiedad $propiedad,
        array $archivos
    ): void {
        $rutasGuardadas = [];

        try {
            DB::transaction(function () use (
                $propiedad,
                $archivos,
                &$rutasGuardadas
            ): void {
                $orden = ((int) $propiedad->imagenes()->max('orden')) + 1;
                $tienePortada = $propiedad->imagenes()
                    ->where('portada', true)
                    ->exists();

                foreach ($archivos as $archivo) {
                    $ruta = $this->guardarArchivoOptimizado(
                        $propiedad,
                        $archivo
                    );

                    if (! $ruta) {
                        throw new RuntimeException('No se pudo guardar una imagen.');
                    }

                    $rutasGuardadas[] = $ruta;

                    $propiedad->imagenes()->create([
                        'ruta' => $ruta,
                        'nombre_original' => $archivo->getClientOriginalName(),
                        'orden' => $orden,
                        'portada' => ! $tienePortada,
                    ]);

                    $tienePortada = true;
                    $orden++;
                }
            });
        } catch (Throwable $error) {
            Storage::disk('public')->delete($rutasGuardadas);
            throw $error;
        }
    }

    public function reordenarImagenes(
        Propiedad $propiedad,
        array $ordenes
    ): void {
        $imagenes = $propiedad->imagenes()
            ->whereIn('id', array_keys($ordenes))
            ->get();

        abort_unless(
            $imagenes->count() === count($ordenes),
            422,
            'Una de las imágenes no pertenece a la propiedad.'
        );

        DB::transaction(function () use ($imagenes, $ordenes): void {
            foreach ($imagenes as $imagen) {
                $imagen->update([
                    'orden' => (int) $ordenes[$imagen->id],
                ]);
            }
        });
    }

    public function marcarPortada(ImagenPropiedad $imagen): void
    {
        DB::transaction(function () use ($imagen): void {
            ImagenPropiedad::query()
                ->where('propiedad_id', $imagen->propiedad_id)
                ->where('portada', true)
                ->update(['portada' => false]);

            $imagen->update(['portada' => true]);
        });
    }

    public function eliminarImagen(ImagenPropiedad $imagen): void
    {
        $propiedad = $imagen->propiedad;
        $eraPortada = $imagen->portada;
        $ruta = $imagen->ruta;

        DB::transaction(function () use (
            $imagen,
            $propiedad,
            $eraPortada
        ): void {
            $imagen->delete();

            if ($eraPortada) {
                $siguiente = $propiedad->imagenes()
                    ->orderBy('orden')
                    ->orderBy('id')
                    ->first();

                $siguiente?->update(['portada' => true]);
            }
        });

        Storage::disk('public')->delete($ruta);
    }

    private function generarNombreArchivo(UploadedFile $archivo): string
    {
        $extension = strtolower(
            $archivo->guessExtension()
                ?: $archivo->getClientOriginalExtension()
                ?: 'jpg'
        );

        return Str::uuid()->toString().'.'.$extension;
    }

    private function guardarArchivoOptimizado(
        Propiedad $propiedad,
        UploadedFile $archivo
    ): string|false {
        if (! function_exists('imagecreatefromstring')
            || ! function_exists('imagewebp')) {
            return $archivo->storeAs(
                "propiedades/{$propiedad->id}",
                $this->generarNombreArchivo($archivo),
                'public'
            );
        }

        $contenido = file_get_contents($archivo->getRealPath());
        $imagen = $contenido !== false
            ? @imagecreatefromstring($contenido)
            : false;

        if ($imagen === false) {
            throw new RuntimeException('No se pudo procesar una de las imágenes.');
        }

        try {
            $ancho = imagesx($imagen);
            $alto = imagesy($imagen);
            $maximo = max(800, (int) config('services.imagenes.max_dimension', 2400));
            $escala = min(1, $maximo / max($ancho, $alto));
            $anchoFinal = max(1, (int) round($ancho * $escala));
            $altoFinal = max(1, (int) round($alto * $escala));
            $destino = imagecreatetruecolor($anchoFinal, $altoFinal);

            if ($destino === false) {
                throw new RuntimeException('No se pudo preparar la imagen.');
            }

            imagealphablending($destino, false);
            imagesavealpha($destino, true);
            $transparente = imagecolorallocatealpha($destino, 0, 0, 0, 127);
            imagefilledrectangle(
                $destino,
                0,
                0,
                $anchoFinal,
                $altoFinal,
                $transparente
            );
            imagecopyresampled(
                $destino,
                $imagen,
                0,
                0,
                0,
                0,
                $anchoFinal,
                $altoFinal,
                $ancho,
                $alto
            );

            ob_start();
            $guardada = imagewebp(
                $destino,
                null,
                (int) config('services.imagenes.calidad_webp', 82)
            );
            $webp = ob_get_clean();
            imagedestroy($destino);

            if (! $guardada || $webp === false) {
                throw new RuntimeException('No se pudo optimizar la imagen.');
            }

            $ruta = "propiedades/{$propiedad->id}/".Str::uuid().'.webp';

            return Storage::disk('public')->put($ruta, $webp)
                ? $ruta
                : false;
        } finally {
            imagedestroy($imagen);
        }
    }
}
