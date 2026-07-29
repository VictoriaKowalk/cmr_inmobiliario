<?php

namespace Database\Seeders;

use App\Enums\CategoriaCaracteristica;
use App\Models\Caracteristica;
use Illuminate\Database\Seeder;

class CaracteristicasSeeder extends Seeder
{
    public function run(): void
    {
        $caracteristicas = [
            CategoriaCaracteristica::SERVICIO->value => [
                'Agua Corriente',
                'Cloaca',
                'Gas Natural',
                'Internet',
                'Electricidad',
                'Pavimento',
                'Teléfono',
                'Cable',
            ],
            CategoriaCaracteristica::AMBIENTE->value => [
                'Altillo',
                'Balcón',
                'Baulera',
                'Cocina',
                'Comedor diario',
                'Dependencia',
                'Oficina',
                'Hall',
                'Jardín',
                'Lavadero',
                'Living comedor',
                'Patio',
                'Sótano',
                'Terraza',
                'Toilette',
                'Vestidor',
            ],
            CategoriaCaracteristica::CARTEL->value => [
                'Tiene cartel',
                'Sin cartel',
            ],
            CategoriaCaracteristica::OBSERVACION->value => [
                'Oportunidad',
                'Acepta Lote',
                'Acepta Permuta',
                'Apto Credito',
                'Venta Con Renta',
                'Acepta mascotas',
                'Apto profesional',
                'Propiedad destacada',
            ],
            CategoriaCaracteristica::PREFERENCIA_LOTE->value => [
                'Al Rio',
                'Interno',
                'Al Golf',
                'Al lago',
                'Perimetral',
                'Lindero Interno',
            ],
            CategoriaCaracteristica::AMENITY->value => [
                'Aire Acondicionado individual',
                'Alarma',
                'Amoblado',
                'Calefacción',
                'Centro de deportes',
                'Gimnasio',
                'Hidromasaje',
                'Parrilla',
                'Quincho',
                'Sala de juegos',
                'Sauna',
                'Solarium',
                'SUM',
                'Cancha de Paddle',
                'Pileta',
                'Riego automático',
                'Seguridad Privada',
                'Luminoso',
                'Amarra',
                'Laundry',
                'Seguridad 24hs',
            ],
        ];

        foreach ($caracteristicas as $categoria => $nombres) {
            foreach ($nombres as $nombre) {
                Caracteristica::query()->updateOrCreate(
                    [
                        'nombre' => $nombre,
                        'categoria' => $categoria,
                    ],
                    ['activa' => true]
                );
            }
        }
    }
}
