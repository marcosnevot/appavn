document.addEventListener('DOMContentLoaded', function () {
    // console.log('El script tasks.js ha sido cargado correctamente.');

    // Variables globales para la paginación
    let currentPage = 1;
    let globalTasksArray = []; // Definir una variable global para las tareas


    const sessionUserId = document.getElementById('user-session-id').value;


    // Cargar tareas inicialmente
    loadTasks();

    // Función para cargar las tareas mediante AJAX con paginación
    function loadTasks(page = 1) {
        const tableBody = document.querySelector('table tbody');
        tableBody.innerHTML = '<tr><td colspan="21" class="text-center">Cargando tareas...</td></tr>'; // Mensaje de carga

        fetch(`/tareas/getTasks?page=${page}&user_id=${sessionUserId}`, {
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
            
            <td>${task.descripcion || ''}</td>
            <td>${task.observaciones || ''}</td>
            <td>${task.facturable ? 'Sí' : 'No'}</td>
            <td>${task.facturado || 'No facturado'}</td>
            <td>${task.subtipo || ''}</td>
            <td>${task.estado}</td>
            <td>${task.fecha_inicio ? new Date(task.fecha_inicio).toLocaleDateString() : 'Sin fecha'}</td>
            <td>${task.fecha_vencimiento ? new Date(task.fecha_vencimiento).toLocaleDateString() : 'Sin fecha'}</td>
            <td>${task.fecha_imputacion ? new Date(task.fecha_imputacion).toLocaleDateString() : 'Sin fecha'}</td>
            <td>${task.tiempo_previsto || 'N/A'}</td>
            <td>${task.tiempo_real || 'N/A'}</td>
            <td>
            ${task.fecha_planificacion ? formatFechaPlanificacion(task.fecha_planificacion) : 'Sin fecha'}
            </td> 
           <td>${task.users && task.users.length > 0 ? task.users.map(user => user.name).join(', ') : 'Sin asignación'}</td>
            <td style="display: none;">${task.archivo || 'No disponible'}</td>
            <td style="display: none;">${task.precio || 'N/A'}</td>
            <td style="display: none;">${task.suplido || 'N/A'}</td>
            <td style="display: none;">${task.coste || 'N/A'}</td>
            <td style="display: none;">${task.created_at || 'Sin fecha'}</td>
        `;
            tableBody.appendChild(row);

            // Añadir el evento de doble clic a las filas de la tabla
            addDoubleClickEventToRows();
        });
    }

    function formatFechaPlanificacion(fecha) {
        const hoy = new Date();
        const manana = new Date();
        manana.setDate(hoy.getDate() + 1);
        const fechaPlanificacion = new Date(fecha);

        // Array con los nombres de los días de la semana
        const diasSemana = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];

        // Verificar si la fecha es hoy
        if (
            fechaPlanificacion.getDate() === hoy.getDate() &&
            fechaPlanificacion.getMonth() === hoy.getMonth() &&
            fechaPlanificacion.getFullYear() === hoy.getFullYear()
        ) {
            return "HOY";
        }

        // Verificar si la fecha es mañana
        if (
            fechaPlanificacion.getDate() === manana.getDate() &&
            fechaPlanificacion.getMonth() === manana.getMonth() &&
            fechaPlanificacion.getFullYear() === manana.getFullYear()
        ) {
            return "MAÑANA";
        }

        // Calcular el último día laborable de esta semana (viernes)
        const diaHoy = hoy.getDay();
        const diasHastaViernes = 5 - diaHoy; // 5 es viernes
        const viernesDeEstaSemana = new Date(hoy);
        viernesDeEstaSemana.setDate(hoy.getDate() + diasHastaViernes);

        // Excluir sábado y domingo
        const diaSemanaPlanificacion = fechaPlanificacion.getDay();
        if (diaSemanaPlanificacion === 0 || diaSemanaPlanificacion === 6) {
            return fechaPlanificacion.toLocaleDateString();
        }

        // Verificar si la fecha está en esta semana y es entre lunes y viernes
        if (fechaPlanificacion <= viernesDeEstaSemana && fechaPlanificacion > hoy) {
            return diasSemana[diaSemanaPlanificacion];
        }

        // Si la fecha es anterior a hoy, formatearla en rojo
        if (fechaPlanificacion < hoy) {
            return `<span style="color: red;">${fechaPlanificacion.toLocaleDateString()}</span>`;
        }

        // Mostrar la fecha en formato normal para cualquier otra condición
        return fechaPlanificacion.toLocaleDateString();
    }


    document.getElementById('export-tasks-button').addEventListener('click', async function () {

        const fileName = prompt("Ingrese el nombre para el archivo (sin extensión):", "tareas");

        // Verificar si el usuario canceló
        if (fileName === null) {
            return;  // Salir de la función sin continuar con la exportación
        }

        const filterData = {
            cliente: document.getElementById('filter-cliente-id-input')?.value || '',
            asunto: document.getElementById('filter-asunto-input')?.value || '',
            tipo: document.getElementById('filter-tipo-input')?.value || '',
            subtipo: document.getElementById('filter-subtipo')?.value || '',
            estado: document.getElementById('filter-estado')?.value || '',
            usuario: document.getElementById('filter-user-ids')?.value || '',
            archivo: document.getElementById('filter-archivo')?.value || '',
            facturable: document.getElementById('filter-facturable')?.value || '',
            facturado: document.getElementById('filter-facturado')?.value || '',
            precio: document.getElementById('filter-precio')?.value || '',
            suplido: document.getElementById('filter-suplido')?.value || '',
            coste: document.getElementById('filter-coste')?.value || '',
            fecha_inicio: document.getElementById('filter-fecha-inicio')?.value || '',
            fecha_vencimiento: document.getElementById('filter-fecha-vencimiento')?.value || '',
            fecha_imputacion: document.getElementById('filter-fecha-imputacion')?.value || '',
            tiempo_previsto: document.getElementById('filter-tiempo-previsto')?.value || '',
            tiempo_real: document.getElementById('filter-tiempo-real')?.value || '',
            fileName: fileName + '.xlsx'
        };

        try {
            const response = await fetch('/tareas/export', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(filterData)
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName + '.xlsx';  // Nombre del archivo personalizado
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url); // Liberar el objeto URL
        } catch (error) {
            console.error('Error al exportar:', error);
        }
    });



});
