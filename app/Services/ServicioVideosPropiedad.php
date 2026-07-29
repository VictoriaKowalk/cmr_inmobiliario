<?php

namespace App\Services;

use App\Models\Propiedad;
use App\Models\VideoPropiedad;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ServicioVideosPropiedad
{
    public function guardarVideoDesdeYoutube(
        Propiedad $propiedad,
        string $url,
        ?string $titulo = null
    ): void {
        $youtubeId = $this->extraerYoutubeId($url);

        if (! $youtubeId) {
            throw new InvalidArgumentException('La URL de YouTube no es valida.');
        }

        $propiedad->videos()->create([
            'tipo' => 'youtube',
            'titulo' => $titulo,
            'url' => $url,
            'youtube_id' => $youtubeId,
            'orden' => $this->siguienteOrden($propiedad),
        ]);
    }

    public function eliminarVideo(VideoPropiedad $video): void
    {
        $ruta = $video->ruta;

        $video->delete();

        if ($ruta) {
            Storage::disk('public')->delete($ruta);
        }
    }

    private function siguienteOrden(Propiedad $propiedad): int
    {
        return ((int) $propiedad->videos()->max('orden')) + 1;
    }

    private function extraerYoutubeId(string $url): ?string
    {
        $partes = parse_url($url);

        if (! $partes || empty($partes['host'])) {
            return null;
        }

        $host = strtolower($partes['host']);
        $path = trim($partes['path'] ?? '', '/');

        if (str_contains($host, 'youtu.be') && $path !== '') {
            return Str::limit($path, 50, '');
        }

        if (str_contains($host, 'youtube.com')) {
            parse_str($partes['query'] ?? '', $query);

            if (! empty($query['v'])) {
                return Str::limit((string) $query['v'], 50, '');
            }

            if (str_starts_with($path, 'embed/')) {
                return Str::limit(substr($path, 6), 50, '');
            }

            if (str_starts_with($path, 'shorts/')) {
                return Str::limit(substr($path, 7), 50, '');
            }
        }

        return null;
    }
}
