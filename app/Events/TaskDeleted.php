<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskDeleted implements ShouldBroadcast
{
    public $taskId;

    public function __construct($taskId)
    {
        $this->taskId = $taskId;  // Pasamos solo la ID de la tarea eliminada
    }

    public function broadcastOn()
    {
        return new Channel('tasks');  // Canal de broadcasting
    }

    public function broadcastWith()
    {
        return [
            'taskId' => $this->taskId,  // Lo que se envía a los usuarios
        ];
    }
}
