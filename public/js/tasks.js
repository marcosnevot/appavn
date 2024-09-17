// Añadir el event listener para manejar el envío del formulario
document.addEventListener('DOMContentLoaded', function () {
    console.log('El script ha sido cargado correctamente.');

    const taskForm = document.getElementById('task-form');
    const addTaskForm = document.getElementById('add-task-form'); // El propio formulario

    const asuntoInput = document.getElementById('asunto-input');
    const asuntoIdInput = document.getElementById('asunto-id-input'); // Campo oculto para el ID del asunto
    let asuntosData = JSON.parse(document.getElementById('asuntos-data').getAttribute('data-asuntos'));

    const modal = document.getElementById('confirm-modal'); // Modal de confirmación
    const modalMessage = document.getElementById('modal-message');

    // Mostrar el formulario cuando se pulsa el botón de "Nueva Tarea"
    document.getElementById('new-task-button').addEventListener('click', function () {
        taskForm.style.display = 'block';
        setTimeout(() => {
            taskForm.classList.remove('hide');
            taskForm.classList.add('show');
        }, 10);
    });

    // Ocultar el formulario cuando se pulsa el botón de cerrar
    document.getElementById('close-task-form').addEventListener('click', function () {
        closeTaskForm();
    });

    // Ocultar el formulario cuando se hace clic fuera de él
    document.addEventListener('click', function (event) {
        const isInsideForm = taskForm.contains(event.target);
        const isInsideModal = document.getElementById('confirm-modal').contains(event.target);
        const isNewTaskButton = document.getElementById('new-task-button').contains(event.target);

        // Verifica si el clic no es dentro del formulario, ni dentro del modal ni en el botón de nueva tarea
        if (!isInsideForm && !isInsideModal && !isNewTaskButton) {
            if (taskForm.classList.contains('show')) {
                closeTaskForm();
            }
        }
    });

    // Función para cerrar el formulario
    function closeTaskForm() {
        taskForm.classList.remove('show');
        taskForm.classList.add('hide');
        setTimeout(() => {
            taskForm.style.display = 'none';
        }, 400);
    }



    // Función para manejar el envío del formulario
    function submitTaskForm() {
        const formData = {
            cliente_id: document.querySelector('input[name="cliente_id"]').value,
            asunto_id: asuntoIdInput.value, // Si el asunto es nuevo, esto estará vacío
            asunto_nombre: asuntoIdInput.value === '' ? asuntoInput.value : null, // Si es nuevo, se envía el nombre
            tipo_id: document.querySelector('select[name="tipo_id"]').value,
            subtipo: document.querySelector('select[name="subtipo"]').value,
            estado: document.querySelector('select[name="estado"]').value,
            users: Array.from(document.querySelectorAll('select[name="users[]"] option:checked')).map(option => option.value),
            archivo: document.querySelector('input[name="archivo"]').value,
            descripcion: document.querySelector('textarea[name="descripcion"]').value,
            observaciones: document.querySelector('textarea[name="observaciones"]').value,
            facturable: document.querySelector('input[name="facturable"]').checked ? 1 : 0,
            facturado: document.querySelector('input[name="facturado"]').value,
            precio: document.querySelector('input[name="precio"]').value,
            suplido: document.querySelector('input[name="suplido"]').value,
            coste: document.querySelector('input[name="coste"]').value,
            fecha_inicio: document.querySelector('input[name="fecha_inicio"]').value,
            fecha_vencimiento: document.querySelector('input[name="fecha_vencimiento"]').value,
            fecha_imputacion: document.querySelector('input[name="fecha_imputacion"]').value,
            tiempo_previsto: document.querySelector('input[name="tiempo_previsto"]').value,
            tiempo_real: document.querySelector('input[name="tiempo_real"]').value
        };

        console.log('Datos del formulario:', formData);

        fetch('/tareas', {
            method: 'POST',
            body: JSON.stringify(formData),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('Tarea creada:', data.task);

                    // Si hay un nuevo asunto en la respuesta, lo añadimos a la lista de asuntos
                    if (data.task.asunto) {
                        asuntosData.push(data.task.asunto); // Añadir el nuevo asunto a la lista de asuntos
                    }

                    showSuccessNotification();
                    document.getElementById('add-task-form').reset(); // Resetear el formulario
                } else {
                    console.error('Errores de validación:', data.errors);
                }
            })
            .catch(errorData => {
                // Manejar y mostrar los errores
                if (errorData.errors) {
                    displayFormErrors(errorData.errors);
                } else {
                    console.error('Error:', errorData.message || 'Error desconocido');
                }
            });
    }

    // Función para mostrar los errores en los campos del formulario
    function displayFormErrors(errors) {
        // Limpiar mensajes de error previos
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(msg => msg.remove());

        // Iterar sobre los errores y mostrarlos
        Object.keys(errors).forEach(key => {
            const field = document.querySelector(`[name="${key}"]`);
            if (field) {
                const errorMessage = document.createElement('div');
                errorMessage.classList.add('error-message');
                errorMessage.textContent = errors[key][0];  // Mostrar solo el primer error por campo
                field.parentNode.appendChild(errorMessage); // Añadir el mensaje de error al lado del campo
            }
        });
    }



    // Manejar el evento de envío del formulario
    addTaskForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevenir el comportamiento predeterminado de recargar la página

        // Validar si el cliente seleccionado está en la lista
        const clienteInputValue = document.getElementById('cliente-input').value;
        const clienteIdValue = document.getElementById('cliente-id-input').value;
        const clienteValido = clientes.some(cliente =>
            `${cliente.nombre_fiscal} (${cliente.nif})` === clienteInputValue && cliente.id == clienteIdValue
        );

        if (!clienteValido) {
            alert("Por favor, selecciona un cliente válido de la lista.");
            return;
        }

        // Validar si el asunto existe o si es nuevo
        const asuntoInputValue = asuntoInput.value.trim(); // Asegurarse de eliminar espacios innecesarios
        const asuntoValido = asuntosData.some(asunto =>
            asunto.nombre.toUpperCase() === asuntoInputValue.toUpperCase() // Comparación estricta en mayúsculas
        );

        if (!asuntoValido) {
            showModalConfirmAsunto(asuntoInputValue);
        } else {
            submitTaskForm();
        }
    });

    // Escuchar el canal y el evento del WebSocket
    window.Echo.channel('tasks')
        .listen('TaskCreated', (e) => {
            console.log('Nueva tarea creada:', e);
            updateTaskTable(e.task); // Actualiza la tabla cuando se crea una nueva tarea
        });

    // Buscador de clientes

    // Obtener los datos de clientes desde el atributo data-clientes
    const clientesData = document.getElementById('clientes-data');
    const clientes = JSON.parse(clientesData.getAttribute('data-clientes'));

    const input = document.getElementById('cliente-input');
    const clienteIdInput = document.getElementById('cliente-id-input'); // Campo oculto para el ID del cliente
    const clienteList = document.getElementById('cliente-list');
    let selectedIndex = -1;

    // Función para mostrar la lista filtrada
    function filterClientes(query) {
        const filtered = clientes.filter(cliente =>
            cliente.nombre_fiscal.toLowerCase().includes(query.toLowerCase()) ||
            cliente.nif.toLowerCase().includes(query.toLowerCase()) // Comparar con NIF
        );
        renderList(filtered);
    }

    // Función para renderizar la lista
    function renderList(filtered) {
        clienteList.innerHTML = '';
        if (filtered.length === 0) {
            clienteList.style.display = 'none';
            return;
        }
        clienteList.style.display = 'block';
        filtered.forEach((cliente, index) => {
            const li = document.createElement('li');
            li.textContent = `${cliente.nombre_fiscal} (${cliente.nif})`; // Mostrar nombre y NIF
            li.setAttribute('data-id', cliente.id);
            li.classList.add('autocomplete-item');
            if (index === selectedIndex) {
                li.classList.add('active');
            }
            li.addEventListener('click', () => selectCliente(cliente));
            clienteList.appendChild(li);
        });
    }

    // Función para seleccionar un cliente y autocompletar el input
    function selectCliente(cliente) {
        input.value = `${cliente.nombre_fiscal} (${cliente.nif})`; // Mostrar tanto el nombre como el NIF
        clienteIdInput.value = cliente.id; // Almacena el id en el campo oculto
        clienteList.style.display = 'none';
    }

    // Mostrar lista completa de clientes al hacer clic en el campo
    input.addEventListener('focus', function () {
        if (input.value === '') {
            renderList(clientes); // Muestra la lista completa si no se ha escrito nada
        }
    });

    // Manejador del evento input para filtrar clientes
    input.addEventListener('input', function () {
        selectedIndex = -1;
        filterClientes(this.value);
        clienteIdInput.value = '';
    });

    // Manejador para la navegación por teclado
    input.addEventListener('keydown', function (e) {
        const items = document.querySelectorAll('.autocomplete-item');
        if (e.key === 'ArrowDown') {
            selectedIndex = (selectedIndex + 1) % items.length;
            updateActiveItem(items);
        } else if (e.key === 'ArrowUp') {
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
            updateActiveItem(items);
        } else if (e.key === 'Enter') {
            e.preventDefault(); // Prevenir el comportamiento por defecto de 'Enter'
            if (selectedIndex >= 0 && selectedIndex < items.length) {
                const selectedCliente = clientes.find(cliente =>
                    `${cliente.nombre_fiscal} (${cliente.nif})` === items[selectedIndex].textContent
                );
                selectCliente(selectedCliente);
            }
        }
    });

    // Función para actualizar el ítem activo en la lista
    function updateActiveItem(items) {
        items.forEach(item => item.classList.remove('active'));
        if (items[selectedIndex]) {
            items[selectedIndex].classList.add('active');
        }
    }

    // Cerrar la lista si se hace clic fuera
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.autocomplete')) {
            clienteList.style.display = 'none';
        }
    });

    // Asunto - Autocompletado y creación de nuevos asuntos

    // Obtener los datos de asuntos desde el atributo data-asuntos
    const asuntoList = document.getElementById('asunto-list');
    let selectedAsuntoIndex = -1;

    // Función para mostrar la lista filtrada de asuntos
    function filterAsuntos(query) {
        return asuntosData.filter(asunto =>
            asunto.nombre.toLowerCase().includes(query.toLowerCase())
        );
    }



    // Mostrar la lista completa al hacer clic
    asuntoInput.addEventListener('focus', function () {
        if (asuntoInput.value === '') {
            renderAsuntoList(asuntosData); // Mostrar todos los asuntos
        }
    });

    // Filtrar asuntos en tiempo real mientras se escribe
    asuntoInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();  // Convertir el texto a mayúsculas
        const filteredAsuntos = filterAsuntos(this.value);
        renderAsuntoList(filteredAsuntos);

        // Si no hay coincidencias, se permite crear un nuevo asunto
        if (filteredAsuntos.length === 0) {
            asuntoIdInput.value = '';  // Limpiar el campo oculto para nuevos asuntos
        }
    });


    // Manejar la navegación por teclado
    asuntoInput.addEventListener('keydown', function (e) {
        const items = document.querySelectorAll('.autocomplete-item');
        if (e.key === 'ArrowDown') {
            asuntoSelectedIndex = (asuntoSelectedIndex + 1) % items.length;
            updateActiveAsuntoItem(items);
        } else if (e.key === 'ArrowUp') {
            asuntoSelectedIndex = (asuntoSelectedIndex - 1 + items.length) % items.length;
            updateActiveAsuntoItem(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (asuntoSelectedIndex >= 0 && asuntoSelectedIndex < items.length) {
                const selectedAsunto = asuntosData.find(asunto =>
                    asunto.nombre === items[asuntoSelectedIndex].textContent
                );
                selectAsunto(selectedAsunto);
            }
        }
    });

    // Actualizar el ítem activo de la lista de asuntos
    function updateActiveAsuntoItem(items) {
        items.forEach(item => item.classList.remove('active'));
        if (items[asuntoSelectedIndex]) {
            items[asuntoSelectedIndex].classList.add('active');
        }
    }

    // Cerrar la lista si se hace clic fuera
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.autocomplete')) {
            asuntoList.style.display = 'none';
        }
    });

    // Función para renderizar la lista de asuntos actualizada
    function renderAsuntoList(filtered) {
        asuntoList.innerHTML = '';  // Limpiar la lista previa
        if (filtered.length === 0) {
            asuntoList.style.display = 'none';  // Si no hay resultados, oculta la lista
            return;
        }
        asuntoList.style.display = 'block';  // Muestra la lista si hay resultados
        filtered.forEach((asunto, index) => {
            const li = document.createElement('li');
            li.textContent = asunto.nombre;
            li.setAttribute('data-id', asunto.id);
            li.classList.add('autocomplete-item');
            li.addEventListener('click', () => selectAsunto(asunto));  // Permite seleccionar el asunto
            asuntoList.appendChild(li);  // Añadir el asunto a la lista
        });
    }


    function selectAsunto(asunto) {
        const asuntoInput = document.getElementById('asunto-input');
        const asuntoIdInput = document.getElementById('asunto-id-input');
        asuntoInput.value = asunto.nombre;
        asuntoIdInput.value = asunto.id;
        document.getElementById('asunto-list').style.display = 'none';
    }

    // Mostrar el modal de confirmación
    function showModalConfirmAsunto(asunto) {
        modalMessage.textContent = `El asunto "${asunto}" no existe en la base de datos. ¿Deseas crearlo como un nuevo asunto?`;
        modal.style.display = 'flex'; // Usamos flex para centrar el modal

        // Evitar el cierre al hacer clic fuera del modal
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                event.stopPropagation(); // Prevenir cierre por clic fuera del modal
            }
        });

        // Confirmar creación del asunto
        document.getElementById('confirm-modal-yes').addEventListener('click', function () {
            modal.style.display = 'none';
            submitTaskForm(); // Enviar formulario con nuevo asunto
        });

        // Cancelar la creación
        document.getElementById('confirm-modal-no').addEventListener('click', function () {
            modal.style.display = 'none'; // Solo cerrar si presiona "No"
        });
    }

    // Ordenar la tabla
    const tableBody = document.querySelector('table tbody');
    const sortSelect = document.getElementById('sort-select');

    // Función para ordenar la tabla
    function sortTableBy(attribute) {
        // Obtener las filas actuales de la tabla
        let rows = Array.from(tableBody.querySelectorAll('tr'));

        // Definir la lógica de ordenación según el atributo seleccionado
        rows.sort((a, b) => {
            let valA, valB;

            switch (attribute) {
                case 'cliente':
                    valA = a.children[2].textContent.trim().toLowerCase();
                    valB = b.children[2].textContent.trim().toLowerCase();
                    break;
                case 'asunto':
                    valA = a.children[1].textContent.trim().toLowerCase();
                    valB = b.children[1].textContent.trim().toLowerCase();
                    break;
                case 'estado':
                    valA = a.children[5].textContent.trim().toLowerCase();
                    valB = b.children[5].textContent.trim().toLowerCase();
                    break;
                case 'fecha_creacion':
                default:
                    // Utilizar created_at (última columna oculta)
                    valA = new Date(a.children[20].textContent); // Índice de created_at
                    valB = new Date(b.children[20].textContent);
                    return valB - valA; // Orden descendente por fecha de creación
            }

            // Ordenar alfabéticamente para otros atributos
            return valA > valB ? 1 : (valA < valB ? -1 : 0);
        });

        // Vaciar el contenido de la tabla y agregar las filas ordenadas
        tableBody.innerHTML = '';
        rows.forEach(row => tableBody.appendChild(row));
    }

    // Evento para cambiar la ordenación cuando se selecciona un nuevo atributo
    sortSelect.addEventListener('change', function () {
        const selectedAttribute = this.value;
        sortTableBy(selectedAttribute);
    });

    // Ordenar inicialmente por fecha de creación (ID)
    sortTableBy('fecha_creacion');


});


