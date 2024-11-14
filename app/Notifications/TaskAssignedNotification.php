<?php
namespace App\Notifications;

use App\Models\Asunto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $task;

    public function __construct($task) 
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        // Define los canales por los que se enviará la notificación
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        // Estructura para persistir en la base de datos
        return [
            'task_id' => $this->task->id,
            'task_title' => is_string($this->task->asunto->nombre) ? $this->task->asunto->nombre : 'Sin asunto', // Asegura que sea un string
            'assigned_by' => auth()->user()->name, // Usuario que asignó la tarea
            'url' => route('tasks.index') // Enlace a la tarea
        ];
    }

    public function toBroadcast($notifiable)
    {
        // Estructura para el canal de broadcasting
        return new BroadcastMessage([
            'task_id' => $this->task->id,
            'task_title' => $this->task->asunto->nombre ?? 'Sin asunto', // Valor predeterminado
            'assigned_by' => auth()->user()->name,
            'url' => route('tasks.index'),
        ]);
    }
}
