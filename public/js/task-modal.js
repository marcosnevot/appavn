document.addEventListener('DOMContentLoaded', () => {
    console.log('El script task-modal.js ha sido cargado correctamente.');

    // Observa si el contenido del modal cambia (es decir, se carga una nueva tarea)
    const modalContent = document.getElementById('task-detail-modal-content');
    const observer = new MutationObserver(() => {

        // Volver a seleccionar el botón de borrar después de que se cargue el contenido
        const deleteButton = document.getElementById('delete-task-button');

        if (deleteButton) {
            // Agregar el event listener solo si el botón de borrar existe
            deleteButton.addEventListener('click', () => {
                const taskId = deleteButton.getAttribute('data-task-id');
                const confirmDelete = confirm('¿Estás seguro de que quieres borrar esta tarea?');

                if (confirmDelete) {
                    deleteTask(taskId);  // Llama a la función que borra la tarea
                }
            });
        }
    });

    // Iniciar el observador de cambios en el contenido del modal
    observer.observe(modalContent, { childList: true });


    // Escuchar el evento 'TaskDeleted' a través de websockets
    Echo.channel('tasks')
        .listen('TaskDeleted', (data) => {
            console.log('Tarea eliminada:', data.taskId);

            // Actualizar la tabla eliminando la tarea correspondiente
            const rowToDelete = document.querySelector(`tr[data-task-id="${data.taskId}"]`);
            if (rowToDelete) {
                rowToDelete.remove();
            }

            // Si el modal está abierto y es el mismo taskId, cerrarlo
            const modal = document.getElementById('task-detail-modal');
            const modalTaskId = modal.getAttribute('data-task-id'); // Asume que tienes el id de la tarea en un atributo

            if (modalTaskId == data.taskId) {
                console.log('Cerrando el modal porque la tarea ha sido eliminada.');
                closeTaskModal();
            }
        });


});
// Función para borrar la tarea
function deleteTask(taskId) {
    console.log("Intentando borrar la tarea con ID:", taskId);

    fetch(`/tareas/${taskId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Tarea eliminada correctamente.");
                showNotification("Tarea eliminada correctamente", "error");
                // Cerrar el modal
                closeTaskModal();

                // Eliminar la fila correspondiente de la tabla
                const rowToDelete = document.querySelector(`tr[data-task-id="${taskId}"]`);
                if (rowToDelete) {
                    rowToDelete.remove();  // Elimina la fila de la tabla
                }

            } else {
                console.error('Error al eliminar la tarea:', data.message);
                alert('Error al eliminar la tarea: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error al borrar la tarea:', error);
            alert('Error al borrar la tarea.');
        });
}


// Función para cerrar el modal
function closeTaskModal() {
    const modal = document.getElementById('task-detail-modal');
    modal.style.display = 'none';
}





