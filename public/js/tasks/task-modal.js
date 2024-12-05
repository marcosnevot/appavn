document.addEventListener('DOMContentLoaded', function () {
    console.log('El script task-modal.js ha sido cargado correctamente.');


    const modalContent = document.getElementById('task-detail-modal-content');
    const editTaskFormContainer = document.getElementById('edit-task-form-container'); // Cambiado a la nueva ID del contenedor
    const editTaskForm = document.getElementById('edit-task-form');

    const btnEditTaskForm = document.getElementById('btn-edit-task-form');
    const btnCloseEditForm = document.getElementById('close-edit-task-form');

    // Referencia al campo de "Estado" y al contenedor de "Duplicar" usando el atributo `name`
    const estadoSelect = document.querySelector('[name="estadoEdit"]');  // Usar `name` para asegurar que se encuentra el campo correcto
    const duplicarContainer = document.getElementById('duplicar').closest('.form-group'); // Contenedor del checkbox de duplicar
    const duplicarCheckbox = document.getElementById('duplicar');

    const modal = document.getElementById('confirm-modal'); // Modal de confirmación
    const modalMessage = document.getElementById('modal-message');
    const modalAsuntoMessage = document.getElementById('modal-asunto-message');
    const modalTipoMessage = document.getElementById('modal-tipo-message');
    const modalClienteMessage = document.getElementById('modal-cliente-message');


    // Función para mostrar u ocultar el campo "Duplicar"
    function toggleDuplicarVisibility() {
        if (estadoSelect.value === 'COMPLETADA') {
            duplicarContainer.style.display = 'flex'; // Mostrar el checkbox
        } else {
            duplicarContainer.style.display = 'none'; // Ocultar el checkbox
            duplicarCheckbox.checked = false; // Desmarcar por seguridad
        }
    }

    // Escuchar cambios en el campo de "Estado" y ejecutar la función
    estadoSelect.addEventListener('change', toggleDuplicarVisibility);



    // Configurar un MutationObserver para detectar cambios 
    const observerDuplicar = new MutationObserver(() => {
        toggleDuplicarVisibility(); // Llamar a la función cuando se detecte un cambio

        // Evitar envío del formulario al presionar "Enter" en campos de entrada
        Array.from(editTaskForm.elements).forEach(element => {
            if (['text', 'number', 'date', 'textarea', 'checkbox'].includes(element.type)) {
                element.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); // Evitar el envío del formulario
                        if (element.type === 'checkbox') {
                            element.checked = !element.checked; // Cambia el estado del checkbox
                        }
                    }
                });
            }
        });

        // Permitir "Enter" en los botones "Guardar" y "Cerrar"
        [btnEditTaskForm, btnCloseEditForm].forEach(button => {
            button.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    button.click(); // Ejecuta el clic del botón
                }
            });
        });

    });
    if (editTaskFormContainer) {
        observerDuplicar.observe(editTaskFormContainer, { childList: true, subtree: true });
    }


    // Asignar Usuarios a una tarea (formulario de edición)
    const userSelectEdit = document.getElementById('user-select-edit');
    const userListEdit = document.getElementById('user-list-edit');
    const selectedUsersContainerEdit = document.getElementById('selected-users-edit');
    const userIdsInputEdit = document.getElementById('user-ids-edit');
    let selectedUsersEdit = [];
    let currentFocusEdit = -1;


    if (!modalContent) {
        console.error('Modal content not found!');
        return;
    }

    // Observador de cambios para detectar cuando se carga una nueva tarea en el modal
    const observer = new MutationObserver(() => {
        const deleteButton = document.getElementById('delete-task-button');
        const editButton = document.getElementById('edit-task-button');

        const closeEditButton = document.getElementById('close-edit-task-form');

        // Asignar evento al botón de borrar
        if (deleteButton) {
            deleteButton.addEventListener('click', () => {
                const taskId = deleteButton.getAttribute('data-task-id');
                const confirmDelete = confirm('¿Estás seguro de que quieres borrar esta tarea?');

                if (confirmDelete) {
                    deleteCustomer(taskId);
                }
            });
        }

        // Asignar evento al botón de editar
        if (editButton && editTaskForm) {
            editButton.addEventListener('click', () => {
                const taskId = deleteButton.getAttribute('data-task-id'); // Obtener el ID de la tarea

                // Mostrar el formulario de edición
                editTaskFormContainer.style.display = 'block';
                setTimeout(() => {
                    editTaskFormContainer.classList.remove('hide');
                    editTaskFormContainer.classList.add('show');
                }, 10);

                // Llamada AJAX para obtener los datos de la tarea
                fetch(`/tareas/${taskId}/edit`)
                    .then(response => response.json())
                    .then(task => {
                        console.log('Datos recibidos:', task); // Verificar la respuesta
                        if (task.error) {
                            console.error('Error al cargar los datos de la tarea:', task.error);
                            return;
                        }
                        observer.disconnect(); // Desconecta el observer para evitar conflictos

                        populateEditForm(task);
                        observer.observe(modalContent, { childList: true, subtree: true });


                        // Verificar que los elementos existen en el DOM antes de rellenar

                        const subtipoSelect = document.querySelector('select[name="subtipoEdit"]');
                        const estadoSelect = document.querySelector('select[name="estadoEdit"]');

                        const descripcionTextarea = document.querySelector('textarea[name="descripcionEdit"]');
                        const observacionesTextarea = document.querySelector('textarea[name="observacionesEdit"]');
                        const facturableCheckbox = document.querySelector('input[name="facturableEdit"]');
                        const facturadoInput = document.querySelector('select[name="facturadoEdit"]');

                        const fechaInicioInput = document.querySelector('input[name="fecha_inicioEdit"]');
                        const fechaPlanificacionInput = document.querySelector('input[name="fecha_planificacionEdit"]');
                        const fechaVencimientoInput = document.querySelector('input[name="fecha_vencimientoEdit"]');

                        const tiempoPrevistoInput = document.querySelector('input[name="tiempo_previstoEdit"]');
                        const tiempoRealInput = document.querySelector('input[name="tiempo_realEdit"]');

                        const taskIdInput = document.getElementById('task_id');


                        // Asignar valores de manera segura


                        if (subtipoSelect) subtipoSelect.value = task.subtipo || 'ORDINARIA';
                        if (estadoSelect) estadoSelect.value = task.estado || 'PENDIENTE';

                        if (descripcionTextarea) descripcionTextarea.value = task.descripcion || '';
                        if (observacionesTextarea) observacionesTextarea.value = task.observaciones || '';
                        if (facturableCheckbox) facturableCheckbox.checked = !!task.facturable;
                        if (facturadoInput) facturadoInput.value = task.facturado || '';

                        if (fechaPlanificacionInput) fechaPlanificacionInput.value = task.fecha_planificacion || '';
                        if (fechaInicioInput) fechaInicioInput.value = task.fecha_inicio || '';
                        if (fechaVencimientoInput) fechaVencimientoInput.value = task.fecha_vencimiento || '';

                        if (tiempoPrevistoInput) tiempoPrevistoInput.value = task.tiempo_previsto || '';
                        if (tiempoRealInput) tiempoRealInput.value = task.tiempo_real || '';
                        if (taskIdInput) {
                            taskIdInput.value = task.id; // Asegúrate de que el ID de la tarea esté disponible
                        }


                        // Cargar y asignar los usuarios seleccionados
                        if (task.users && task.users.length > 0) {
                            selectedUsersEdit = task.users.map(user => ({ id: user.id, name: user.name }));
                            updateSelectedUsersDisplayEdit();
                            updateUserIdsInputEdit();

                            // Marcar los checkboxes de los usuarios preseleccionados y añadirlos al array `selectedUsersEdit`
                            userListEdit.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                                const userId = parseInt(checkbox.value);
                                if (task.users.some(user => user.id === userId)) {
                                    checkbox.checked = true;
                                    // Asegurarnos de que estos usuarios preseleccionados estén en el array
                                    if (!selectedUsersEdit.some(user => user.id === userId)) {
                                        const userName = checkbox.nextElementSibling.textContent;
                                        selectedUsersEdit.push({ id: userId, name: userName });
                                    }
                                } else {
                                    checkbox.checked = false;
                                }
                            });

                            updateSelectedUsersDisplayEdit(); // Actualizar la visualización de los usuarios
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener los datos de la tarea:', error);
                    });
            });
        }


        // Asignar evento al botón de cerrar el formulario de edición
        if (closeEditButton && editTaskFormContainer) {
            closeEditButton.addEventListener('click', () => {
                closeEditCustomerForm(editTaskFormContainer);
            });
        }
    });

    // Iniciar el observador de cambios en el contenido del modal
    observer.observe(modalContent, { childList: true, subtree: true });

    // Funciones para manejar la selección de usuarios en el formulario de edición

    userSelectEdit.addEventListener('click', toggleUserListEdit);
    userSelectEdit.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleUserListEdit();
        } else if (e.key === 'Escape') {
            userListEdit.style.display = 'none';
        }
    });

    function toggleUserListEdit() {
        if (userListEdit.style.display === 'block') {
            userListEdit.style.display = 'none';
        } else {
            userListEdit.style.display = 'block';
            currentFocusEdit = -1;
            focusNextCheckboxEdit(1);
        }
    }

    // Manejar el cambio en los checkboxes de la lista de usuarios
    userListEdit.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const userId = parseInt(this.value);
            const userName = this.nextElementSibling.textContent;

            if (this.checked) {
                // Agregar usuario a la lista seleccionada si no está ya
                if (!selectedUsersEdit.some(user => user.id === userId)) {
                    selectedUsersEdit.push({ id: userId, name: userName });
                }
            } else {
                // Eliminar usuario de la lista seleccionada
                selectedUsersEdit = selectedUsersEdit.filter(user => user.id !== userId);
            }

            // Actualizar la visualización y los IDs de los usuarios seleccionados
            updateSelectedUsersDisplayEdit();
        });
    });

    // Función para actualizar la visualización de los usuarios seleccionados
    function updateSelectedUsersDisplayEdit() {
        selectedUsersContainerEdit.innerHTML = ''; // Limpiar la visualización actual
        selectedUsersEdit.forEach(user => {
            const span = document.createElement('span');
            span.textContent = user.name;
            span.classList.add('selected-user'); // Puedes agregar estilos CSS si es necesario
            selectedUsersContainerEdit.appendChild(span);
        });
    }

    // Función para actualizar el campo oculto con los IDs de los usuarios seleccionados
    function updateUserIdsInputEdit() {
        userIdsInputEdit.value = selectedUsersEdit.map(user => user.id).join(','); // Convertir a una lista de IDs separados por coma
    }

    // Cerrar la lista de usuarios si se hace clic fuera
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.custom-select') && e.target !== userSelectEdit) {
            userListEdit.style.display = 'none';
        }
    });

    // Navegar con el teclado en la lista de usuarios
    userListEdit.addEventListener('keydown', function (e) {
        const checkboxes = userListEdit.querySelectorAll('input[type="checkbox"]');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            focusNextCheckboxEdit(1);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            focusNextCheckboxEdit(-1);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentFocusEdit >= 0 && currentFocusEdit < checkboxes.length) {
                checkboxes[currentFocusEdit].click(); // Simular un click para seleccionar el usuario
            }
        } else if (e.key === 'Escape') {
            userListEdit.style.display = 'none';
            userSelectEdit.focus();
        }
    });

    // Función para manejar el enfoque de los checkboxes con el teclado
    function focusNextCheckboxEdit(direction) {
        const checkboxes = userListEdit.querySelectorAll('input[type="checkbox"]');
        currentFocusEdit = (currentFocusEdit + direction + checkboxes.length) % checkboxes.length;
        checkboxes[currentFocusEdit].focus();
    }

    let nuevoCliente = null;
    let nuevoAsunto = null;
    let nuevoTipo = null;

    // Manejar el envío del formulario de edición
    editTaskForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevenir el envío normal del formulario
        console.log("Evento submit interceptado");

        const taskId = document.getElementById('task_id').value;
        const duplicarCheckbox = document.getElementById('duplicar'); // Referencia al checkbox de Duplicar

        // Mostrar alerta si Duplicar está marcado
        if (duplicarCheckbox.checked) {
            const confirmDuplicar = confirm("Estás a punto de duplicar la tarea. ¿Deseas continuar?");
            if (!confirmDuplicar) return;
        }

        // Obtener referencias a los inputs
        const clienteInput = document.getElementById('cliente-inputEdit');
        const clienteIdInput = document.getElementById('cliente-id-inputEdit');
        const asuntoInput = document.getElementById('asunto-inputEdit');
        const asuntoIdInput = document.getElementById('asunto-id-inputEdit');
        const tipoInput = document.getElementById('tipo-inputEdit');
        const tipoIdInput = document.getElementById('tipo-id-inputEdit');

        // Validar que todos los inputs existan
        if (
            !clienteInput ||
            !clienteIdInput ||
            !asuntoInput ||
            !asuntoIdInput ||
            !tipoInput ||
            !tipoIdInput
        ) {
            console.error("Uno o más elementos de entrada no se encontraron en el DOM.", {
                clienteInput: clienteInput || "No encontrado",
                clienteIdInput: clienteIdInput || "No encontrado",
                asuntoInput: asuntoInput || "No encontrado",
                asuntoIdInput: asuntoIdInput || "No encontrado",
                tipoInput: tipoInput || "No encontrado",
                tipoIdInput: tipoIdInput || "No encontrado",
            });
            return; // Salir del flujo si faltan elementos
        }

        nuevoAsunto = null; // Limpiar variables de nuevos valores
        nuevoTipo = null;
        nuevoCliente = null;


        // Validar si el cliente existe o si es nuevo
        const clienteInputValue = clienteInput.value.trim();
        const clienteValido = clientesData.some(cliente =>
            cliente.nombre_fiscal.toUpperCase() === clienteInputValue.toUpperCase()
        );

        if (!clienteValido) {
            nuevoCliente = clienteInputValue; // Almacenar el nuevo cliente si no existe
        }

        // Validar si el asunto existe o si es nuevo
        const asuntoInputValue = asuntoInput.value.trim();
        const asuntoValido = asuntosData.some(asunto =>
            asunto.nombre.toUpperCase() === asuntoInputValue.toUpperCase()
        );

        if (!asuntoValido) {
            nuevoAsunto = asuntoInputValue; // Almacenar el nuevo asunto si no existe
        }

        // Validar si el tipo de tarea existe o si es nuevo
        const tipoInputValue = tipoInput.value.trim();
        const tipoValido = tiposData.some(tipo =>
            tipo.nombre.toUpperCase() === tipoInputValue.toUpperCase()
        );

        if (!tipoValido) {
            nuevoTipo = tipoInputValue; // Almacenar el nuevo tipo si no existe
        }

        // Si hay un nuevo asunto, cliente o un nuevo tipo, mostrar el modal
        if (nuevoAsunto || nuevoTipo || nuevoCliente) {
            showModalConfirmEdit(() => {
                submitEditForm(taskId, duplicarCheckbox, nuevoCliente, nuevoAsunto, nuevoTipo);
            });
        } else {
            // Enviar el formulario directamente si no hay nuevos elementos
            submitEditForm(taskId, duplicarCheckbox, nuevoCliente, nuevoAsunto, nuevoTipo);
        }



    });


    function submitEditForm(taskId, duplicarCheckbox, nuevoCliente, nuevoAsunto, nuevoTipo, additionalClientData = {}) {
        console.log("Entrando en submitEditForm", { taskId, nuevoCliente, nuevoAsunto, nuevoTipo });

        // Asegúrate de declarar todas las referencias necesarias dentro de esta función
        const clienteInput = document.getElementById('cliente-inputEdit');
        const asuntoInput = document.getElementById('asunto-inputEdit');
        const tipoInput = document.getElementById('tipo-inputEdit');
        const clienteIdInput = document.getElementById('cliente-id-inputEdit');
        const asuntoIdInput = document.getElementById('asunto-id-inputEdit');
        const tipoIdInput = document.getElementById('tipo-id-inputEdit');

        const clienteInputValue = clienteInput.value.trim().toUpperCase();
        nuevoCliente = checkIfNewItem(clienteInputValue, clientesData, clienteIdInput);

        const asuntoInputValue = asuntoInput.value.trim().toUpperCase();
        nuevoAsunto = checkIfNewItem(asuntoInputValue, asuntosData, asuntoIdInput);

        const tipoInputValue = tipoInput.value.trim().toUpperCase();
        nuevoTipo = checkIfNewItem(tipoInputValue, tiposData, tipoIdInput);

        const formData = new FormData(editTaskForm);
        formData.append('cliente_nombreEdit', nuevoCliente || '');
        formData.append('cliente_nifEdit', nuevoCliente ? additionalClientData.clienteNIF : '');
        formData.append('cliente_emailEdit', nuevoCliente ? additionalClientData.clienteEmail : '');
        formData.append('cliente_telefonoEdit', nuevoCliente ? additionalClientData.clienteTelefono : '');
        formData.append('asunto_nombreEdit', nuevoAsunto || '');
        formData.append('tipo_nombreEdit', nuevoTipo || '');

        // Agregar manualmente el campo _method para simular una solicitud PUT
        formData.append('_method', 'PUT');
        console.log({
            tipo_id: document.getElementById('tipo-id-inputEdit').value,
            tipo_nombre: document.getElementById('tipo-inputEdit').value,
        });

        // Obtener los usuarios seleccionados y añadir cada uno al FormData
        const selectedUsersEdit = Array.from(document.querySelectorAll('#user-list-edit input[type="checkbox"]:checked'))
            .map(checkbox => checkbox.value);

        selectedUsersEdit.forEach(userId => {
            formData.append('usersEdit[]', userId);
        });

        formData.append('duplicar', duplicarCheckbox.checked ? '1' : '0');

        fetch(`/tareas/${taskId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        console.error("Error en la respuesta:", err);
                        throw new Error('Error en la respuesta del servidor');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Actualizar listas locales si se crearon nuevos elementos
                    if (data.task.cliente && !clientesData.some(c => c.id === data.task.cliente.id)) {
                        clientesData.push(data.task.cliente);
                    }
                    if (data.task.asunto && !asuntosData.some(a => a.id === data.task.asunto.id)) {
                        asuntosData.push(data.task.asunto);
                    }
                    if (data.task.tipo && !tiposData.some(t => t.id === data.task.tipo.id)) {
                        tiposData.push(data.task.tipo);
                    }

                    showNotification('Tarea actualizada correctamente', 'info');
                    closeEditCustomerForm(editTaskFormContainer); // Cerrar el formulario de edición
                } else {
                    showNotification('Error al actualizar la tarea', 'error');
                    console.error('Errores de validación:', data.errors);
                }
            })
            .catch(error => {
                console.error('Error al actualizar la tarea:', error);
                showNotification('Error al actualizar la tarea', 'error');
            });
    }

    Echo.channel('tasks')
        .listen('.TaskUpdated', (data) => {
            console.log('Tarea actualizada (capturada por listen):', data);
            updateTaskRow(data.task);
        })
        .listenForWhisper('TaskUpdated', (data) => {
            console.log('Tarea actualizada (capturada por whisper):', data);
        })
        .listen('TaskDeleted', (data) => {
            console.log('Tarea eliminada (capturada por listen):', data);
            deleteTaskRow(data.taskId);
        })
        .subscribed(() => {
            console.log('Conectado al canal de tareas');
        });

    // Mostrar el modal de confirmación
    function showModalConfirmEdit(callback) {
        // Actualizar mensajes del modal
        if (modalClienteMessage) {
            modalClienteMessage.textContent = nuevoCliente
                ? `El cliente "${nuevoCliente}" no existe. ¿Deseas crearlo?`
                : '';
        }

        if (modalAsuntoMessage) {
            modalAsuntoMessage.textContent = nuevoAsunto
                ? `El asunto "${nuevoAsunto}" no existe. ¿Deseas crearlo?`
                : '';
        }

        if (modalTipoMessage) {
            modalTipoMessage.textContent = nuevoTipo
                ? `El tipo de tarea "${nuevoTipo}" no existe. ¿Deseas crearlo?`
                : '';
        }

        // Mostrar campos adicionales solo si el cliente es nuevo
        const newClientDetails = document.getElementById('new-client-details');
        newClientDetails.style.display = nuevoCliente ? 'block' : 'none';

        modal.style.display = 'flex'; // Mostrar el modal

        // Confirmación modal
        document.getElementById('confirm-modal-yes').addEventListener('click', function () {
            modal.style.display = 'none';

            // Obtener valores adicionales del cliente si es nuevo
            const clienteNIF = document.getElementById('cliente-nifEdit').value.trim();
            const clienteEmail = document.getElementById('cliente-emailEdit').value.trim();
            const clienteTelefono = document.getElementById('cliente-telefonoEdit').value.trim();

            callback({
                clienteNIF,
                clienteEmail,
                clienteTelefono,
            });
        });

        document.getElementById('confirm-modal-no').addEventListener('click', function () {
            modal.style.display = 'none';
        });
    }



    function checkIfNewItem(inputValue, data, idInput) {
        // Verifica que inputValue esté definido y no esté vacío
        if (inputValue) {
            const item = data.find(entry => entry.nombre && entry.nombre.toUpperCase() === inputValue.toUpperCase());
            if (item) {
                idInput.value = item.id;
                return null;
            } else {
                idInput.value = ''; // Dejar vacío si es nuevo
                return inputValue;
            }
        } else {
            // Si inputValue es undefined o vacío, simplemente devuelve null
            idInput.value = ''; // Asegura que el ID esté vacío
            return null;
        }
    }


    function updateTaskRow(task) {

        const rowToUpdate = document.querySelector(`tr[data-task-id="${task.id}"]`);
        if (rowToUpdate) {
            const asunto = task.asunto ? task.asunto : rowToUpdate.querySelector('td:nth-child(6)').textContent;
            const cliente = task.cliente ? task.cliente : rowToUpdate.querySelector('td:nth-child(5)').textContent;
            const tipo = task.tipo ? task.tipo : rowToUpdate.querySelector('td:nth-child(14)').textContent;

            // Añade una clase según el estado de la tarea
            const estadoClass = task.estado ? `estado-${task.estado.toLowerCase()}` : 'estado-default';
            rowToUpdate.classList.add(estadoClass);


            rowToUpdate.innerHTML = `
                    <td>${task.id}</td>
                    <td>${task.fecha_vencimiento ? new Date(task.fecha_vencimiento).toLocaleDateString() : 'Sin fecha'}</td>
                    <td>
                      ${task.fecha_planificacion ? formatFechaPlanificacion(task.fecha_planificacion) : 'Sin fecha'}
                     </td> 
                    <td>${task.users && task.users.length > 0 ? task.users.join(', ') : 'Sin asignación'}</td>
                    <td>${cliente}</td>
                    <td>${asunto}</td>
                    <td class="col-descripcion">${task.descripcion ? truncateText(task.descripcion, 100) : ''}</td>
                     <td class="col-observaciones">${task.observaciones ? truncateText(task.observaciones, 100) : ''}</td>
                    <td>${task.facturable ? 'Sí' : 'No'}</td>
                    <td>${task.facturado || 'No facturado'}</td>
                    <td>${task.estado}</td>
                    <td>${task.tiempo_previsto || 'N/A'}</td>
                    <td>${task.tiempo_real || 'N/A'}</td>
                    <td>${tipo}</td>
                    <td>${task.subtipo || ''}</td>
                    <td>${task.fecha_inicio ? new Date(task.fecha_inicio).toLocaleDateString() : 'Sin fecha'}</td>  

                `;
        } else {
            console.warn('No se encontró la fila correspondiente a la tarea actualizada');
        }
    }


    function deleteTaskRow(taskId) {
        const rowToDelete = document.querySelector(`tr[data-task-id="${taskId}"]`);
        if (rowToDelete) {
            rowToDelete.remove();
        } else {
            console.warn('No se encontró la fila correspondiente a la tarea eliminada');
        }
    }


    let tooltip = null;
    let tooltipTimeout;

    // Crear tooltip solo una vez y agregar al body
    function createTooltip() {
        if (!tooltip) {
            tooltip = document.createElement('div');
            tooltip.id = 'client-tooltip';
            tooltip.classList.add('client-tooltip');
            tooltip.style.display = 'none';
            document.body.appendChild(tooltip);

            tooltip.addEventListener('mouseenter', () => clearTimeout(tooltipTimeout));
            tooltip.addEventListener('mouseleave', hideTooltip);
        }
    }

    // Función para mostrar el tooltip
    function showTooltip(e, clientData) {
        tooltip.innerHTML = `
            <p><strong>NIF:</strong> ${clientData.nif || 'No disponible'}</p>
            <p><strong>Dirección:</strong> ${clientData.direccion || 'No disponible'}</p>
            <p><strong>Población:</strong> ${clientData.poblacion || 'No disponible'}</p>
        `;
        tooltip.style.top = `${e.pageY + 5}px`;
        tooltip.style.left = `${e.pageX + 5}px`;
        tooltip.style.display = 'block';
        tooltip.classList.add('show');
    }

    // Función para ocultar el tooltip
    // Función para ocultar el tooltip
    function hideTooltip() {
        if (tooltip) {
            // Remover clase `show` para que la transición de ocultar funcione
            tooltip.classList.remove('show');
        }
    }

    // Función para agregar eventos al cliente
    function addTooltipEvents(clientElement) {
        const clientData = {
            nif: clientElement.getAttribute('data-nif'),
            direccion: clientElement.getAttribute('data-direccion'),
            poblacion: clientElement.getAttribute('data-poblacion')
        };

        clientElement.addEventListener('mouseenter', (e) => {
            tooltipTimeout = setTimeout(() => showTooltip(e, clientData), 500);
        });
        clientElement.addEventListener('mouseleave', () => {
            clearTimeout(tooltipTimeout);
            tooltipTimeout = setTimeout(hideTooltip, 300);
        });
    }

    // Observador para añadir el tooltip a los elementos cliente al abrir el modal
    const observer2 = new MutationObserver(() => {
        const clientElement = document.querySelector('.task-client');
        if (clientElement) {
            addTooltipEvents(clientElement);
        }


    });

    // Configurar el observador para observar cambios en el modal
    observer2.observe(modalContent, { childList: true, subtree: true });

    // Crear el tooltip al cargar la página
    createTooltip();

});

// Función para borrar la tarea
function deleteCustomer(taskId) {
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
                closeCustomerModal();

                const rowToDelete = document.querySelector(`tr[data-task-id="${taskId}"]`);
                if (rowToDelete) {
                    rowToDelete.remove();
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

// Función para cerrar el formulario de edición
function closeEditCustomerForm(editTaskFormContainer) {
    if (editTaskFormContainer) {
        editTaskFormContainer.classList.remove('show');
        editTaskFormContainer.classList.add('hide');
        setTimeout(() => {
            editTaskFormContainer.style.display = 'none';
        }, 400);
    }
}

// Función para cerrar el modal de detalles de la tarea
function closeCustomerModal() {
    const modal = document.getElementById('task-detail-modal');
    const editTaskFormContainer = document.getElementById('edit-task-form-container');

    modal.style.display = 'none';

    // También cerrar el formulario de edición si está abierto
    if (editTaskFormContainer.classList.contains('show')) {
        closeEditCustomerForm(editTaskFormContainer);
    }
}




function populateEditForm(task) {
    console.log('Datos recibidos:', task);

    let clientesData = JSON.parse(document.getElementById('clientes-data').getAttribute('data-clientes'));
    let asuntosData = JSON.parse(document.getElementById('asuntos-data').getAttribute('data-asuntos'));
    let tiposData = JSON.parse(document.getElementById('tipos-data').getAttribute('data-tipos'));


    // Cliente
    const clienteInput = document.getElementById('cliente-inputEdit');
    const clienteIdInput = document.getElementById('cliente-id-inputEdit');
    if (clienteInput) clienteInput.value = task.cliente?.nombre_fiscal || '';
    if (clienteIdInput) clienteIdInput.value = task.cliente_id || '';

    // Asunto
    const asuntoInput = document.getElementById('asunto-inputEdit');
    const asuntoIdInput = document.getElementById('asunto-id-inputEdit');
    if (asuntoInput) asuntoInput.value = task.asunto?.nombre || '';
    if (asuntoIdInput) asuntoIdInput.value = task.asunto_id || '';

    // Tipo
    const tipoInput = document.getElementById('tipo-inputEdit');
    const tipoIdInput = document.getElementById('tipo-id-inputEdit');
    if (tipoInput) tipoInput.value = task.tipo?.nombre || '';
    if (tipoIdInput) tipoIdInput.value = task.tipo_id || '';


    // Aquí asegura que los datos y elementos DOM estén listos antes de configurar autocompletado
    if (document.getElementById('cliente-inputEdit')) {
        console.log('Elementos DOM disponibles, inicializando autocompletado');
        setupAutocomplete({
            inputId: 'cliente-inputEdit',
            hiddenId: 'cliente-id-inputEdit',
            listId: 'cliente-listEdit',
            data: clientesData,
            nameKey: 'nombre_fiscal',
            formatItem: item => `${item.nombre_fiscal} (${item.nif || 'N/A'})`,
        });

        setupAutocomplete({
            inputId: 'asunto-inputEdit',
            hiddenId: 'asunto-id-inputEdit',
            listId: 'asunto-listEdit',
            data: asuntosData,
            nameKey: 'nombre',
            formatItem: item => item.nombre,
        });

        setupAutocomplete({
            inputId: 'tipo-inputEdit',
            hiddenId: 'tipo-id-inputEdit',
            listId: 'tipo-listEdit',
            data: tiposData,
            nameKey: 'nombre',
            formatItem: item => item.nombre,
        });

    } else {
        console.warn('Elementos DOM aún no disponibles');
    }


}


clientesData = JSON.parse(document.getElementById('clientes-data').getAttribute('data-clientes'));
asuntosData = JSON.parse(document.getElementById('asuntos-data').getAttribute('data-asuntos'));
tiposData = JSON.parse(document.getElementById('tipos-data').getAttribute('data-tipos'));


function setupAutocomplete({ inputId, hiddenId, listId, data, nameKey, formatItem }) {
    const input = document.getElementById(inputId);
    const hiddenInput = document.getElementById(hiddenId);
    const list = document.getElementById(listId);
    let selectedIndex = -1;


    function filterData(query) {
        return data.filter(item => {
            const itemName = item[nameKey] ? item[nameKey].toLowerCase() : ''; // Usar el `nameKey` dinámicamente
            return itemName.includes(query.toLowerCase());
        });
    }

    function renderList(filtered) {
        list.innerHTML = '';
        if (filtered.length === 0) {
            list.style.display = 'none';
            return;
        }
        list.style.display = 'block';
        filtered.forEach((item, index) => {
            const li = document.createElement('li');
            li.textContent = formatItem ? formatItem(item) : item[nameKey] || 'Elemento sin nombre';
            li.setAttribute('data-id', item.id);
            li.classList.add('autocomplete-item');
            if (index === selectedIndex) {
                li.classList.add('active');
            }
            li.addEventListener('click', () => selectItem(item));
            list.appendChild(li);
        });
    }

    function selectItem(item) {
        if (!item || !item[nameKey]) {
            console.error(`El objeto seleccionado no tiene '${nameKey}'.`, item);
            input.value = 'Elemento no válido';
            hiddenInput.value = '';
            return;
        }

        input.value = item[nameKey]; // Usar la propiedad dinámica para el nombre
        hiddenInput.value = item.id; // Guardar el ID en el campo oculto
        list.style.display = 'none';
        selectedIndex = -1;
    }

    function handleKeydown(e) {
        const items = list.querySelectorAll('.autocomplete-item');
        if (items.length > 0) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateActiveItem(items, selectedIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, 0);
                updateActiveItem(items, selectedIndex);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedIndex >= 0 && selectedIndex < items.length) {
                    const selectedItem = data.find(item =>
                        (formatItem ? formatItem(item) : item[nameKey]) === items[selectedIndex].textContent
                    );
                    selectItem(selectedItem);
                }
            }
        }
    }

    function updateActiveItem(items, index) {
        items.forEach(item => item.classList.remove('active')); // Iteración correcta
        if (items[index]) {
            items[index].classList.add('active'); // Usar `items[index]` en lugar de `item`
            items[index].scrollIntoView({ block: 'nearest' }); // Asegurar que el elemento sea visible
        }
    }


    input.addEventListener('input', function () {
        const query = this.value.toUpperCase();
        const filtered = filterData(query);
        selectedIndex = -1;
        hiddenInput.value = '';
        renderList(filtered);
    });

    input.addEventListener('focus', function () {
        const query = this.value.toUpperCase();
        const filtered = filterData(query);
        renderList(filtered);
    });

    input.addEventListener('keydown', handleKeydown);

    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !list.contains(e.target)) {
            list.style.display = 'none';
        }
    });


}



