<?php

namespace App\Exports;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TasksExport implements FromCollection, WithHeadings
{
    protected $tasks;

    public function __construct(Collection $tasks)
    {
        $this->tasks = $tasks->map(function ($task) {
            return [
                'ID' => $task->id,
                'Fecha de Vencimiento' => $task->fecha_vencimiento,
                'Fecha de Planificación' => $task->fecha_planificacion,
                'Usuarios Asignados' => $task->users->pluck('name')->join(', '),  // Concatenar nombres de usuarios
                'Cliente' => $task->cliente ? $task->cliente->nombre_fiscal : 'Sin cliente',
                'Asunto' => $task->asunto ? $task->asunto->nombre : 'Sin asunto',
                'Descripción' => $task->descripcion,
                'Observaciones' => $task->observaciones,
                'Facturable' => $task->facturable ? 'Sí' : 'No',
                'Facturado' => $task->facturado,
                'Estado' => $task->estado,
                'Tiempo Previsto' => $task->tiempo_previsto,
                'Tiempo Real' => $task->tiempo_real,
                'Tipo' => $task->tipo ? $task->tipo->nombre : 'Sin tipo',
                'Subtipo' => $task->subtipo,
                'Fecha de Inicio' => $task->fecha_inicio,
                'Fecha de Creación' => $task->created_at,
            ];
        });
    }

    public function collection()
    {
        return $this->tasks;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha de Vencimiento',
            'Fecha de Planificación',
            'Usuarios Asignados',
            'Cliente',
            'Asunto',
            'Descripción',
            'Observaciones',
            'Facturable',
            'Facturado',
            'Estado',
            'Tiempo Previsto',
            'Tiempo Real',
            'Tipo',
            'Subtipo',
            'Fecha de Inicio',
            'Fecha de Creación',
        ];
    }
}
