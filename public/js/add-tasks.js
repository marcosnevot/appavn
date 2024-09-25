// Añadir el event listener para manejar el envío del formulario
document.addEventListener('DOMContentLoaded', function () {
    console.log('El script de add-tasks ha sido cargado correctamente.');

    const taskForm = document.getElementById('task-form');
    const addTaskForm = document.getElementById('add-task-form'); // El propio formulario


    let asuntosData = JSON.parse(document.getElementById('asuntos-data').getAttribute('data-asuntos'));

    let tiposData = JSON.parse(document.getElementById('tipos-data').getAttribute('data-tipos'));

    let usersData = JSON.parse(document.getElementById('usuarios-data').getAttribute('data-usuarios'));


    const modal = document.getElementById('confirm-modal'); // Modal de confirmación
    const modalMessage = document.getElementById('modal-message');
    const modalAsuntoMessage = document.getElementById('modal-asunto-message');
    const modalTipoMessage = document.getElementById('modal-tipo-message');
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
    function submitTaskForm() {
        // Obtener los usuarios seleccionados
        const selectedUsers = Array.from(document.querySelectorAll('#user-list input[type="checkbox"]:checked'))
            .map(checkbox => checkbox.value); // Obtener los IDs de los usuarios seleccionados

        // Verificar si el asunto ingresado manualmente ya existe
        const asuntoInputValue = asuntoInput.value.trim().toUpperCase();
        let asuntoExistente = asuntosData.find(asunto => asunto.nombre.toUpperCase() === asuntoInputValue);

        // Si el asunto no existe, marcarlo como nuevo
        if (!asuntoExistente) {
            nuevoAsunto = asuntoInputValue;
            asuntoIdInput.value = ''; // Si es nuevo, dejar vacío el ID
        } else {
            // Si el asunto ya existe, asegurarse de que el ID esté asignado
            asuntoIdInput.value = asuntoExistente.id;
            nuevoAsunto = null; // No es necesario crear un nuevo asunto
        }

        // Verificar si el tipo ingresado manualmente ya existe
        const tipoInputValue = tipoInput.value.trim().toUpperCase();
        let tipoExistente = tiposData.find(tipo => tipo.nombre.toUpperCase() === tipoInputValue);

        // Si el tipo no existe, marcarlo como nuevo
        if (!tipoExistente) {
            nuevoTipo = tipoInputValue;
            tipoIdInput.value = ''; // Si es nuevo, dejar vacío el ID
        } else {
            // Si el tipo ya existe, asegurarse de que el ID esté asignado
            tipoIdInput.value = tipoExistente.id;
            nuevoTipo = null; // No es necesario crear un nuevo tipo
        }

        const formData = {
            cliente_id: document.querySelector('input[name="cliente_id"]').value,
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
                    if (data.task.asunto && !asuntosData.some(a => a.id === data.task.asunto.id)) {
                        asuntosData.push(data.task.asunto); // Añadir el nuevo asunto a la lista de asuntos
                    }

                    // Si hay un nuevo tipo en la respuesta, lo añadimos a la lista de tipos
                    if (data.task.tipo && !tiposData.some(t => t.id === data.task.tipo.id)) {
                        tiposData.push(data.task.tipo); // Añadir el nuevo tipo a la lista de tipos
                    }

                    showSuccessNotification();
                    // Limpiar los usuarios seleccionados
                    resetSelectedUsers();
                    document.getElementById('add-task-form').reset(); // Resetear el formulario
                } else {
                    console.error('Errores de validación:', data.errors);
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error.message);
            });
    }

    // Función para limpiar los usuarios seleccionados
    function resetSelectedUsers() {
        const selectedUsersContainer = document.getElementById('selected-users');
        const userIdsInput = document.getElementById('user-ids');

        // Limpiar el contenedor visual de los usuarios seleccionados
        selectedUsersContainer.innerHTML = '';

        // Limpiar el campo oculto de los IDs de los usuarios seleccionados
        userIdsInput.value = '';

        // Desmarcar todos los checkboxes
        const checkboxes = document.querySelectorAll('#user-list input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }


    // Manejar el evento de envío del formulario
    addTaskForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevenir el comportamiento predeterminado de recargar la página

        nuevoAsunto = null; // Limpiar variables de nuevos valores
        nuevoTipo = null;

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

        // Si hay un nuevo asunto o un nuevo tipo, mostrar el modal
        if (nuevoAsunto || nuevoTipo) {
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

            // Verificar si los filtros existen y están activos
            let currentFilters = null;
            if (document.getElementById('filter-cliente-id-input')) {
                currentFilters = getCurrentFilters(); // Obtener los filtros actuales solo si están disponibles
            }

            // Actualizar la tabla con la nueva tarea y los filtros actuales
            updateTaskTable(e.task, true, currentFilters);
        });

    // Buscador de clientes

    // Obtener los datos de clientes desde el atributo data-clientes
    const clientesData = document.getElementById('clientes-data');
    const clientes = JSON.parse(clientesData.getAttribute('data-clientes'));

    const clienteInput = document.getElementById('cliente-input');
    const clienteIdInput = document.getElementById('cliente-id-input'); // Campo oculto para el ID del cliente
    const clienteList = document.getElementById('cliente-list');
    let selectedClienteIndex = -1;

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
            if (index === selectedClienteIndex) {
                li.classList.add('active');
            }
            li.addEventListener('click', () => selectCliente(cliente));
            clienteList.appendChild(li);
        });
    }

    // Función para seleccionar un cliente y autocompletar el input
    function selectCliente(cliente) {
        clienteInput.value = `${cliente.nombre_fiscal} (${cliente.nif})`; // Mostrar tanto el nombre como el NIF
        clienteIdInput.value = cliente.id; // Almacena el id en el campo oculto
        clienteList.style.display = 'none';
        selectedClienteIndex = -1; // Reiniciar el índice seleccionado
    }

    // Mostrar la lista de clientes al ganar el foco, ya sea con clic o tabulador
    clienteInput.addEventListener('focus', function () {
        selectedClienteIndex = -1;
        filterClientes(clienteInput.value);
        asuntoList.style.display = 'none';
        tipoList.style.display = 'none';
    });

    // Manejador del evento input para filtrar clientes
    clienteInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();  // Convertir el texto a mayúsculas
        filterClientes(this.value);
        selectedClienteIndex = -1; // Reiniciar el índice seleccionado
        clienteIdInput.value = '';  // Limpiar el campo oculto
    });

    // Función para manejar la navegación por teclado
    clienteInput.addEventListener('keydown', function (e) {
        const items = document.querySelectorAll('#cliente-list .autocomplete-item'); // Específicamente para la lista de clientes
        if (items.length > 0) {
            if (e.key === 'ArrowDown') {
                e.preventDefault(); // Prevenir el scroll default
                selectedClienteIndex = Math.min(selectedClienteIndex + 1, items.length - 1); // Asegura que no exceda el último elemento
                updateActiveItem(items, selectedClienteIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault(); // Prevenir el scroll default
                selectedClienteIndex = Math.max(selectedClienteIndex - 1, 0); // Asegura que no baje de 0
                updateActiveItem(items, selectedClienteIndex);
            } else if (e.key === 'Enter') {
                e.preventDefault(); // Prevenir el comportamiento por defecto de 'Enter'
                if (selectedClienteIndex >= 0 && selectedClienteIndex < items.length) {
                    const selectedCliente = clientes.find(cliente =>
                        `${cliente.nombre_fiscal} (${cliente.nif})` === items[selectedClienteIndex].textContent
                    );
                    selectCliente(selectedCliente); // Seleccionar el cliente
                }
            }
        }
    });

    // Función para actualizar el ítem activo en la lista de clientes
    function updateActiveItem(items, index) {
        items.forEach(item => item.classList.remove('active'));
        if (items[index]) {
            items[index].classList.add('active');
            items[index].scrollIntoView({ block: "nearest" }); // Asegurar que esté visible
        }
    }



    // Asunto - Autocompletado y creación de nuevos asuntos

    // Obtener los datos de asuntos desde el atributo data-asuntos
    const asuntoInput = document.getElementById('asunto-input');
    const asuntoIdInput = document.getElementById('asunto-id-input'); // Campo oculto para el ID del asunto
    const asuntoList = document.getElementById('asunto-list');
    let selectedAsuntoIndex = -1; // Inicializar correctamente

    // Función para mostrar la lista filtrada de asuntos
    function filterAsuntos(query) {
        const filtered = asuntosData.filter(asunto =>
            asunto.nombre.toLowerCase().includes(query.toLowerCase())
        );
        renderAsuntoList(filtered);
        return filtered;  // Devolver los asuntos filtrados para que puedan ser utilizados
    }

    // Función para renderizar la lista de asuntos
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
            if (index === selectedAsuntoIndex) {
                li.classList.add('active');
            }
            li.addEventListener('click', () => selectAsunto(asunto));  // Permite seleccionar el asunto
            asuntoList.appendChild(li);  // Añadir el asunto a la lista
        });
    }

    // Función para seleccionar un asunto y autocompletar el input
    function selectAsunto(asunto) {
        asuntoInput.value = asunto.nombre;
        asuntoIdInput.value = asunto.id;
        asuntoList.style.display = 'none';  // Ocultar la lista después de la selección
        selectedAsuntoIndex = -1; // Reiniciar el índice seleccionado
    }


    // Mostrar la lista de asuntos al ganar el foco, ya sea con clic o tabulador
    asuntoInput.addEventListener('focus', function () {
        selectedAsuntoIndex = -1;
        filterAsuntos(asuntoInput.value);
        clienteList.style.display = 'none';
        tipoList.style.display = 'none';
    });

    // Filtrar asuntos en tiempo real mientras se escribe
    asuntoInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();  // Convertir el texto a mayúsculas
        const filteredAsuntos = filterAsuntos(this.value);  // Obtener los asuntos filtrados
        selectedAsuntoIndex = -1; // Reiniciar el índice seleccionado

        // Si no hay coincidencias, se permite crear un nuevo asunto
        if (filteredAsuntos.length === 0) {
            asuntoIdInput.value = '';  // Limpiar el campo oculto para nuevos asuntos
        }
    });

    // Manejar la navegación por teclado
    asuntoInput.addEventListener('keydown', function (e) {
        const items = document.querySelectorAll('#asunto-list .autocomplete-item'); // Específicamente para la lista de asuntos
        if (items.length > 0) {
            if (e.key === 'ArrowDown') {
                e.preventDefault(); // Prevenir el scroll default
                selectedAsuntoIndex = Math.min(selectedAsuntoIndex + 1, items.length - 1); // Asegura que no exceda el último elemento
                updateActiveAsuntoItem(items, selectedAsuntoIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault(); // Prevenir el scroll default
                selectedAsuntoIndex = Math.max(selectedAsuntoIndex - 1, 0); // Asegura que no baje de 0
                updateActiveAsuntoItem(items, selectedAsuntoIndex);
            } else if (e.key === 'Enter') {
                e.preventDefault(); // Prevenir el comportamiento por defecto de 'Enter'
                if (selectedAsuntoIndex >= 0 && selectedAsuntoIndex < items.length) {
                    const selectedAsunto = asuntosData.find(asunto =>
                        asunto.nombre === items[selectedAsuntoIndex].textContent
                    );
                    selectAsunto(selectedAsunto); // Seleccionar el asunto
                }
            }
        }
    });

    // Función para actualizar el ítem activo de la lista de asuntos
    function updateActiveAsuntoItem(items, index) {
        items.forEach(item => item.classList.remove('active'));
        if (items[index]) {
            items[index].classList.add('active');
            items[index].scrollIntoView({ block: "nearest" }); // Asegurar que esté visible
        }
    }





    // Tipo - Autocompletado y creación de nuevos tipos

    // Obtener los datos de asuntos desde el atributo data-asuntos
    const tipoInput = document.getElementById('tipo-input');
    const tipoIdInput = document.getElementById('tipo-id-input'); // Campo oculto para el ID del tipo
    const tipoList = document.getElementById('tipo-list');
    let selectedTipoIndex = -1; // Inicializar correctamente

    // Función para mostrar la lista filtrada de tipos
    function filterTipos(query) {
        const filtered = tiposData.filter(tipo =>
            tipo.nombre.toLowerCase().includes(query.toLowerCase())
        );
        renderTipoList(filtered);
        return filtered; // Devolver los tipos filtrados para que puedan ser utilizados
    }

    // Función para renderizar la lista de tipos
    function renderTipoList(filtered) {
        tipoList.innerHTML = '';  // Limpiar la lista previa
        if (filtered.length === 0) {
            tipoList.style.display = 'none';  // Si no hay resultados, oculta la lista
            return;
        }
        tipoList.style.display = 'block';  // Muestra la lista si hay resultados
        filtered.forEach((tipo, index) => {
            const li = document.createElement('li');
            li.textContent = tipo.nombre;
            li.setAttribute('data-id', tipo.id);
            li.classList.add('autocomplete-item');
            if (index === selectedTipoIndex) {
                li.classList.add('active');
            }
            li.addEventListener('click', () => selectTipo(tipo));  // Permite seleccionar el tipo
            tipoList.appendChild(li);  // Añadir el tipo a la lista
        });
    }


    // Función para seleccionar un tipo y autocompletar el input
    function selectTipo(tipo) {
        tipoInput.value = tipo.nombre;
        tipoIdInput.value = tipo.id;
        tipoList.style.display = 'none';  // Ocultar la lista después de la selección
        selectedTipoIndex = -1; // Reiniciar el índice seleccionado

    }

    // Mostrar la lista de tipos al ganar el foco, ya sea con clic o tabulador
    tipoInput.addEventListener('focus', function () {
        selectedTipoIndex = -1;
        filterTipos(tipoInput.value);
        clienteList.style.display = 'none';
        asuntoList.style.display = 'none';
    });

    // Filtrar tipos en tiempo real mientras se escribe
    tipoInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();  // Convertir el texto a mayúsculas
        const filteredTipos = filterTipos(this.value);  // Obtener los tipos filtrados
        selectedTipoIndex = -1; // Reiniciar el índice seleccionado

        // Si no hay coincidencias, se permite crear un nuevo tipo
        if (filteredTipos.length === 0) {
            tipoIdInput.value = '';  // Limpiar el campo oculto para nuevos tipos
        }
    });

    // Manejar la navegación por teclado
    tipoInput.addEventListener('keydown', function (e) {
        const items = document.querySelectorAll('#tipo-list .autocomplete-item'); // Específicamente para la lista de tipos
        if (items.length > 0) {
            if (e.key === 'ArrowDown') {
                e.preventDefault(); // Prevenir el scroll default
                selectedTipoIndex = Math.min(selectedTipoIndex + 1, items.length - 1); // Asegura que no exceda el último elemento
                updateActiveTipoItem(items, selectedTipoIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault(); // Prevenir el scroll default
                selectedTipoIndex = Math.max(selectedTipoIndex - 1, 0); // Asegura que no baje de 0
                updateActiveTipoItem(items, selectedTipoIndex);
            } else if (e.key === 'Enter') {
                e.preventDefault(); // Prevenir el comportamiento por defecto de 'Enter'
                if (selectedTipoIndex >= 0 && selectedTipoIndex < items.length) {
                    const selectedTipo = tiposData.find(tipo =>
                        tipo.nombre === items[selectedTipoIndex].textContent
                    );
                    selectTipo(selectedTipo); // Seleccionar el tipo
                }
            }
        }
    });

    // Función para actualizar el ítem activo de la lista de tipos
    function updateActiveTipoItem(items, index) {
        items.forEach(item => item.classList.remove('active'));
        if (items[index]) {
            items[index].classList.add('active');
            items[index].scrollIntoView({ block: "nearest" }); // Asegurar que esté visible
        }
    }



    // Cerrar la lista si se hace clic fuera del campo y de la lista correspondiente
    document.addEventListener('click', function (e) {
        // Cerrar lista de clientes si el clic no es dentro del input o lista de clientes
        if (!clienteInput.contains(e.target) && !clienteList.contains(e.target)) {
            clienteList.style.display = 'none';
        }
        // Cerrar lista de asuntos si el clic no es dentro del input o lista de asuntos
        if (!asuntoInput.contains(e.target) && !asuntoList.contains(e.target)) {
            asuntoList.style.display = 'none';
        }
        // Cerrar lista de tipos si el clic no es dentro del input o lista de tipos
        if (!tipoInput.contains(e.target) && !tipoList.contains(e.target)) {
            tipoList.style.display = 'none';
        }
    });



    // Mostrar el modal de confirmación
    function showModalConfirm() {
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
        modal.style.display = 'flex'; // Mostrar el modal

        // Confirmación modal
        document.getElementById('confirm-modal-yes').addEventListener('click', function () {
            modal.style.display = 'none';
            submitTaskForm();
        });

        document.getElementById('confirm-modal-no').addEventListener('click', function () {
            modal.style.display = 'none';
        });
    }

    // Asignar Usuarios a una tarea
    const userSelect = document.getElementById('user-select');
    const userList = document.getElementById('user-list');
    const selectedUsersContainer = document.getElementById('selected-users');
    const userIdsInput = document.getElementById('user-ids');
    let selectedUsers = [];
    let currentFocus = -1;

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

    // Si no es una tarea única (por ejemplo, en filtrado), limpiamos la tabla
    if (!isSingleTask) {
        tableBody.innerHTML = ''; // Limpiar la tabla existente
    }

    // Convertir el parámetro `tasks` a un array si es un solo objeto
    const tasksArray = isSingleTask ? [tasks] : tasks;

    tasksArray.forEach(task => {
        // Verificar si la tarea coincide con los filtros actuales (si es que hay filtros)
        if (currentFilters && !taskMatchesFilters(task, currentFilters)) {
            // Si no coincide con los filtros actuales, no la mostramos
            return;
        }

        const row = document.createElement('tr');
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
            <td style="display: none;">${task.created_at || 'Sin fecha'}</td> <!-- Campo oculto para created_at -->
        `;

        // Insertar la nueva fila al principio si es una tarea única (añadir tarea)
        if (isSingleTask && tableBody.firstChild) {
            tableBody.insertBefore(row, tableBody.firstChild);
        } else {
            tableBody.appendChild(row);
        }
    });

    
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
    return {
        cliente: document.getElementById('filter-cliente-input') ? document.getElementById('filter-cliente-input').value : '',
        asunto: document.getElementById('filter-asunto-input') ? document.getElementById('filter-asunto-input').value : '',
        tipo: document.getElementById('filter-tipo-input') ? document.getElementById('filter-tipo-input').value : '',
        subtipo: document.getElementById('filter-subtipo') ? document.getElementById('filter-subtipo').value : '',
        estado: document.getElementById('filter-estado') ? document.getElementById('filter-estado').value : '',
        usuario: document.getElementById('filter-user-input') ? document.getElementById('filter-user-input').value : '',
        archivo: document.getElementById('filter-archivo') ? document.getElementById('filter-archivo').value : '',
        facturable: document.getElementById('filter-facturable') ? document.getElementById('filter-facturable').value : '',
        facturado: document.getElementById('filter-facturado') ? document.getElementById('filter-facturado').value : '',
        precio: document.getElementById('filter-precio') ? document.getElementById('filter-precio').value : '',
        suplido: document.getElementById('filter-suplido') ? document.getElementById('filter-suplido').value : '',
        coste: document.getElementById('filter-coste') ? document.getElementById('filter-coste').value : '',
        fecha_inicio: document.getElementById('filter-fecha-inicio') ? document.getElementById('filter-fecha-inicio').value : '',
        fecha_vencimiento: document.getElementById('filter-fecha-vencimiento') ? document.getElementById('filter-fecha-vencimiento').value : '',
        fecha_imputacion: document.getElementById('filter-fecha-imputacion') ? document.getElementById('filter-fecha-imputacion').value : '',
        tiempo_previsto: document.getElementById('filter-tiempo-previsto') ? document.getElementById('filter-tiempo-previsto').value : '',
        tiempo_real: document.getElementById('filter-tiempo-real') ? document.getElementById('filter-tiempo-real').value : ''
    };
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