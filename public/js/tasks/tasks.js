document.addEventListener('DOMContentLoaded', function () {
    // console.log('El script tasks.js ha sido cargado correctamente.');

    // Variables globales para la paginación
    let currentPage = 1;
    let globalTasksArray = []; // Definir una variable global para las tareas

    // Cargar tareas inicialmente
    loadTasks();

    // Función para cargar las tareas mediante AJAX con paginación
    function loadTasks(page = 1) {
        const tableBody = document.querySelector('table tbody');
        tableBody.innerHTML = '<tr><td colspan="21" class="text-center">Cargando tareas...</td></tr>'; // Mensaje de carga

        fetch(`/tareas/getTasks?page=${page}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadInitialTasks(data.tasks);
                    updatePagination(data.pagination, loadTasks);  // Pasa loadTasks como argumento
                } else {
                    console.error('Error al cargar tareas:', data.message);
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error.message);
                tableBody.innerHTML = '<tr><td colspan="21" class="text-center text-red-500">Error al cargar las tareas.</td></tr>';
            });
    }

    // Función para cargar y actualizar la tabla de tareas inicialmente
    function loadInitialTasks(tasks) {
        globalTasksArray = tasks;  // Almacenar las tareas cargadas globalmente

        const tableBody = document.querySelector('table tbody');
        tableBody.innerHTML = ''; // Limpiar la tabla existente

        tasks.forEach(task => {
            const row = document.createElement('tr');
            row.setAttribute('data-task-id', task.id); // Asignar el id de la tarea
            row.innerHTML = `
            <td>${task.id}</td>
            <td>${task.asunto ? task.asunto.nombre : 'Sin asunto'}</td>
            <td>${task.cliente ? task.cliente.nombre_fiscal : 'Sin cliente'}</td>
            <td>${task.tipo ? task.tipo.nombre : 'Sin tipo'}</td>
            <td>${task.subtipo || ''}</td>
            <td>${task.estado}</td>
            <td>${task.users && task.users.length > 0 ? task.users.map(user => user.name).join(', ') : 'Sin asignación'}</td>
            <td>${task.descripcion || ''}</td>
            <td>${task.observaciones || ''}</td>
            <td>${task.archivo || 'No disponible'}</td>
            <td>${task.facturable ? 'Sí' : 'No'}</td>
            <td>${task.facturado || 'No facturado'}</td>
            <td>${task.precio || 'N/A'}</td>
            <td>${task.suplido || 'N/A'}</td>
            <td>${task.coste || 'N/A'}</td>
            <td>${task.fecha_inicio ? new Date(task.fecha_inicio).toLocaleDateString() : 'Sin fecha'}</td>
            <td>${task.fecha_vencimiento ? new Date(task.fecha_vencimiento).toLocaleDateString() : 'Sin fecha'}</td>
            <td>${task.fecha_imputacion ? new Date(task.fecha_imputacion).toLocaleDateString() : 'Sin fecha'}</td>
            <td>${task.tiempo_previsto || 'N/A'}</td>
            <td>${task.tiempo_real || 'N/A'}</td>
            <td style="display: none;">${task.created_at || 'Sin fecha'}</td>
        `;
            tableBody.appendChild(row);

            // Añadir el evento de doble clic a las filas de la tabla
            addDoubleClickEventToRows();
        });
    }





});