// Función para actualizar la tabla con la nueva tarea
function updateTaskTable(task) {
    const tableBody = document.querySelector('table tbody');
    const row = document.createElement('tr');

    row.innerHTML = `
        <td>${task.id}</td>
        <td>${task.asunto.nombre || 'Sin asunto'}</td>
        <td>${task.cliente.nombre_fiscal || 'Sin cliente'}</td>
        <td>${task.tipo ? task.tipo.nombre : 'Sin tipo'}</td>
        <td>${task.subtipo}</td>
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
    `;

    // Insertar la nueva fila al principio del cuerpo de la tabla
    if (tableBody.firstChild) {
        tableBody.insertBefore(row, tableBody.firstChild);
    } else {
        tableBody.appendChild(row);
    }
}

// Función para mostrar la notificación de éxito
function showSuccessNotification(message = "Tarea creada exitosamente") {
    const notification = document.getElementById('success-notification');
    const notificationMessage = document.querySelector('.notification-message');
    const notificationTimer = document.querySelector('.notification-timer');

    notificationMessage.textContent = message;
    notificationTimer.style.width = '100%';

    // Mostrar la notificación
    notification.classList.add('show');
    notification.classList.remove('hide');

    // Iniciar la reducción de la barra de progreso
    setTimeout(() => {
        notificationTimer.style.width = '0%';
    }, 10); // Inicia casi inmediatamente para suavizar la transición

    // Ocultar la notificación después de 3 segundos
    setTimeout(() => {
        notification.classList.add('hide');
        notification.classList.remove('show');
    }, 3000);
}