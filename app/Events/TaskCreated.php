<?php

namespace App\Events;

use App\Models\Tarea;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class TaskCreated implements ShouldBroadcast
{
    use SerializesModels;

    public $task;
    public $assignedUserIds; // IDs de los usuarios asignados a la tarea

    public function __construct(Tarea $task)
    {
        // Cargar relaciones necesarias
        $this->task = $task->load('asunto', 'cliente', 'tipo', 'users');

        // Extraer los IDs de los usuarios asignados
        $this->assignedUserIds = $task->users->pluck('id')->toArray();
    }

    public function broadcastOn()
    {
        return new Channel('tasks'); // Canal público
    }

    public function broadcastWith()
    {
        // Datos enviados al frontend
        return [
            'task' => $this->task,
            'assignedUserIds' => $this->assignedUserIds,
        ];
    }
}
