<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tarea;
use App\Models\Asunto;
use App\Models\Tipo;
use App\Models\User;
use Carbon\Carbon;

class TareaSeeder extends Seeder
{
    public function run()
    {
        // Obtener algunos ejemplos de 'asuntos', 'tipos' y 'usuarios'
        $asuntos = Asunto::take(6)->get();
        $tipos = Tipo::take(4)->get();
        $users = User::take(2)->get(); // Obtener los primeros 5 usuarios como ejemplo

        // Verificar que existan registros para evitar errores
        if (!$asuntos->count() || !$tipos->count() || !$users->count()) {
            $this->command->error('Asegúrate de que hay datos en las tablas de asuntos, tipos y usuarios.');
            return;
        }

        // Array de ejemplos de tareas con variaciones (añadí tres tareas más al final)
        $tareas = [
            [
                'subtipo' => 'ORDINARIA',
                'estado' => 'PENDIENTE',
                'cliente_id' => 6,
                'descripcion' => 'Revisión de la documentación fiscal para el trimestre.',
                'observaciones' => 'Requiere revisión urgente.',
                'archivo' => 'documento_fiscal.pdf',
                'facturable' => true,
                'facturado' => 'Factura001',
                'precio' => 200.00,
                'suplido' => 20.00,
                'coste' => 150.00,
                'fecha_inicio' => Carbon::now(),
                'fecha_vencimiento' => Carbon::now()->addDays(10),
                'fecha_imputacion' => Carbon::now(),
                'tiempo_previsto' => 2.00,
                'tiempo_real' => 1.50,
            ],
            [
                'subtipo' => 'EXTRAORDINARIA',
                'estado' => 'ENPROGRESO',
                'cliente_id' => 7,
                'descripcion' => 'Actualización del sistema contable.',
                'observaciones' => 'Esperando la aprobación del cliente.',
                'archivo' => 'sistema_contable.pdf',
                'facturable' => true,
                'facturado' => 'Factura002',
                'precio' => 500.00,
                'suplido' => 50.00,
                'coste' => 300.00,
                'fecha_inicio' => Carbon::now()->subDays(5),
                'fecha_vencimiento' => Carbon::now()->addDays(15),
                'fecha_imputacion' => Carbon::now(),
                'tiempo_previsto' => 5.00,
                'tiempo_real' => 2.00,
            ],
            [
                'subtipo' => 'ORDINARIA',
                'estado' => 'COMPLETADA',
                'cliente_id' => 8,
                'descripcion' => 'Preparación del informe anual.',
                'observaciones' => 'Informe finalizado y enviado al cliente.',
                'archivo' => 'informe_anual.pdf',
                'facturable' => false,
                'facturado' => null,
                'precio' => 0.00,
                'suplido' => 0.00,
                'coste' => 0.00,
                'fecha_inicio' => Carbon::now()->subDays(30),
                'fecha_vencimiento' => Carbon::now()->subDays(10),
                'fecha_imputacion' => Carbon::now()->subDays(5),
                'tiempo_previsto' => 3.00,
                'tiempo_real' => 3.50,
            ],
            // Tres nuevas tareas añadidas
            [
                'subtipo' => 'EXTRAORDINARIA',
                'estado' => 'PENDIENTE',
                'cliente_id' => 9,
                'descripcion' => 'Revisión del contrato de servicios.',
                'observaciones' => 'El contrato debe revisarse antes de firmar.',
                'archivo' => 'contrato_servicios.pdf',
                'facturable' => true,
                'facturado' => 'Factura003',
                'precio' => 300.00,
                'suplido' => 25.00,
                'coste' => 200.00,
                'fecha_inicio' => Carbon::now()->subDays(3),
                'fecha_vencimiento' => Carbon::now()->addDays(7),
                'fecha_imputacion' => Carbon::now(),
                'tiempo_previsto' => 1.50,
                'tiempo_real' => 1.00,
            ],
            [
                'subtipo' => 'ORDINARIA',
                'estado' => 'ENPROGRESO',
                'cliente_id' => 10,
                'descripcion' => 'Planificación de la estrategia de marketing.',
                'observaciones' => 'El cliente solicitó cambios en la estrategia.',
                'archivo' => 'estrategia_marketing.pdf',
                'facturable' => false,
                'facturado' => null,
                'precio' => 0.00,
                'suplido' => 0.00,
                'coste' => 0.00,
                'fecha_inicio' => Carbon::now()->subDays(7),
                'fecha_vencimiento' => Carbon::now()->addDays(5),
                'fecha_imputacion' => Carbon::now(),
                'tiempo_previsto' => 4.00,
                'tiempo_real' => 2.00,
            ],
            [
                'subtipo' => 'ORDINARIA',
                'estado' => 'COMPLETADA',
                'cliente_id' => 11,
                'descripcion' => 'Auditoría interna de procesos.',
                'observaciones' => 'Se completó la auditoría con éxito.',
                'archivo' => 'auditoria_procesos.pdf',
                'facturable' => true,
                'facturado' => 'Factura004',
                'precio' => 800.00,
                'suplido' => 100.00,
                'coste' => 600.00,
                'fecha_inicio' => Carbon::now()->subDays(20),
                'fecha_vencimiento' => Carbon::now()->subDays(5),
                'fecha_imputacion' => Carbon::now()->subDays(4),
                'tiempo_previsto' => 6.00,
                'tiempo_real' => 6.50,
            ],
        ];

        // Crear varias tareas y asignarles usuarios
        foreach ($tareas as $index => $data) {
            $asunto = $asuntos->random(); // Seleccionar asunto aleatorio
            $tipo = $tipos->random(); // Seleccionar tipo aleatorio

            // Crear la tarea
            $tarea = Tarea::create(array_merge($data, [
                'asunto_id' => $asunto->id,
                'tipo_id' => $tipo->id,
            ]));

            // Asignar usuarios aleatoriamente a la tarea
            $tarea->users()->attach($users->random(1)->pluck('id')->toArray());
        }
    }
}
