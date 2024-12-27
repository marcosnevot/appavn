<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TaskPeriodicReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $task;
    private $periodicidad;

    /**
     * Constructor de la notificación.
     *
     * @param $task La tarea periódica creada.
     * @param $periodicidad La periodicidad de la tarea original.
     */
    public function __construct($task, $periodicidad)
    {
        $this->task = $task;
        $this->periodicidad = $periodicidad;
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
            'client' => $this->task->cliente->nombre_fiscal ?? 'Sin cliente', // Relación con cliente
            'description' => Str::limit($this->task->descripcion ?? '', 15), // Descripción de la tarea
            'url' => route('tasks.index'), // URL para ver la tarea
            'created_at' => $this->task->created_at ? $this->task->created_at->toISOString() : now()->toISOString(),
            'notification_type' => 'task_periodic_reminder', // Nuevo campo para diferenciar el tipo de notificación
            'reminder' => 'Tarea Periódica ' . $this->periodicidad,
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
            'client' => $this->task->cliente->nombre_fiscal ?? 'Sin cliente',
            'description' => Str::limit($this->task->descripcion ?? '', 15),
            'url' => route('tasks.index'),
            'created_at' => $this->task->created_at ? $this->task->created_at->toISOString() : now()->toISOString(),
            'notification_type' => 'task_periodic_reminder', // Nuevo campo para diferenciar el tipo de notificación
            'reminder' => 'Tarea Periódica ' . $this->periodicidad,
        ]);
    }
}
