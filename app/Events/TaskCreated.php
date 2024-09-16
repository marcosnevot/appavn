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

    public function __construct(Tarea $task)
    {
        // Aquí, cargar relaciones necesarias
        $this->task = $task->load('asunto', 'cliente', 'tipo', 'users');
    }

    public function broadcastOn()
    {
        return new Channel('tasks');
    }
}
