// Añadir el event listener para manejar el envío del formulario
document.addEventListener('DOMContentLoaded', function () {
    // console.log('El script de add-tasks ha sido cargado correctamente.');

    const taskForm = document.getElementById('task-form');
    const addTaskForm = document.getElementById('add-task-form'); // El propio formulario

    clientesData = JSON.parse(document.getElementById('clientes-data').getAttribute('data-clientes'));

    asuntosData = JSON.parse(document.getElementById('asuntos-data').getAttribute('data-asuntos'));

    tiposData = JSON.parse(document.getElementById('tipos-data').getAttribute('data-tipos'));

    let usersData = JSON.parse(document.getElementById('usuarios-data').getAttribute('data-usuarios'));


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
            descripcion: document.querySelector('textarea[name="descripcion"]').value,
            observaciones: document.querySelector('textarea[name="observaciones"]').value,
            facturable: document.querySelector('input[name="facturable"]').checked ? 1 : 0,
            facturado: document.querySelector('select[name="facturado"]').value,
            fecha_inicio: document.querySelector('input[name="fecha_inicio"]').value,
            fecha_vencimiento: document.querySelector('input[name="fecha_vencimiento"]').value,
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
                    preselectSessionUser();
                    generarBotonesPlanificacion();

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

            // Verificar si la tarea es facturable y no está facturada
            const isFacturable = Boolean(e.task.facturable);
            const isFacturadoNo = e.task.facturado.toUpperCase() === 'NO';

            // Verificar si la tarea es facturable y no está facturada
            if (!isFacturable || !isFacturadoNo) {
                console.log('Tarea no cumple los criterios de facturación (facturable: true, facturado: NO), ignorando...');
                return;
            }

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

    const clienteInput = document.getElementById('cliente-input');
    const clienteIdInput = document.getElementById('cliente-id-input'); // Campo oculto para el ID del cliente
    const clienteList = document.getElementById('cliente-list');
    const clienteInfo = document.getElementById('cliente-info'); // Span para mostrar email y teléfono
    let selectedClienteIndex = -1;

    // Función para mostrar la lista filtrada
    function filterClientes(query) {
        const filtered = clientesData.filter(cliente => {
            const nombreFiscal = cliente.nombre_fiscal ? cliente.nombre_fiscal.toLowerCase() : '';
            const nif = cliente.nif ? cliente.nif.toLowerCase() : ''; // Verificar si NIF no es null

            return nombreFiscal.includes(query.toLowerCase()) || nif.includes(query.toLowerCase());
        });
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
        clienteInput.value = cliente.nombre_fiscal; // Asignar solo el nombre al campo de entrada
        clienteIdInput.value = cliente.id; // Almacenar el ID en el campo oculto
        clienteList.style.display = 'none';
        selectedClienteIndex = -1; // Reiniciar el índice seleccionado

        // Mostrar email y teléfono en el label si existen
        if (cliente.email || cliente.movil) {
            clienteInfo.style.display = 'inline'; // Mostrar el span
            clienteInfo.textContent = `${cliente.email || 'N/A'} | ${cliente.movil || 'N/A'}`;
        } else {
            clienteInfo.style.display = 'none'; // Ocultar si no hay info
        }
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

        // Ocultar la información adicional del cliente cuando no se selecciona de la lista
        clienteInfo.style.display = 'none';
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
                    const selectedCliente = clientesData.find(cliente =>
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
    // Obtener el ID del usuario en sesión
    const sessionUserId = document.querySelector('meta[name="user-id"]').getAttribute('content');

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
    console.log('Filtros aplicados:', getCurrentFilters());

    // Si no es una tarea única (por ejemplo, en filtrado), limpiamos la tabla
    if (!isSingleTask) {
        tableBody.innerHTML = ''; // Limpiar la tabla existente
    }
    // Usar los filtros globales si no se pasan como argumento
    const filtersToApply = currentFilters || window.currentFilters;
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
            <td class="fecha-planificacion-cell" 
                data-fecha_planificacion="${task.fecha_planificacion || ''}" 
                data-task-id="${task.id}">
                ${task.fecha_planificacion ? formatFechaPlanificacion(task.fecha_planificacion) : 'Sin fecha'}
            </td> 
            <td class="col-cliente">${task.cliente ? task.cliente.nombre_fiscal : 'Sin cliente'}</td>
            <td>${task.asunto ? task.asunto.nombre : 'Sin asunto'}</td>
            <td class="col-descripcion">${task.descripcion ? truncateText(task.descripcion, 100) : ''}</td>
            <td class="col-observaciones">${task.observaciones ? truncateText(task.observaciones, 100) : ''}</td>
           <td class="facturable-cell" 
                data-facturable="${task.facturable ? 'SI' : 'NO'}" 
                data-task-id="${task.id}">
                ${task.facturable ? 'SI' : 'NO'}
            </td>
            <td class="facturado-cell" 
                data-facturado="${task.facturado || 'NO'}" 
                data-task-id="${task.id}">
                ${task.facturado || 'NO'}
            </td>
            <td class="estado-cell" 
                data-estado="${task.estado || 'PENDIENTE'}" 
                data-task-id="${task.id}">
                ${task.estado || 'PENDIENTE'}
            </td>
            <td>${task.tipo ? task.tipo.nombre : 'Sin tipo'}</td>
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
    // Inicializar eventos de clic derecho para cada columna dinámica
    initializeContextMenu('facturado-cell', 'facturado', ['SI', 'NO', 'NUNCA']);
    initializeContextMenu('facturable-cell', 'facturable', ['1', '0']);
    initializeContextMenu('estado-cell', 'estado', ['PENDIENTE', 'ENESPERA', 'COMPLETADA', 'PLANIFICADA', 'RECURRENTE/TRIMESTRE']);
    initializeDatePickerContextMenu('fecha-planificacion-cell', 'fecha_planificacion');

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

    if (filters.facturable !== '' && String(Boolean(Number(task.facturable))) !== filters.facturable) {
        console.log(`Facturable no coincide: task.facturable=${task.facturable}, filters.facturable=${filters.facturable}`);
        return false;
    }


    if (filters.facturado && task.facturado !== filters.facturado) {
        console.log(`Facturado no coincide: task.facturado=${task.facturado}, filters.facturado=${filters.facturado}`);
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



// Helper para obtener valores seleccionados de un checklist
function getChecklistValues(fieldName, isBoolean = false) {
    const checkboxes = document.querySelectorAll(`#filter-${fieldName}-list input[type="checkbox"]:checked`);
    return Array.from(checkboxes).map(checkbox => isBoolean ? checkbox.value === "1" : checkbox.value);
}

function getCurrentFilters() {


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