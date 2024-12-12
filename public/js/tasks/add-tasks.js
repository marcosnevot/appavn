// Añadir el event listener para manejar el envío del formulario
document.addEventListener('DOMContentLoaded', function () {
    // console.log('El script de add-tasks ha sido cargado correctamente.');

    const taskForm = document.getElementById('task-form');
    const addTaskForm = document.getElementById('add-task-form'); // El propio formulario

    clientesData = JSON.parse(document.getElementById('clientes-data').getAttribute('data-clientes'));

    asuntosData = JSON.parse(document.getElementById('asuntos-data').getAttribute('data-asuntos'));

    tiposData = JSON.parse(document.getElementById('tipos-data').getAttribute('data-tipos'));

    let usersData = JSON.parse(document.getElementById('usuarios-data').getAttribute('data-usuarios'));

    // Obtener el ID del usuario en sesión
    const sessionUserId = document.querySelector('meta[name="user-id"]').getAttribute('content');

    const modal = document.getElementById('confirm-modal'); // Modal de confirmación
    const modalMessage = document.getElementById('modal-message');
    const modalAsuntoMessage = document.getElementById('modal-asunto-message');
    const modalTipoMessage = document.getElementById('modal-tipo-message');
    const modalClienteMessage = document.getElementById('modal-cliente-message');

    let nuevoCliente = null;
    let nuevoAsunto = null;
    let nuevoTipo = null;

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
    function submitTaskForm(additionalClientData = {}) {
        // Obtener los usuarios seleccionados
        const selectedUsers = getSelectedUsers();

        const clienteInputValue = clienteInput.value.trim().toUpperCase();
        nuevoCliente = checkIfNewItem(clienteInputValue, clientesData, clienteIdInput);

        const asuntoInputValue = asuntoInput.value.trim().toUpperCase();
        nuevoAsunto = checkIfNewItem(asuntoInputValue, asuntosData, asuntoIdInput);

        const tipoInputValue = tipoInput.value.trim().toUpperCase();
        nuevoTipo = checkIfNewItem(tipoInputValue, tiposData, tipoIdInput);

        const formData = {
            cliente_id: clienteIdInput.value, // Si el asunto es nuevo, esto estará vacío
            cliente_nombre: nuevoCliente || '',
            cliente_nif: nuevoCliente ? additionalClientData.clienteNIF : '', // NIF solo si el cliente es nuevo
            cliente_email: nuevoCliente ? additionalClientData.clienteEmail : '', // Email solo si es nuevo
            cliente_telefono: nuevoCliente ? additionalClientData.clienteTelefono : '', // Teléfono solo si es nuevo    
            asunto_id: asuntoIdInput.value, // Si el asunto es nuevo, esto estará vacío
            asunto_nombre: nuevoAsunto || '',
            tipo_id: tipoIdInput.value, // Si el tipo es nuevo, esto estará vacío
            tipo_nombre: nuevoTipo || '', // Enviar nuevo tipo si no existe
            subtipo: document.querySelector('select[name="subtipo"]').value,
            estado: document.querySelector('select[name="estado"]').value,
            users: selectedUsers, // Lista de IDs de los usuarios seleccionados
            archivo: document.querySelector('input[name="archivo"]').value,
            descripcion: document.querySelector('textarea[name="descripcion"]').value,
            observaciones: document.querySelector('textarea[name="observaciones"]').value,
            facturable: document.querySelector('input[name="facturable"]').checked ? 1 : 0,
            facturado: document.querySelector('select[name="facturado"]').value,
            precio: document.querySelector('input[name="precio"]').value,
            suplido: document.querySelector('input[name="suplido"]').value,
            coste: document.querySelector('input[name="coste"]').value,
            fecha_inicio: document.querySelector('input[name="fecha_inicio"]').value,
            fecha_vencimiento: document.querySelector('input[name="fecha_vencimiento"]').value,
            fecha_imputacion: document.querySelector('input[name="fecha_imputacion"]').value,
            tiempo_previsto: document.querySelector('input[name="tiempo_previsto"]').value,
            tiempo_real: document.querySelector('input[name="tiempo_real"]').value,
            planificacion: document.querySelector('input[name="planificacion"]').value
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
                    if (data.task.asunto && !asuntosData.some(a => a.id === data.task.asunto.id)) {
                        asuntosData.push(data.task.asunto); // Añadir el nuevo asunto a la lista de asuntos
                    }

                    // Si hay un nuevo cliente en la respuesta, lo añadimos a la lista de clientes
                    if (data.task.cliente && !clientesData.some(c => c.id === data.task.cliente.id)) {
                        clientesData.push(data.task.cliente); // Añadir el nuevo asunto a la lista de clientes
                    }

                    // Si hay un nuevo tipo en la respuesta, lo añadimos a la lista de tipos
                    if (data.task.tipo && !tiposData.some(t => t.id === data.task.tipo.id)) {
                        tiposData.push(data.task.tipo); // Añadir el nuevo tipo a la lista de tipos
                    }

                    showNotification("Tarea creada exitosamente", "success");
                    // Resetear determinados campos

                    document.getElementById('add-task-form').reset(); // Resetear el formulario
                    generarBotonesPlanificacion();
                    preselectSessionUser();
                } else {
                    console.error('Errores de validación:', data.errors);
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error.message);
            });
    }

    function getSelectedUsers() {
        return Array.from(document.querySelectorAll('#user-list input[type="checkbox"]:checked'))
            .map(checkbox => checkbox.value); // Obtener los IDs de los usuarios seleccionados
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


    // Función para limpiar los usuarios seleccionados y preseleccionar el usuario en sesión
    function resetSelectedUsers() {
        const selectedUsersContainer = document.getElementById('selected-users');
        const userIdsInput = document.getElementById('user-ids');

        // Obtener el ID del usuario actual (debe estar en el HTML como un meta o input oculto)
        const currentUserId = document.querySelector('meta[name="user-id"]').getAttribute('content');

        // Limpiar el contenedor visual de los usuarios seleccionados
        selectedUsersContainer.innerHTML = '';

        // Limpiar el campo oculto de los IDs de los usuarios seleccionados
        userIdsInput.value = '';

        // Desmarcar todos los checkboxes
        const checkboxes = document.querySelectorAll('#user-list input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false; // Desmarcar todos los checkboxes
        });

        // Seleccionar el checkbox del usuario actual
        const currentUserCheckbox = document.querySelector(`#user-list input[type="checkbox"][value="${currentUserId}"]`);
        if (currentUserCheckbox) {
            currentUserCheckbox.checked = true; // Marcar el checkbox del usuario actual

            // Obtener el nombre del usuario actual para mostrarlo en el contenedor visual
            const userName = currentUserCheckbox.nextElementSibling.textContent;

            // Crear un elemento visual para el usuario seleccionado
            const userItem = document.createElement('span');
            userItem.className = 'selected-user';
            userItem.textContent = userName;

            // Añadir el usuario actual al contenedor visual
            selectedUsersContainer.appendChild(userItem);

            // Actualizar el campo oculto con el ID del usuario actual
            userIdsInput.value = currentUserId;
        } else {
            console.error('El usuario actual no está en la lista de usuarios.');
        }
    }



    // Manejar el evento de envío del formulario
    addTaskForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevenir el comportamiento predeterminado de recargar la página

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
            showModalConfirm();
        } else {
            // Confirmar antes de enviar
            if (confirm("¿Estás seguro de que deseas enviar el formulario?")) {
                submitTaskForm(); // Si se confirma, enviar el formulario
            }
        }
    });

    // Escuchar el canal y el evento del WebSocket
    window.Echo.channel('tasks')
        .listen('TaskCreated', (e) => {
            console.log('Nueva tarea creada:', e);

            // Obtener el ID del usuario autenticado
            const currentUserId = parseInt(sessionUserId); // Asegúrate de definir `sessionUserId`

            // Verificar si el usuario autenticado está en los asignados
            if (!e.assignedUserIds.includes(currentUserId)) {
                console.log('Tarea no asignada al usuario actual, ignorando...');
                return;
            }

            console.log('Tarea asignada al usuario actual.');

            // Actualizar la tabla con los filtros actuales
            let currentFilters = null;
            if (document.getElementById('filter-cliente-id-input')) {
                currentFilters = getCurrentFilters();
            }

            updateTaskTable(e.task, true, currentFilters);
        });





    /**
   * Configura el autocompletado para un campo.
   * @param {Object} options - Configuración del campo.
   * @param {string} options.inputId - ID del input visible.
   * @param {string} options.hiddenId - ID del input oculto.
   * @param {string} options.listId - ID de la lista de sugerencias.
   * @param {Array} options.data - Datos para autocompletar (array de objetos con `nombre` e `id`).
   * @param {Function} [options.formatItem] - Función opcional para formatear los elementos de la lista.
   */

    const clienteInput = document.getElementById('cliente-input');
    const clienteIdInput = document.getElementById('cliente-id-input');
    const asuntoInput = document.getElementById('asunto-input');
    const asuntoIdInput = document.getElementById('asunto-id-input');
    const tipoInput = document.getElementById('tipo-input');
    const tipoIdInput = document.getElementById('tipo-id-input');




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


    // Configurar para clientes
    setupAutocomplete({
        inputId: 'cliente-input',
        hiddenId: 'cliente-id-input',
        listId: 'cliente-list',
        data: clientesData,
        nameKey: 'nombre_fiscal', // Para clientes
        formatItem: item => `${item.nombre_fiscal} (${item.nif || 'N/A'})`
    });

    // Configurar para asuntos
    setupAutocomplete({
        inputId: 'asunto-input',
        hiddenId: 'asunto-id-input',
        listId: 'asunto-list',
        data: asuntosData,
        nameKey: 'nombre', // Para asuntos
        formatItem: item => item.nombre
    });

    // Configurar para tipos
    setupAutocomplete({
        inputId: 'tipo-input',
        hiddenId: 'tipo-id-input',
        listId: 'tipo-list',
        data: tiposData,
        nameKey: 'nombre', // Para tipos
        formatItem: item => item.nombre
    });




    // Mostrar el modal de confirmación
    function showModalConfirm() {
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

        // Eliminar controladores previos para evitar duplicaciones
        const confirmYesButton = document.getElementById('confirm-modal-yes');
        const confirmNoButton = document.getElementById('confirm-modal-no');

        confirmYesButton.replaceWith(confirmYesButton.cloneNode(true)); // Clonar el botón para eliminar eventos
        confirmNoButton.replaceWith(confirmNoButton.cloneNode(true)); // Clonar el botón para eliminar eventos

        document.getElementById('confirm-modal-yes').addEventListener('click', function () {
            modal.style.display = 'none';

            // Obtener los valores de los campos adicionales
            const clienteNIF = document.getElementById('cliente-nif').value.trim();
            const clienteEmail = document.getElementById('cliente-email').value.trim();
            const clienteTelefono = document.getElementById('cliente-telefono').value.trim();

            // Limpiar los campos del modal
            resetModalFields();

            // Pasar estos valores al envío del formulario
            submitTaskForm({ clienteNIF, clienteEmail, clienteTelefono });
        });

        document.getElementById('confirm-modal-no').addEventListener('click', function () {
            modal.style.display = 'none';
            // Limpiar los campos del modal
            resetModalFields();
        });
    }


    function resetModalFields() {
        document.getElementById('cliente-nif').value = '';
        document.getElementById('cliente-email').value = '';
        document.getElementById('cliente-telefono').value = '';
    }



    // Asignar Usuarios a una tarea
    const userSelect = document.getElementById('user-select');
    const userList = document.getElementById('user-list');
    const selectedUsersContainer = document.getElementById('selected-users');
    const userIdsInput = document.getElementById('user-ids');


    let selectedUsers = [];
    let currentFocus = -1;

    // Preseleccionar el usuario de sesión si está en la lista
    function preselectSessionUser() {
        // Limpiar el array de usuarios seleccionados
        selectedUsers = [];

        // Desmarcar todos los checkboxes
        userList.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });

        // Reiniciar el campo oculto de IDs de usuarios seleccionados
        userIdsInput.value = '';

        // Limpiar la visualización de usuarios seleccionados
        updateSelectedUsersDisplay();

        // Ahora seleccionar el usuario de sesión
        const sessionUserCheckbox = document.querySelector(`#user-${sessionUserId}`);
        if (sessionUserCheckbox) {
            sessionUserCheckbox.checked = true;

            // Añadir el usuario de sesión al array
            const userName = sessionUserCheckbox.nextElementSibling.textContent;
            selectedUsers.push({ id: sessionUserId, name: userName });

            // Actualizar la visualización y el campo oculto
            updateSelectedUsersDisplay();
            updateUserIdsInput();
        }
    }


    preselectSessionUser();

    // Mostrar/ocultar la lista de usuarios al hacer clic o presionar Enter/Espacio
    userSelect.addEventListener('click', toggleUserList);
    userSelect.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleUserList();
        } else if (e.key === 'Escape') {
            userList.style.display = 'none';
        }
    });

    // Función para alternar la visibilidad de la lista desplegable
    function toggleUserList() {
        if (userList.style.display === 'block') {
            userList.style.display = 'none';
        } else {
            userList.style.display = 'block';
            currentFocus = -1; // Reiniciar la selección cuando se vuelve a abrir
            focusNextCheckbox(1); // Foco en el primer checkbox cuando se abre la lista
        }
    }

    // Manejar la selección de usuarios
    userList.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const userId = this.value;
            const userName = this.nextElementSibling.textContent;

            if (this.checked) {
                selectedUsers.push({ id: userId, name: userName });
            } else {
                selectedUsers = selectedUsers.filter(user => user.id !== userId);
            }

            updateSelectedUsersDisplay();
            updateUserIdsInput();
            userList.style.display = 'none'; // Cerrar la lista después de seleccionar un usuario
            userSelect.focus(); // Devolver el foco al select principal
        });
    });

    // Manejar la selección de usuarios
    userList.querySelectorAll('li').forEach(li => {
        li.addEventListener('click', function (e) {
            // Evitar procesamiento duplicado si el clic fue en el checkbox
            if (e.target.tagName === 'INPUT') return;

            // Buscar el checkbox dentro del <li>
            const checkbox = li.querySelector('input[type="checkbox"]');
            if (!checkbox) return;

            // Si el clic fue en el label, no alternar el estado manualmente
            if (e.target.tagName === 'LABEL') {
                return; // El label ya alternará el estado del checkbox automáticamente
            }

            // Alternar el estado del checkbox manualmente si el clic fue en el <li> (fuera del label)
            checkbox.checked = !checkbox.checked;

            // Obtener datos del usuario
            const userId = checkbox.value;
            const userName = checkbox.nextElementSibling.textContent;

            if (checkbox.checked) {
                // Agregar usuario a la lista de seleccionados
                selectedUsers.push({ id: userId, name: userName });
            } else {
                // Eliminar usuario de la lista de seleccionados
                selectedUsers = selectedUsers.filter(user => user.id !== userId);
            }

            // Actualizar la visualización y los datos ocultos
            updateSelectedUsersDisplay();
            updateUserIdsInput();
        });
    });



    // Función para actualizar la visualización de los usuarios seleccionados
    function updateSelectedUsersDisplay() {
        selectedUsersContainer.innerHTML = '';
        selectedUsers.forEach(user => {
            const span = document.createElement('span');
            span.textContent = user.name;
            selectedUsersContainer.appendChild(span);
        });
    }

    // Función para actualizar el campo oculto con los IDs de usuarios seleccionados
    function updateUserIdsInput() {
        userIdsInput.value = selectedUsers.map(user => user.id).join(',');
    }

    // Cerrar la lista al perder el foco o al presionar Escape
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.custom-select') && e.target !== userSelect) {
            userList.style.display = 'none';
        }
    });

    // Función para navegar dentro de la lista con el teclado
    userList.addEventListener('keydown', function (e) {
        const checkboxes = userList.querySelectorAll('input[type="checkbox"]');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            focusNextCheckbox(1);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            focusNextCheckbox(-1);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentFocus >= 0 && currentFocus < checkboxes.length) {
                checkboxes[currentFocus].click(); // Simular un click para seleccionar el usuario
            }
        } else if (e.key === 'Escape') {
            userList.style.display = 'none';
            userSelect.focus(); // Volver el foco al select principal
        }
    });

    // Función para manejar el enfoque de los checkboxes
    function focusNextCheckbox(direction) {
        const checkboxes = userList.querySelectorAll('input[type="checkbox"]');
        currentFocus = (currentFocus + direction + checkboxes.length) % checkboxes.length; // Calcular el índice
        checkboxes[currentFocus].focus();
    }




    // Definir referencias globales a los elementos de la planificación
    const planificacionContainer = document.getElementById('planificacion-buttons');
    const planificacionInput = document.getElementById('planificacion');

    function obtenerDiasSemana() {
        const diasSemana = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
        const hoy = new Date();
        const hoyIndex = hoy.getDay();
        const diasRestantes = [];

        for (let i = 0; i < 7 - hoyIndex; i++) {
            const nuevoDia = new Date(hoy);
            nuevoDia.setDate(hoy.getDate() + i);
            const diaSemana = nuevoDia.getDay();

            // Excluir sábado y domingo
            if (diaSemana === 0 || diaSemana === 6) continue;

            const nombreDia = i === 0 ? "Hoy" : i === 1 ? "Mañana" : diasSemana[diaSemana - 1];
            diasRestantes.push({
                nombre: nombreDia,
                fecha: nuevoDia.toISOString().split('T')[0]
            });
        }

        return diasRestantes;
    }

    // Configuración inicial al cargar la página
    function generarBotonesPlanificacion() {
        planificacionContainer.innerHTML = ""; // Limpiar botones previos
        const diasRestantes = obtenerDiasSemana();

        diasRestantes.forEach(dia => {
            const button = document.createElement('button');
            button.type = 'button';
            button.classList.add('btn-planificacion');
            button.textContent = dia.nombre;
            button.setAttribute('data-fecha', dia.fecha);
            button.onclick = () => setPlanificacion(dia.fecha);
            planificacionContainer.appendChild(button);

            // Seleccionar "Hoy" por defecto
            if (dia.nombre === "Hoy") {
                setPlanificacion(dia.fecha);
                button.classList.add('active');
            }
        });
    }

    function setPlanificacion(fecha, isManual = false) {
        planificacionInput.value = fecha; // Actualizar el input oculto con la fecha seleccionada

        // Remover todas las clases `active` de los botones rápidos
        document.querySelectorAll('.btn-planificacion').forEach(btn => btn.classList.remove('active'));

        // Verificar si la fecha coincide con alguna de las opciones de botones rápidos
        const selectedButton = Array.from(document.querySelectorAll('.btn-planificacion')).find(btn => btn.getAttribute('data-fecha') === fecha);
        if (selectedButton) {
            selectedButton.classList.add('active');
        } else if (isManual) {
            // Si es una fecha manual que no coincide con los botones rápidos, desmarcar todos
            document.querySelectorAll('.btn-planificacion').forEach(btn => btn.classList.remove('active'));
        }
    }

    // Escuchar cambios en el input `date` existente para desmarcar botones si la fecha es manual
    planificacionInput.addEventListener('change', () => setPlanificacion(planificacionInput.value, true));

    // Generar los botones de planificación al cargar la página
    generarBotonesPlanificacion();





});

