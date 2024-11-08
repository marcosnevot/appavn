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
                'Asunto' => $task->asunto ? $task->asunto->nombre : 'Sin asunto',
                'Cliente' => $task->cliente ? $task->cliente->nombre_fiscal : 'Sin cliente',
                'Tipo' => $task->tipo ? $task->tipo->nombre : 'Sin tipo',
                'Descripción' => $task->descripcion,
                'Observaciones' => $task->observaciones,
                'Facturable' => $task->facturable ? 'Sí' : 'No',
                'Facturado' => $task->facturado,
                'Subtipo' => $task->subtipo,
                'Estado' => $task->estado,
                'Fecha de Inicio' => $task->fecha_inicio,
                'Fecha de Vencimiento' => $task->fecha_vencimiento,
                'Fecha de Imputación' => $task->fecha_imputacion,
                'Tiempo Previsto' => $task->tiempo_previsto,
                'Tiempo Real' => $task->tiempo_real,
                'Fecha de Planificación' => $task->fecha_planificacion,
                'Usuarios Asignados' => $task->users->pluck('name')->join(', '),  // Concatenar nombres de usuarios
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
            'ID', 'Asunto', 'Cliente', 'Tipo', 'Descripción', 'Observaciones', 'Facturable',
            'Facturado', 'Subtipo', 'Estado', 'Fecha de Inicio', 'Fecha de Vencimiento',
            'Fecha de Imputación', 'Tiempo Previsto', 'Tiempo Real', 'Fecha de Planificación',
            'Usuarios Asignados', 'Fecha de Creación'
        ];
    }
}