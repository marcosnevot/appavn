document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = "/api/periodic-tasks"; // Ruta de API para tareas periódicas
    const tableBody = document.getElementById("tasks-table-body");
    const filtersForm = document.getElementById("filters-form");
    const editTaskModal = document.getElementById("edit-task-modal");
    const editTaskForm = document.getElementById("edit-task-form");
    const closeModalButton = document.getElementById("close-edit-modal");
    const cancelEditButton = document.getElementById("cancel-edit-modal");

    // Cargar Usuarios para el Select de Asignación
    function loadUsers() {
        fetch("/api/users")
            .then(response => response.json())
            .then(users => {
                const asignacionSelect = document.getElementById("filter-asignacion");
                asignacionSelect.innerHTML = '<option value="">Todos</option>'; // Resetear opciones

                users.forEach(user => {
                    const option = document.createElement("option");
                    option.value = user.id;
                    option.textContent = `${user.name}`;
                    asignacionSelect.appendChild(option);
                });
            })
            .catch(error => console.error("Error al cargar usuarios:", error));
    }

    // Cargar Tareas en la Tabla
    function loadTasks(filters = {}) {
        tableBody.innerHTML = '<tr><td colspan="7">Cargando...</td></tr>';

        const queryParams = new URLSearchParams(filters).toString();

        fetch(`${apiEndpoint}?${queryParams}`)
            .then(response => response.json())
            .then(tasks => {
                tableBody.innerHTML = ""; // Limpiar la tabla

                if (tasks.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="7">No se encontraron tareas.</td></tr>';
                    return;
                }

                tasks.forEach(task => {
                    const row = createTaskRow(task);
                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error("Error al cargar tareas:", error);
                tableBody.innerHTML = '<tr><td colspan="7">Error al cargar las tareas.</td></tr>';
            });
    }

    // Crear Fila de Tarea
    function createTaskRow(task) {
        const row = document.createElement("tr");
        row.dataset.taskId = task.id;

        // Truncar la descripción a 50 caracteres si es más larga
        const descripcion = task.descripcion ? task.descripcion.slice(0, 50) : "Sin Descripción";
        const descripcionMostrar = descripcion.length < (task.descripcion ? task.descripcion.length : 0) ? descripcion + "..." : descripcion;


        row.innerHTML = `
            <td>${task.id}</td>
            <td>${task.cliente_nombre || "Sin Cliente"}</td>
            <td>${task.asunto_nombre || "Sin Asunto"}</td>
            <td>${descripcionMostrar}</td>
            <td>${task.asignacion_nombre || "Sin Asignación"}</td>
            <td>${task.periodicidad}</td>
            <td>${task.fecha_inicio_generacion}</td>
        `;

        // Evento para abrir el modal al hacer doble clic
        row.addEventListener("dblclick", () => openEditModal(task));

        return row;
    }

    // Abrir el Modal de Edición
    function openEditModal(task) {
        console.log("Abriendo modal para tarea:", task);

        // Rellenar el formulario con los datos de la tarea
        document.getElementById("periodicidad").value = task.periodicidad || "SEMANAL";
        document.getElementById("fecha_inicio_generacion").value = task.fecha_inicio_generacion || "";

        // Añadir el ID de la tarea al formulario para identificarla
        editTaskForm.dataset.taskId = task.id;

        // Mostrar el modal
        editTaskModal.classList.remove("hidden");
    }

    // Guardar Cambios en el Formulario de Edición
    editTaskForm.addEventListener("submit", (e) => {
        e.preventDefault(); // Prevenir el comportamiento predeterminado
        console.log("Formulario enviado, previniendo recarga.");

        const taskId = editTaskForm.dataset.taskId; // Obtener el ID de la tarea
        const periodicidad = document.getElementById("periodicidad").value;
        const fechaInicioGeneracion = document.getElementById("fecha_inicio_generacion").value;

        // Validación: si la periodicidad no es "NO", la fecha debe estar seleccionada
        if (periodicidad !== "NO" && !fechaInicioGeneracion) {
            alert("Por favor, selecciona una fecha de inicio de generación.");
            return; // Detener el envío si no pasa la validación
        }

        fetch(`${apiEndpoint}/${taskId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                periodicidad,
                fecha_inicio_generacion: periodicidad !== "NO" ? fechaInicioGeneracion : null, // Solo mantener la fecha si no es "NO"
            }),
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error al actualizar la tarea.");
                }
                return response.json();
            })
            .then(data => {
                console.log("Tarea actualizada:", data);

                // Cerrar el modal
                editTaskModal.classList.add("hidden");
                showSuccessNotification('Tarea editada con éxito');
                // Recargar las tareas
                loadTasks();
            })
            .catch(error => {
                console.error("Error al guardar cambios:", error);
                showErrorNotification('Error al editar la tarea.');
                alert("Hubo un error al actualizar la tarea.");
            });
    });

    function closeModal() {
        editTaskModal.classList.add("hidden");
    }

    closeModalButton.addEventListener("click", closeModal);
    cancelEditButton.addEventListener("click", closeModal);

    // Manejar Cambios en los Filtros
    if (filtersForm) {
        filtersForm.addEventListener("input", () => {
            const filters = Array.from(filtersForm.elements).reduce((acc, element) => {
                if (element.value) {
                    acc[element.name] = element.value;
                }
                return acc;
            }, {});
            console.log("Filtros aplicados:", filters); // Depuración
            loadTasks(filters);
        });
    } else {
        console.error("El formulario de filtros no está disponible en el DOM.");
    }

    // Inicializar
    loadUsers();
    loadTasks();
});
