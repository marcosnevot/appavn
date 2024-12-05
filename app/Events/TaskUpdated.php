<?php

namespace App\Events;

use App\Models\Tarea;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TaskUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $task;

    public function __construct(Tarea $task)
    {
        // Cargar relaciones necesarias como en TaskCreated
        $this->task = $task->load('asunto', 'cliente', 'tipo', 'users');
    }

    public function broadcastAs()
    {
        return 'TaskUpdated';
    }


    public function broadcastOn()
    {
        return new Channel('tasks');
    }

    public function broadcastWith()
    {
        Log::debug('Datos que se están emitiendo en TaskUpdated: ', $this->task->toArray());

        return [
            'task' => [
                'id' => $this->task->id,
                'subtipo' => $this->task->subtipo,
                'estado' => $this->task->estado,
                'users' => $this->task->users->map(function ($user) {
                    return $user->name;
                })->toArray(),
                'descripcion' => $this->task->descripcion,
                'observaciones' => $this->task->observaciones,
                'archivo' => $this->task->archivo,
                'facturable' => $this->task->facturable,
                'facturado' => $this->task->facturado,
                'precio' => $this->task->precio,
                'suplido' => $this->task->suplido,
                'coste' => $this->task->coste,
                'fecha_inicio' => $this->task->fecha_inicio,
                'fecha_vencimiento' => $this->task->fecha_vencimiento,
                'fecha_planificacion' => $this->task->fecha_planificacion,
                'tiempo_previsto' => $this->task->tiempo_previsto,
                'tiempo_real' => $this->task->tiempo_real,
                'asunto' => $this->task->asunto ? $this->task->asunto->nombre : null,
                'tipo' => $this->task->tipo ? $this->task->tipo->nombre : null,
                'cliente' => $this->task->cliente ? $this->task->cliente->nombre_fiscal : null, // Devuelve el nombre directamente

            ],
        ];
    }
}