<?php

namespace Database\Seeders;

use App\Enums\EstadoSeguimiento;
use App\Models\Consulta;
use App\Models\Propiedad;
use App\Models\Tasacion;
use App\Models\TipoPropiedad;
use Illuminate\Database\Seeder;

class ContactosDemoSeeder extends Seeder
{
    public function run(): void
    {
        $propiedades = Propiedad::query()
            ->with('operaciones')
            ->limit(3)
            ->get();

        $consultas = [
            [
                'nombre' => 'Mariana Lopez',
                'email' => 'mariana.lopez@example.com',
                'telefono' => '+54 9 11 5020-1144',
                'mensaje' => 'Hola, me interesa coordinar una visita esta semana. Quisiera saber si la propiedad sigue disponible.',
                'estado_seguimiento' => EstadoSeguimiento::NUEVA,
                'leida_en' => null,
                'atendida_en' => null,
                'notas_internas' => null,
            ],
            [
                'nombre' => 'Federico Martin',
                'email' => 'federico.martin@example.com',
                'telefono' => '+54 9 11 6314-9021',
                'mensaje' => 'Estoy buscando una propiedad para alquilar con cochera. Puedo visitar por la tarde.',
                'estado_seguimiento' => EstadoSeguimiento::EN_SEGUIMIENTO,
                'leida_en' => now()->subDays(2),
                'atendida_en' => null,
                'notas_internas' => 'Responder con opciones similares si esta unidad no está disponible.',
            ],
            [
                'nombre' => 'Valeria Gomez',
                'email' => 'valeria.gomez@example.com',
                'telefono' => '+54 9 11 3848-7710',
                'mensaje' => 'Necesito más información sobre gastos, expensas y medios de pago.',
                'estado_seguimiento' => EstadoSeguimiento::CONTACTADA,
                'leida_en' => now()->subDays(4),
                'atendida_en' => now()->subDays(3),
                'notas_internas' => 'Se envió información por WhatsApp. Esperar confirmación de visita.',
            ],
            [
                'nombre' => 'Santiago Perez',
                'email' => 'santiago.perez@example.com',
                'telefono' => null,
                'mensaje' => 'Consulta general: busco casa en zona norte, mínimo 3 dormitorios.',
                'estado_seguimiento' => EstadoSeguimiento::CERRADA,
                'leida_en' => now()->subDays(7),
                'atendida_en' => now()->subDays(6),
                'notas_internas' => 'Se cerró porque compró por otra inmobiliaria.',
            ],
        ];

        foreach ($consultas as $indice => $datos) {
            $propiedad = $propiedades->get($indice);
            $operacion = $propiedad?->operaciones->first();

            Consulta::query()->updateOrCreate(
                ['email' => $datos['email'], 'mensaje' => $datos['mensaje']],
                [
                    ...$datos,
                    'estado_seguimiento' => $datos['estado_seguimiento']->value,
                    'propiedad_id' => $propiedad?->id,
                    'operacion_propiedad_id' => $operacion?->id,
                ],
            );
        }

        $tipos = TipoPropiedad::query()
            ->whereIn('nombre', ['Casa', 'Departamento', 'Terreno'])
            ->pluck('id', 'nombre');

        $tasaciones = [
            [
                'nombre' => 'Carolina Suarez',
                'email' => 'carolina.suarez@example.com',
                'telefono' => '+54 9 11 5122-3001',
                'tipo_propiedad_id' => $tipos->get('Casa'),
                'ubicacion_texto' => 'Nordelta, Tigre',
                'direccion' => 'Barrio Los Lagos, lote interno',
                'mensaje' => 'Quiero tasar una casa de 4 ambientes con pileta para posible venta.',
                'estado_seguimiento' => EstadoSeguimiento::NUEVA,
                'leida_en' => null,
                'atendida_en' => null,
                'notas_internas' => null,
            ],
            [
                'nombre' => 'Diego Fernandez',
                'email' => 'diego.fernandez@example.com',
                'telefono' => '+54 9 11 6420-8842',
                'tipo_propiedad_id' => $tipos->get('Departamento'),
                'ubicacion_texto' => 'Puerto Madero, CABA',
                'direccion' => 'Juana Manso 1200',
                'mensaje' => 'Necesito una valuación para alquiler anual.',
                'estado_seguimiento' => EstadoSeguimiento::EN_SEGUIMIENTO,
                'leida_en' => now()->subDay(),
                'atendida_en' => null,
                'notas_internas' => 'Pedir fotos y datos de amenities.',
            ],
            [
                'nombre' => 'Lucia Alvarez',
                'email' => 'lucia.alvarez@example.com',
                'telefono' => '+54 9 11 2398-1204',
                'tipo_propiedad_id' => $tipos->get('Terreno'),
                'ubicacion_texto' => 'San Matias, Escobar',
                'direccion' => null,
                'mensaje' => 'Terreno en barrio cerrado. Quiero saber valor de mercado actual.',
                'estado_seguimiento' => EstadoSeguimiento::CONTACTADA,
                'leida_en' => now()->subDays(5),
                'atendida_en' => now()->subDays(4),
                'notas_internas' => 'Se solicitó documentación del lote.',
            ],
        ];

        foreach ($tasaciones as $datos) {
            Tasacion::query()->updateOrCreate(
                ['email' => $datos['email'], 'ubicacion_texto' => $datos['ubicacion_texto']],
                [
                    ...$datos,
                    'estado_seguimiento' => $datos['estado_seguimiento']->value,
                ],
            );
        }
    }
}
