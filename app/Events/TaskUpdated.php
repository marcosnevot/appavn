<?php

namespace App\Events;

use App\Models\Tarea;
use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $task;

    public function __construct(Tarea $task)
    {
        $this->task = $task;
    }

    public function broadcastOn()
    {
        return new Channel('tasks');
    }

    public function broadcastAs()
    {
        return 'TaskUpdated';
    }
}
