<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $task;
    private $assignedBy; // Usuario que asigna la tarea

    /**
     * Constructor de la notificación.
     *
     * @param $task La tarea asignada.
     * @param $assignedBy El usuario que asignó la tarea.
     */
    public function __construct($task, $assignedBy)
    {
        $this->task = $task;
        $this->assignedBy = $assignedBy;
    }

    /**
     * Canales por los que se enviará la notificación.
     *
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Estructura para persistir en la base de datos.
     *
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {


        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->asunto->nombre ?? 'Sin asunto',
            'assigned_by' => $this->assignedBy->name, // Ahora usa $this->assignedBy
            'client' => $this->task->cliente->nombre_fiscal ?? 'Sin cliente', // Relación con cliente
            'description' => Str::limit($this->task->descripcion ?? '', 15),
            'url' => route('tasks.index'),
            'created_at' => $this->task->created_at ? $this->task->created_at->toISOString() : now()->toISOString(),

        ];
    }

    /**
     * Estructura para el canal de broadcasting.
     *
     * @param $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {


        return new BroadcastMessage([
            'task_id' => $this->task->id,
            'task_title' => $this->task->asunto->nombre ?? 'Sin asunto',
            'assigned_by' => $this->assignedBy->name, // Ahora usa $this->assignedBy
            'client' => $this->task->cliente->nombre_fiscal ?? 'Sin cliente', // Relación con cliente
            'description' => Str::limit($this->task->descripcion ?? '', 15),
            'url' => route('tasks.index'),
            'created_at' => $this->task->created_at ? $this->task->created_at->toISOString() : now()->toISOString(),
        ]);
    }
}
