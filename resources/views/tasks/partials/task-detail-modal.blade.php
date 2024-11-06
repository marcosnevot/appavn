<div class="task-detail-modal-header">
    <!-- Título central con el Asunto y Cliente -->
    <h2 class="task-detail-title">
        <span class="task-title">{{ $task->asunto->nombre ?? 'Sin Asunto' }}</span>
        <span class="task-client" data-nif="{{ $task->cliente->nif ?? 'No disponible' }}"
            data-direccion="{{ $task->cliente->direccion ?? 'No disponible' }}"
            data-poblacion="{{ $task->cliente->poblacion ?? 'No disponible' }}">{{ $task->cliente->nombre_fiscal ?? 'Sin Cliente' }}</span>
    </h2>

    <!-- Teléfono y Email del Cliente -->
    <div class="task-client-contact">
        <div class="task-client-contact-item task-client-email">✉️ <a href="mailto:{{ $task->cliente->email ?? '#' }}">{{ $task->cliente->email ?? 'Sin email' }}</a></div>
        <div class="task-client-contact-item task-client-phone">📞 {{ $task->cliente->movil ?? 'Sin teléfono' }}</div>
    </div>

    <!-- Descripción integrada debajo del cliente -->
    <p class="task-description">{{ $task->descripcion ?? 'No hay descripción disponible para esta tarea.' }}</p>
</div>

<div class="task-detail-actions">
    <!-- Botones centrados para editar y eliminar la tarea -->
    <button id="edit-task-button" class="btn-task-action" data-task-id="{{ $task->id }}">Editar</button>
    <button id="delete-task-button" class="btn-task-action" data-task-id="{{ $task->id }}">Borrar</button>
</div>

<!-- Sección reservada para la futura gestión de subtareas -->
<div class="task-subtasks-section">
    <h3>Subtareas</h3>
    <p>Aquí se gestionarán las subtareas de esta tarea. (Próximamente)</p>
</div>