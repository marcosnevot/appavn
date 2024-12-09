document.addEventListener('DOMContentLoaded', function () {
    // console.log('El script tasks.js ha sido cargado correctamente.');

    // Variables globales para la paginación
    let currentPage = 1;
    let globalTasksArray = []; // Definir una variable global para las tareas


    let currentSortKey = ''; // Almacena la clave de ordenación actual
    let currentSortDirection = ''; // Dirección de orden actual
    const sessionUserId = document.getElementById('user-session-id').value;

    // Cargar tareas inicialmente
    loadTasks(1, 'fecha_planificacion', 'asc');






    document.querySelectorAll('th[data-sort-key]').forEach(header => {
        header.addEventListener('click', function () {
            const sortKey = this.getAttribute('data-sort-key');

            // Determinar la dirección de orden
            if (currentSortKey === sortKey) {
                currentSortDirection = currentSortDirection === 'asc'
                    ? 'desc'
                    : currentSortDirection === 'desc'
                        ? 'none'
                        : 'asc';
            } else {
                currentSortKey = sortKey;
                currentSortDirection = 'asc'; // Reiniciar a ascendente para nueva columna
            }

            // Quitar clases de todos los encabezados
            document.querySelectorAll('th[data-sort-key]').forEach(th => {
                th.classList.remove('sorted-asc', 'sorted-desc');
            });

            // Añadir clase según la dirección actual
            if (currentSortDirection === 'asc') {
                this.classList.add('sorted-asc');
            } else if (currentSortDirection === 'desc') {
                this.classList.add('sorted-desc');
            }

            // Si el estado es "none", reestablecer al orden original
            const sortKeyToSend = currentSortDirection === 'none' ? '' : currentSortKey;
            const sortDirectionToSend = currentSortDirection === 'none' ? '' : currentSortDirection;

            // Recargar tareas con la nueva ordenación y filtros activos
            loadTasks(1, sortKeyToSend, sortDirectionToSend);
        });
    });






    document.getElementById('export-tasks-button').addEventListener('click', async function () {

        const fileName = prompt("Ingrese el nombre para el archivo (sin extensión):", "tareas");

        // Verificar si el usuario canceló
        if (fileName === null) {
            return;  // Salir de la función sin continuar con la exportación
        }

        // Extraer fechas del rango de planificación desde el formulario
        const fechaPlanificacionInput = document.getElementById('filter-fecha-planificacion').value || '';
        const [fechaPlanificacionInicio, fechaPlanificacionFin] = fechaPlanificacionInput
            ? fechaPlanificacionInput.split(' - ')
            : ['', ''];

        const filterData = {
            cliente: document.getElementById('filter-cliente-ids').value || '', // Usar los IDs de clientes seleccionados
            asunto: document.getElementById('filter-asunto-ids').value || '',  // Usar los IDs/nombres de asuntos seleccionados
            tipo: document.getElementById('filter-tipo-ids').value || '',
            subtipo: document.getElementById('filter-subtipo-ids').value || '', // Usar el campo oculto con los valores seleccionados
            estado: document.getElementById('filter-estado-ids').value || '', // <-- Usar el campo oculto con los valores seleccionados
            usuario: document.getElementById('filter-user-ids').value || '',
            archivo: document.getElementById('filter-archivo').value || '',
            facturable: document.getElementById('filter-facturable-ids').value || '', // Usar el campo oculto con los valores seleccionados
            facturado: document.getElementById('filter-facturado-ids').value || '', // Usar el campo oculto con los valores seleccionados
            precio: document.getElementById('filter-precio').value || '',
            suplido: document.getElementById('filter-suplido').value || '',
            coste: document.getElementById('filter-coste').value || '',
            fecha_inicio: document.getElementById('filter-fecha-inicio').value || '',
            fecha_vencimiento: document.getElementById('filter-fecha-vencimiento').value || '',
            // fecha_imputacion: document.getElementById('filter-fecha-imputacion').value || '',
            tiempo_previsto: document.getElementById('filter-tiempo-previsto').value || '',
            tiempo_real: document.getElementById('filter-tiempo-real').value || '',
            // Enviar las fechas de planificación como valores separados
            fecha_planificacion_inicio: fechaPlanificacionInicio || '',
            fecha_planificacion_fin: fechaPlanificacionFin || '',


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

// Función para cargar las tareas mediante AJAX con paginación
function loadTasks(page = 1, sortKey, sortDirection) {
    const tableBody = document.querySelector('table tbody');
    const sessionUserId = document.getElementById('user-session-id').value;

    // Oculta la tabla mientras se cargan las tareas
    const table = document.querySelector('table');

    tableBody.innerHTML = '<tr><td colspan="21" class="text-center">Cargando tareas...</td></tr>'; // Mensaje de carga

    // Construir los parámetros de la URL
    const params = new URLSearchParams({
        ...window.currentFilters, // Usar filtros activos de la variable global
        estado: window.currentFilters?.estado || 'PENDIENTE,ENESPERA', // Predeterminado
        page, // Página actual
        user_id: sessionUserId, // Usuario actual
        sortKey, // Clave de ordenación
        sortDirection, // Dirección de ordenación
        asunto: window.currentFilters?.asunto || 'CITA,LLAMADA TELEFONICA',

    });
     console.log(currentFilters); // Verifica qué se está enviando al servidor


    fetch(`/tareas/getTasks?${params.toString()}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // console.log(data.tasks); // Verifica si las tareas están ordenadas correctamente en los datos recibido
                loadInitialTasks(data.tasks);
                updatePagination(data.pagination, (newPage) => loadTasks(newPage, sortKey, sortDirection));
                updateHoursSummaryFromTotals(data.totalTiempoPrevisto, data.totalTiempoReal);

            } else {
                console.error('Error al cargar tareas:', data.message);
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error.message);
            tableBody.innerHTML = '<tr><td colspan="21" class="text-center text-red-500">Error al cargar las tareas.</td></tr>';
        });
}

function truncateText(text, maxLength) {
    if (!text) return ''; // Retornar vacío si no hay texto
    return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
}


// Función para cargar y actualizar la tabla de tareas inicialmente
function loadInitialTasks(tasks) {
    globalTasksArray = tasks;  // Almacenar las tareas cargadas globalmente
    // console.log('Tareas ordenadas antes de renderizar:', tasks);

    const tableBody = document.querySelector('table tbody');
    tableBody.innerHTML = ''; // Limpiar la tabla existente


    tasks.forEach(task => {
        const row = document.createElement('tr');
        row.setAttribute('data-task-id', task.id); // Asignar el id de la tarea

        // Añade una clase según el estado de la tarea
        const estadoClass = task.estado ? `estado-${task.estado.toLowerCase()}` : 'estado-default';
        row.classList.add(estadoClass);

        row.innerHTML = `
        <td>${task.id}</td>
        <td>${task.fecha_vencimiento ? new Date(task.fecha_vencimiento).toLocaleDateString() : 'Sin fecha'}</td>
        <td>
        ${task.fecha_planificacion ? formatFechaPlanificacion(task.fecha_planificacion) : 'Sin fecha'}
        </td> 
        <td>${task.users && task.users.length > 0 ? task.users.map(user => user.name).join(', ') : 'Sin asignación'}</td>
        <td>${task.cliente ? task.cliente.nombre_fiscal : 'Sin cliente'}</td>
        <td>${task.asunto ? task.asunto.nombre : 'Sin asunto'}</td>
        <td class="col-descripcion">${task.descripcion ? truncateText(task.descripcion, 100) : ''}</td>
        <td class="col-observaciones">${task.observaciones ? truncateText(task.observaciones, 100) : ''}</td>
        <td>${task.facturable ? 'Sí' : 'No'}</td>
        <td>${task.facturado || 'No facturado'}</td>
        <td>${task.estado}</td>
        <td>${task.tiempo_previsto || 'N/A'}</td>
        <td>${task.tiempo_real || 'N/A'}</td>
        <td>${task.tipo ? task.tipo.nombre : 'Sin tipo'}</td>
        <td>${task.subtipo || ''}</td>
        <td>${task.fecha_inicio ? new Date(task.fecha_inicio).toLocaleDateString() : 'Sin fecha'}</td>

    `;
        tableBody.appendChild(row);

        // Añadir el evento de doble clic a las filas de la tabla
        addDoubleClickEventToRows();
    });
}