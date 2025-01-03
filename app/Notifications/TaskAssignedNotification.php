<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
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
        return ['database', 'broadcast' /* ,'mail' */];
    }

    /**
     * Define el contenido del correo.
     *
     * @param $notifiable
     * @return MailMessage
     */
    /* public function toMail($notifiable): MailMessage
    {
        Log::info("Enviando correo desde: {$this->assignedBy->email}");
        Log::info(message: "Destinatario: {$notifiable->email}");

        try {
            $decryptedPassword = Crypt::decryptString($this->assignedBy->outlook_password);
            Log::info("Descifrado exitoso: {$decryptedPassword}");
        } catch (\Exception $e) {
            Log::error("Error al descifrar la contraseña: " . $e->getMessage());
            throw $e;
        }

        // Validar que la contraseña no sea nula
        if (empty($decryptedPassword)) {
            Log::error('La contraseña descifrada está vacía.');
            throw new \Exception('La contraseña de Outlook no está configurada.');
        }

        // Configurar credenciales SMTP dinámicas
        config([
            'mail.mailers.smtp.username' => $this->assignedBy->email,
            'mail.mailers.smtp.password' => $decryptedPassword,
        ]);


        return (new MailMessage)
            ->from($this->assignedBy->email, $this->assignedBy->name)
            ->subject('Nueva tarea asignada: ' . ($this->task->asunto->nombre ?? 'Sin asunto'))
            ->greeting('Hola,')
            ->line('Se te ha asignado una nueva tarea.')
            ->action('Ver Tarea', route('tasks.index'))
            ->line('Gracias por usar nuestra aplicación.');
    }
*/


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
            'notification_type' => 'task_assigned', // Nuevo campo para diferenciar el tipo de notificación

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
            'notification_type' => 'task_assigned', // Nuevo campo para diferenciar el tipo de notificación
        ]);
    }
}