// Función para manejar la paginación de Laravel
function setupPaginationListeners() {
    const paginationLinks = document.querySelectorAll('.pagination-container a'); // Obtener los enlaces de paginación
    paginationLinks.forEach(link => {
        link.addEventListener('click', handlePaginationClick);
    });
}

// Función para actualizar la tabla con la nueva tarea
function updateTaskTable(tasks, isSingleTask = false, currentFilters = null, pagination = null) {

    const tableBody = document.querySelector('table tbody');
    console.log('Evento recibido:');

    // Si no es una tarea única (por ejemplo, en filtrado), limpiamos la tabla
    if (!isSingleTask) {
        tableBody.innerHTML = ''; // Limpiar la tabla existente
    }
    // Usar los filtros globales si no se pasan como argumento
    const filtersToApply = currentFilters || window.currentFilters;
    console.log('Filtros actuales a aplicar:', filtersToApply);
    // Convertir el parámetro `tasks` a un array si es un solo objeto
    const tasksArray = isSingleTask ? [tasks] : tasks;

    tasksArray.forEach(task => {
        // Verificar si la tarea coincide con los filtros actuales (si es que hay filtros)
        if (currentFilters && !taskMatchesFilters(task, currentFilters)) {
            // Si no coincide con los filtros actuales, no la mostramos
            return;
        }

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
            <td>${task.facturado || 'No'}</td>
            <td>${task.estado}</td>
            <td>${task.tiempo_previsto || 'N/A'}</td>
            <td>${task.tiempo_real || 'N/A'}</td>
            <td>${task.tipo ? task.tipo.nombre : 'Sin tipo'}</td>
            <td>${task.subtipo || ''}</td>
            <td>${task.fecha_inicio ? new Date(task.fecha_inicio).toLocaleDateString() : 'Sin fecha'}</td>
        `;

        // Insertar la nueva fila al principio si es una tarea única (añadir tarea)
        if (isSingleTask && tableBody.firstChild) {
            tableBody.insertBefore(row, tableBody.firstChild);
        } else {
            tableBody.appendChild(row);
        }


        // Añadir el evento de doble clic a las filas de la tabla
        addDoubleClickEventToRows();
    });


}



// Función para actualizar una fila específica en la tabla al editar una tarea
function updateSingleTaskRow(task) {
    // Buscar la fila existente con el ID de la tarea
    const existingRow = document.querySelector(`tr[data-task-id="${task.id}"]`);

    if (existingRow) {
        // Si la fila ya existe, actualizar su contenido
        existingRow.innerHTML = `
            <td>${task.id}</td>
            <td>${task.asunto ? task.asunto.nombre : 'Sin asunto'}</td>
            <td>${task.cliente ? task.cliente.nombre_fiscal : 'Sin cliente'}</td>
            <td>${task.tipo ? task.tipo.nombre : 'Sin tipo'}</td>
            <td class="col-descripcion">${task.descripcion ? truncateText(task.descripcion, 100) : ''}</td>
            <td class="col-observaciones">${task.observaciones ? truncateText(task.observaciones, 100) : ''}</td>
            <td>${task.facturable ? 'Sí' : 'No'}</td>
            <td>${task.facturado || 'No'}</td>
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
        `;
    } else {
        console.error(`No se encontró una fila con el ID de la tarea: ${task.id}`);
    }

    // Añadir el evento de doble clic a la fila actualizada (si es necesario)
    addDoubleClickEventToRows();
}




// Función para verificar si una tarea coincide con los filtros actuales
function taskMatchesFilters(task, filters) {
    // Verificar cada filtro
    if (filters.cliente && (!task.cliente || !task.cliente.nombre_fiscal.includes(filters.cliente))) {
        return false;
    }

    if (filters.asunto && (!task.asunto || !task.asunto.nombre.includes(filters.asunto))) {
        return false;
    }

    if (filters.tipo && (!task.tipo || !task.tipo.nombre.includes(filters.tipo))) {
        return false;
    }

    if (filters.subtipo && task.subtipo !== filters.subtipo) {
        return false;
    }

    if (filters.estado && task.estado !== filters.estado) {
        return false;
    }

    if (filters.usuario && (!task.users || !task.users.some(user => user.name.includes(filters.usuario)))) {
        return false;
    }

    if (filters.facturable !== '' && task.facturable !== Boolean(Number(filters.facturable))) {
        return false;
    }

    if (filters.facturado && task.facturado !== filters.facturado) {
        return false;
    }

    if (filters.archivo && (!task.archivo || !task.archivo.includes(filters.archivo))) {
        return false;
    }

    if (filters.precio && parseFloat(task.precio) !== parseFloat(filters.precio)) {
        return false;
    }

    if (filters.suplido && parseFloat(task.suplido) !== parseFloat(filters.suplido)) {
        return false;
    }

    if (filters.coste && parseFloat(task.coste) !== parseFloat(filters.coste)) {
        return false;
    }

    // Verificación de fechas (inicio, vencimiento, imputación)
    if (filters.fecha_inicio && task.fecha_inicio !== filters.fecha_inicio) {
        return false;
    }

    if (filters.fecha_vencimiento && task.fecha_vencimiento !== filters.fecha_vencimiento) {
        return false;
    }

    if (filters.fecha_imputacion && task.fecha_imputacion !== filters.fecha_imputacion) {
        return false;
    }

    if (filters.tiempo_previsto && parseFloat(task.tiempo_previsto) !== parseFloat(filters.tiempo_previsto)) {
        return false;
    }

    if (filters.tiempo_real && parseFloat(task.tiempo_real) !== parseFloat(filters.tiempo_real)) {
        return false;
    }

    return true;
}


function getCurrentFilters() {

    // Helper para obtener valores seleccionados de un checklist
    function getChecklistValues(fieldName, isBoolean = false) {
        const checkboxes = document.querySelectorAll(`#filter-${fieldName}-list input[type="checkbox"]:checked`);
        return Array.from(checkboxes).map(checkbox => isBoolean ? checkbox.value === "1" : checkbox.value);
    }
    return {
        cliente: document.getElementById('filter-cliente-input')?.value || '',
        asunto: document.getElementById('filter-asunto-input')?.value || '',
        tipo: document.getElementById('filter-tipo-input')?.value || '',
        subtipo: document.getElementById('filter-subtipo')?.value || '',
        estado: document.getElementById('filter-estado') ? document.getElementById('filter-estado').value : '',
        usuario: document.getElementById('filter-user-input')?.value || '',
        archivo: document.getElementById('filter-archivo')?.value || '',
        facturable: getChecklistValues('facturable', true).join(','), // Convierte a booleano
        facturado: getChecklistValues('facturado').join(','), // Recoge valores seleccionados de "facturado"
        precio: document.getElementById('filter-precio')?.value || '',
        suplido: document.getElementById('filter-suplido')?.value || '',
        coste: document.getElementById('filter-coste')?.value || '',
        fecha_inicio: document.getElementById('filter-fecha-inicio')?.value || '',
        fecha_vencimiento: document.getElementById('filter-fecha-vencimiento')?.value || '',
        fecha_imputacion: document.getElementById('filter-fecha-imputacion')?.value || '',
        tiempo_previsto: document.getElementById('filter-tiempo-previsto')?.value || '',
        tiempo_real: document.getElementById('filter-tiempo-real')?.value || ''
    };
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





// Función para mostrar la notificación de éxito
function showSuccessNotification(message = "Tarea creada exitosamente") {
    const notification = document.getElementById('success-notification');
    const notificationMessage = document.querySelector('.notification-message');
    const notificationTimer = document.querySelector('.notification-timer');

    notificationMessage.textContent = message;
    notificationTimer.style.width = '100%';
    notification.style.zIndex = '7000';

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