<?php
// app/Events/TareaActualizada.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TareaActualizada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tarea;

    public function __construct($tarea)
    {
        $this->tarea = $tarea;
    }

    public function broadcastOn()
    {
        return new Channel('tareas');
    }
}
