// Añadir el event listener para manejar el envío del formulario
document.addEventListener('DOMContentLoaded', function () {
    console.log('El script de add-customer ha sido cargado correctamente.');

    const customerForm = document.getElementById('customer-form');
    const addCustomerForm = document.getElementById('add-customer-form'); // El propio formulario

    console.log('Botón Nuevo Cliente:', document.getElementById('new-customer-button'));
    console.log('Formulario Cliente:', document.getElementById('customer-form'));
    console.log('Botón Cerrar Formulario:', document.getElementById('close-customer-form'));

    let clasificacionesData = JSON.parse(document.getElementById('clasificaciones-data').getAttribute('data-clasificaciones'));

    let tributacionesData = JSON.parse(document.getElementById('tributaciones-data').getAttribute('data-tributaciones'));

    let situacionesData = JSON.parse(document.getElementById('situaciones-data').getAttribute('data-situaciones'));

    let tiposData = JSON.parse(document.getElementById('tipos-data').getAttribute('data-tipos'));

    let usersData = JSON.parse(document.getElementById('usuarios-data').getAttribute('data-usuarios'));


    const modal = document.getElementById('confirm-modal'); // Modal de confirmación
    const modalMessage = document.getElementById('modal-message');
    const modalClasificacionMessage = document.getElementById('modal-clasificacion-message');
    const modalTipoClienteMessage = document.getElementById('modal-tipo-cliente-message');
    const modalTributacionMessage = document.getElementById('modal-tributacion-message');
    const modalSituacionMessage = document.getElementById('modal-situacion-message');
    let nuevoClasificacion = null;
    let nuevoTributacion = null;
    let nuevoSituacion = null;
    let nuevoTipoCliente = null;


    // Mostrar el formulario cuando se pulsa el botón de "Nueva Tarea"
    document.getElementById('new-customer-button').addEventListener('click', function () {
        customerForm.style.display = 'block';
        setTimeout(() => {
            customerForm.classList.remove('hide');
            customerForm.classList.add('show');
        }, 10);
    });

    // Ocultar el formulario cuando se pulsa el botón de cerrar
    document.getElementById('close-customer-form').addEventListener('click', function () {
        closeCustomerForm();
    });

    // Ocultar el formulario cuando se hace clic fuera de él
    document.addEventListener('click', function (event) {
        const isInsideForm = customerForm.contains(event.target);
        const isInsideModal = document.getElementById('confirm-modal').contains(event.target);
        const isNewCustomerButton = document.getElementById('new-customer-button').contains(event.target);

        // Verifica si el clic no es dentro del formulario, ni dentro del modal ni en el botón de nueva tarea
        if (!isInsideForm && !isInsideModal && !isNewCustomerButton) {
            if (customerForm.classList.contains('show')) {
                closeCustomerForm();
            }
        }
    });

    // Función para cerrar el formulario
    function closeCustomerForm() {
        customerForm.classList.remove('show');
        customerForm.classList.add('hide');
        setTimeout(() => {
            customerForm.style.display = 'none';
        }, 400);
    }

    // Función para manejar el envío del formulario
    function submitCustomerForm() {
        // Obtener los usuarios seleccionados
        const selectedUsers = Array.from(document.querySelectorAll('#user-list input[type="checkbox"]:checked'))
            .map(checkbox => checkbox.value); // Obtener los IDs de los usuarios seleccionados

        // Verificar si el clasificacion ingresado manualmente ya existe
        const clasificacionInputValue = clasificacionInput.value.trim().toUpperCase();
        let clasificacionExistente = clasificacionesData.find(clasificacion => clasificacion.nombre.toUpperCase() === clasificacionInputValue);

        // Si el clasificacion no existe, marcarlo como nuevo
        if (!clasificacionExistente) {
            nuevoClasificacion = clasificacionInputValue;
            clasificacionIdInput.value = ''; // Si es nuevo, dejar vacío el ID
        } else {
            // Si el clasificacion ya existe, asegurarse de que el ID esté asignado
            clasificacionIdInput.value = clasificacionExistente.id;
            nuevoClasificacion = null; // No es necesario crear un nuevo clasificacion
        }

        // Verificar si el tipo ingresado manualmente ya existe
        const tipoInputValue = tipoInput.value.trim().toUpperCase();
        let tipoExistente = tiposData.find(tipo => tipo.nombre.toUpperCase() === tipoInputValue);

        // Si el tipo no existe, marcarlo como nuevo
        if (!tipoExistente) {
            nuevoTipoCliente = tipoInputValue;
            tipoIdInput.value = ''; // Si es nuevo, dejar vacío el ID
        } else {
            // Si el tipo ya existe, asegurarse de que el ID esté asignado
            tipoIdInput.value = tipoExistente.id;
            nuevoTipoCliente = null; // No es necesario crear un nuevo tipo
        }

        // Verificar si el tributacion ingresado manualmente ya existe
        const tributacionInputValue = tributacionInput.value.trim().toUpperCase();
        let tributacionExistente = tributacionesData.find(tributacion => tributacion.nombre.toUpperCase() === tributacionInputValue);

        // Si el tributacion no existe, marcarlo como nuevo
        if (!tributacionExistente) {
            nuevoTributacion = tributacionInputValue;
            tributacionIdInput.value = ''; // Si es nuevo, dejar vacío el ID
        } else {
            // Si el tributacion ya existe, asegurarse de que el ID esté asignado
            tributacionIdInput.value = tributacionExistente.id;
            nuevoTributacion = null; // No es necesario crear un nuevo tributacion
        }

        // Verificar si el situacion ingresado manualmente ya existe
        const situacionInputValue = situacionInput.value.trim().toUpperCase();
        let situacionExistente = situacionesData.find(situacion => situacion.nombre.toUpperCase() === situacionInputValue);

        // Si el situacion no existe, marcarlo como nuevo
        if (!situacionExistente) {
            nuevoSituacion = situacionInputValue;
            situacionIdInput.value = ''; // Si es nuevo, dejar vacío el ID
        } else {
            // Si el situacion ya existe, asegurarse de que el ID esté asignado
            situacionIdInput.value = situacionExistente.id;
            nuevoSituacion = null; // No es necesario crear un nuevo situacion
        }

        const formData = {
            nombre_fiscal: document.querySelector('input[name="nombre_fiscal"]').value,
            nif: document.querySelector('input[name="nif"]').value,
            movil: document.querySelector('input[name="movil"]').value,
            segundo_telefono: document.querySelector('input[name="segundo_telefono"]').value,
            persona_contacto: document.querySelector('input[name="persona_contacto"]').value,
            fijo: document.querySelector('input[name="fijo"]').value,
            email: document.querySelector('input[name="email"]').value,
            direccion: document.querySelector('input[name="direccion"]').value,
            codigo_postal: document.querySelector('input[name="codigo_postal"]').value,
            poblacion: document.querySelector('input[name="poblacion"]').value,
            tipo_cliente_id: tipoIdInput.value, // Si el tipo de cliente es nuevo, esto estará vacío
            tipo_cliente_nombre: nuevoTipoCliente || '', // Enviar nuevo tipo de cliente si no existe
            clasificacion_id: clasificacionIdInput.value, // Si la clasificación es nueva, esto estará vacío
            clasificacion_nombre: nuevoClasificacion || '', // Enviar nueva clasificación si no existe
            tributacion_id: tributacionIdInput.value, // Si la tributación es nueva, esto estará vacío
            tributacion_nombre: nuevoTributacion || '', // Enviar nueva tributación si no existe
            situacion_id: situacionIdInput.value, // Si la situación es nueva, esto estará vacío
            situacion_nombre: nuevoSituacion || '', // Enviar nueva situación si no existe
            datos_bancarios: document.querySelector('textarea[name="datos_bancarios"]').value,
            subclase: document.querySelector('input[name="subclase"]').value,
            puntaje: document.querySelector('input[name="puntaje"]').value,
            codigo_sage: document.querySelector('input[name="codigo_sage"]').value,
            users: selectedUsers // Lista de IDs de los usuarios seleccionados (responsables)
        };


        console.log('Datos del formulario:', formData);

        fetch('/clientes', {
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
                    console.log('Cliente creado:', data.customer);

                    // Si hay una nueva clasificacion en la respuesta, la añadimos a la lista de clasificaciones
                    if (data.customer.clasificacion && !clasificacionesData.some(a => a.id === data.customer.clasificacion.id)) {
                        clasificacionesData.push(data.customer.clasificacion); // Añadir la nueva clasificacion a la lista de clasificaciones
                    }

                    // Si hay un nuevo tipo en la respuesta, lo añadimos a la lista de tipos
                    if (data.customer.tipo_cliente && !tiposData.some(t => t.id === data.customer.tipo_cliente.id)) {
                        tiposData.push(data.customer.tipo_cliente); // Añadir el nuevo tipo a la lista de tipos
                    }

                    // Si hay una nueva tributacion en la respuesta, la añadimos a la lista de tributaciones
                    if (data.customer.tributacion && !tributacionesData.some(a => a.id === data.customer.tributacion.id)) {
                        tributacionesData.push(data.customer.tributacion); // Añadir la nueva tributacion a la lista de tributaciones
                    }

                    // Si hay una nueva situacion en la respuesta, la añadimos a la lista de situaciones
                    if (data.customer.situacion && !situacionesData.some(a => a.id === data.customer.situacion.id)) {
                        situacionesData.push(data.customer.situacion); // Añadir la nueva situacion a la lista de situaciones
                    }

                    showNotification("Cliente creado exitosamente", "success");
                    // Limpiar los usuarios seleccionados
                    resetSelectedUsers();
                    document.getElementById('add-customer-form').reset(); // Resetear el formulario
                } else {
                    console.error('Errores de validación:', data.errors);
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error.message);
            });
    }


    // Manejar el evento de envío del formulario
    addCustomerForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevenir el comportamiento predeterminado de recargar la página

        nuevoClasificacion = null; // Limpiar variables de nuevos valores
        nuevoTipoCliente = null;
        nuevoTributacion = null;
        nuevoSituacion = null;

        // Validar si el clasificacion existe o si es nuevo
        const clasificacionInputValue = clasificacionInput.value.trim();
        const clasificacionValido = clasificacionesData.some(clasificacion =>
            clasificacion.nombre.toUpperCase() === clasificacionInputValue.toUpperCase()
        );

        if (!clasificacionValido) {
            nuevoClasificacion = clasificacionInputValue; // Solo asignar si hay un valor en clasificacionInputValue
        }

        // Validar si el tipo de tarea existe o si es nuevo
        const tipoInputValue = tipoInput.value.trim();
        const tipoValido = tiposData.some(tipoCliente =>
            tipoCliente.nombre.toUpperCase() === tipoInputValue.toUpperCase()
        );

        if (!tipoValido) {
            nuevoTipoCliente = tipoInputValue; // Almacenar el nuevo tipo si no existe
        }

        // Validar si el tributacion existe o si es nuevo
        const tributacionInputValue = tributacionInput.value.trim();
        const tributacionValido = tributacionesData.some(tributacion =>
            tributacion.nombre.toUpperCase() === tributacionInputValue.toUpperCase()
        );

        if (!tributacionValido) {
            nuevoTributacion = tributacionInputValue; // Almacenar el nuevo tributacion si no existe
        }

        // Validar si el situacion existe o si es nuevo
        const situacionInputValue = situacionInput.value.trim();
        const situacionValido = situacionesData.some(situacion =>
            situacion.nombre.toUpperCase() === situacionInputValue.toUpperCase()
        );

        if (!situacionValido) {
            nuevoSituacion = situacionInputValue; // Almacenar el nuevo situacion si no existe
        }

        // Si hay un nuevo asunto o un nuevo tipo, mostrar el modal
        if (nuevoClasificacion || nuevoTipoCliente || nuevoTributacion || nuevoSituacion) {
            console.log(nuevoClasificacion);
            showModalConfirm();
        } else {
            // Confirmar antes de enviar
            if (confirm("¿Estás seguro de que deseas enviar el formulario?")) {
                submitCustomerForm(); // Si se confirma, enviar el formulario
            }
        }
    });

    // Escuchar el canal y el evento del WebSocket
    window.Echo.channel('customers')
        .listen('CustomerCreated', (e) => {
            console.log('Nuevo cliente creado:', e);

            let currentFilters = null;
            // currentFilters = getCurrentFilters(); // Obtener los filtros actuales solo si están disponibles

            // Actualizar la tabla con el nuevo cliente y los filtros actuales
            updateCustomerTable(e.customer, true, currentFilters);
        });



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


    // Clasificación - Autocompletado y creación de nuevos asuntos

    // Obtener los datos de asuntos desde el atributo data-asuntos
    const clasificacionInput = document.getElementById('clasificacion-input');
    const clasificacionIdInput = document.getElementById('clasificacion-id-input'); // Campo oculto para el ID del clasificacion
    const clasificacionList = document.getElementById('clasificacion-list');
    let selectedClasificacionIndex = -1; // Inicializar correctamente

    // Función para mostrar la lista filtrada de clasificaciones
    function filterClasificaciones(query) {
        const filtered = clasificacionesData.filter(clasificacion =>
            clasificacion.nombre.toLowerCase().includes(query.toLowerCase())
        );
        renderClasificacionList(filtered);
        return filtered;  // Devolver los asuntos filtrados para que puedan ser utilizados
    }

    // Función para renderizar la lista de clasificaciones
    function renderClasificacionList(filtered) {
        clasificacionList.innerHTML = '';  // Limpiar la lista previa
        if (filtered.length === 0) {
            clasificacionList.style.display = 'none';  // Si no hay resultados, oculta la lista
            return;
        }
        clasificacionList.style.display = 'block';  // Muestra la lista si hay resultados
        filtered.forEach((clasificacion, index) => {
            const li = document.createElement('li');
            li.textContent = clasificacion.nombre;
            li.setAttribute('data-id', clasificacion.id);
            li.classList.add('autocomplete-item');
            if (index === selectedClasificacionIndex) {
                li.classList.add('active');
            }
            li.addEventListener('click', () => selectClasificacion(clasificacion));  // Permite seleccionar la clasificacion
            clasificacionList.appendChild(li);  // Añadir el clasificacion a la lista
        });
    }

    // Función para seleccionar un clasificacion y autocompletar el input
    function selectClasificacion(clasificacion) {
        clasificacionInput.value = clasificacion.nombre;
        clasificacionIdInput.value = clasificacion.id;
        clasificacionList.style.display = 'none';  // Ocultar la lista después de la selección
        selectedClasificacionIndex = -1; // Reiniciar el índice seleccionado
    }


    // Mostrar la lista de clasificaciones al ganar el foco, ya sea con clic o tabulador
    clasificacionInput.addEventListener('focus', function () {
        selectedClasificacionIndex = -1;
        filterClasificaciones(clasificacionInput.value);
        tributacionList.style.display = 'none';
        situacionList.style.display = 'none';
        tipoList.style.display = 'none';
    });

    // Filtrar clasificaciones en tiempo real mientras se escribe
    clasificacionInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();  // Convertir el texto a mayúsculas
        const filteredClasificaciones = filterClasificaciones(this.value);  // Obtener los asuntos filtrados
        selectedClasificacionIndex = -1; // Reiniciar el índice seleccionado

        // Si no hay coincidencias, se permite crear un nuevo clasificacion
        if (filteredClasificaciones.length === 0) {
            clasificacionIdInput.value = '';  // Limpiar el campo oculto para nuevos clasificacion
        }
    });

    // Manejar la navegación por teclado
    clasificacionInput.addEventListener('keydown', function (e) {
        const items = document.querySelectorAll('#clasificacion-list .autocomplete-item'); // Específicamente para la lista de clasificaciones
        if (items.length > 0) {
            if (e.key === 'ArrowDown') {
                e.preventDefault(); // Prevenir el scroll default
                selectedClasificacionIndex = Math.min(selectedClasificacionIndex + 1, items.length - 1); // Asegura que no exceda el último elemento
                updateActiveClasificacionItem(items, selectedClasificacionIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault(); // Prevenir el scroll default
                selectedClasificacionIndex = Math.max(selectedClasificacionIndex - 1, 0); // Asegura que no baje de 0
                updateActiveClasificacionItem(items, selectedClasificacionIndex);
            } else if (e.key === 'Enter') {
                e.preventDefault(); // Prevenir el comportamiento por defecto de 'Enter'
                if (selectedClasificacionIndex >= 0 && selectedClasificacionIndex < items.length) {
                    const selectedClasificacion = clasificacionesData.find(clasificacion =>
                        clasificacion.nombre === items[selectedClasificacionIndex].textContent
                    );
                    selectClasificacion(selectedClasificacion); // Seleccionar el Clasificacion
                }
            }
        }
    });

    // Función para actualizar el ítem activo de la lista de asuntos
    function updateActiveClasificacionItem(items, index) {
        items.forEach(item => item.classList.remove('active'));
        if (items[index]) {
            items[index].classList.add('active');
            items[index].scrollIntoView({ block: "nearest" }); // Asegurar que esté visible
        }
    }

    // Tipo - Autocompletado y creación de nuevos tipos

    // Obtener los datos de asuntos desde el atributo data-asuntos
    const tipoInput = document.getElementById('tipo_cliente-input');
    const tipoIdInput = document.getElementById('tipo_cliente-id-input'); // Campo oculto para el ID del tipo
    const tipoList = document.getElementById('tipo_cliente-list');
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
        tributacionList.style.display = 'none';
        situacionList.style.display = 'none';
        clasificacionList.style.display = 'none';
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
        const items = document.querySelectorAll('#tipo_cliente-list .autocomplete-item'); // Específicamente para la lista de tipos
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


    // Tributación - Autocompletado y creación de nuevos asuntos

    // Obtener los datos de asuntos desde el atributo data-asuntos
    const tributacionInput = document.getElementById('tributacion-input');
    const tributacionIdInput = document.getElementById('tributacion-id-input'); // Campo oculto para el ID del clasificacion
    const tributacionList = document.getElementById('tributacion-list');
    let selectedTributacionIndex = -1; // Inicializar correctamente

    // Función para mostrar la lista filtrada de clasificaciones
    function filterTributaciones(query) {
        const filtered = tributacionesData.filter(tributacion =>
            tributacion.nombre.toLowerCase().includes(query.toLowerCase())
        );
        renderTributacionList(filtered);
        return filtered;  // Devolver los asuntos filtrados para que puedan ser utilizados
    }

    // Función para renderizar la lista de tributaciones
    function renderTributacionList(filtered) {
        tributacionList.innerHTML = '';  // Limpiar la lista previa
        if (filtered.length === 0) {
            tributacionList.style.display = 'none';  // Si no hay resultados, oculta la lista
            return;
        }
        tributacionList.style.display = 'block';  // Muestra la lista si hay resultados
        filtered.forEach((tributacion, index) => {
            const li = document.createElement('li');
            li.textContent = tributacion.nombre;
            li.setAttribute('data-id', tributacion.id);
            li.classList.add('autocomplete-item');
            if (index === selectedTributacionIndex) {
                li.classList.add('active');
            }
            li.addEventListener('click', () => selectTributacion(tributacion));  // Permite seleccionar la tributacion
            tributacionList.appendChild(li);  // Añadir el tributacion a la lista
        });
    }

    // Función para seleccionar un tributacion y autocompletar el input
    function selectTributacion(tributacion) {
        tributacionInput.value = tributacion.nombre;
        tributacionIdInput.value = tributacion.id;
        tributacionList.style.display = 'none';  // Ocultar la lista después de la selección
        selectedTributacionIndex = -1; // Reiniciar el índice seleccionado
    }


    // Mostrar la lista de tributaciones al ganar el foco, ya sea con clic o tabulador
    tributacionInput.addEventListener('focus', function () {
        selectedTributacionIndex = -1;
        filterTributaciones(tributacionInput.value);
        clasificacionList.style.display = 'none';
        situacionList.style.display = 'none';
        tipoList.style.display = 'none';
    });

    // Filtrar tributaciones en tiempo real mientras se escribe
    tributacionInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();  // Convertir el texto a mayúsculas
        const filteredTributaciones = filterTributaciones(this.value);  // Obtener los asuntos filtrados
        selectedTributacionIndex = -1; // Reiniciar el índice seleccionado

        // Si no hay coincidencias, se permite crear un nuevo tributacion
        if (filteredTributaciones.length === 0) {
            tributacionIdInput.value = '';  // Limpiar el campo oculto para nuevos tributacion
        }
    });

    // Manejar la navegación por teclado
    tributacionInput.addEventListener('keydown', function (e) {
        const items = document.querySelectorAll('#tributacion-list .autocomplete-item'); // Específicamente para la lista de tributaciones
        if (items.length > 0) {
            if (e.key === 'ArrowDown') {
                e.preventDefault(); // Prevenir el scroll default
                selectedTributacionIndex = Math.min(selectedTributacionIndex + 1, items.length - 1); // Asegura que no exceda el último elemento
                updateActiveTributacionItem(items, selectedTributacionIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault(); // Prevenir el scroll default
                selectedTributacionIndex = Math.max(selectedTributacionIndex - 1, 0); // Asegura que no baje de 0
                updateActiveTributacionItem(items, selectedTributacionIndex);
            } else if (e.key === 'Enter') {
                e.preventDefault(); // Prevenir el comportamiento por defecto de 'Enter'
                if (selectedTributacionIndex >= 0 && selectedTributacionIndex < items.length) {
                    const selectedTributacion = tributacionesData.find(tributacion =>
                        tributacion.nombre === items[selectedTributacionIndex].textContent
                    );
                    selectTributacion(selectedTributacion); // Seleccionar el Tributacion
                }
            }
        }
    });

    // Función para actualizar el ítem activo de la lista de asuntos
    function updateActiveTributacionItem(items, index) {
        items.forEach(item => item.classList.remove('active'));
        if (items[index]) {
            items[index].classList.add('active');
            items[index].scrollIntoView({ block: "nearest" }); // Asegurar que esté visible
        }
    }




    // Situación - Autocompletado y creación de nuevos asuntos

    // Obtener los datos de situacion desde el atributo data-situacion
    const situacionInput = document.getElementById('situacion-input');
    const situacionIdInput = document.getElementById('situacion-id-input'); // Campo oculto para el ID del situacion
    const situacionList = document.getElementById('situacion-list');
    let selectedSituacionIndex = -1; // Inicializar correctamente

    // Función para mostrar la lista filtrada de situacion
    function filterSituaciones(query) {
        const filtered = situacionesData.filter(situacion =>
            situacion.nombre.toLowerCase().includes(query.toLowerCase())
        );
        renderSituacionList(filtered);
        return filtered;  // Devolver los situacion filtrados para que puedan ser utilizados
    }

    // Función para renderizar la lista de situaciones
    function renderSituacionList(filtered) {
        situacionList.innerHTML = '';  // Limpiar la lista previa
        if (filtered.length === 0) {
            situacionList.style.display = 'none';  // Si no hay resultados, oculta la lista
            return;
        }
        situacionList.style.display = 'block';  // Muestra la lista si hay resultados
        filtered.forEach((situacion, index) => {
            const li = document.createElement('li');
            li.textContent = situacion.nombre;
            li.setAttribute('data-id', situacion.id);
            li.classList.add('autocomplete-item');
            if (index === selectedSituacionIndex) {
                li.classList.add('active');
            }
            li.addEventListener('click', () => selectSituacion(situacion));  // Permite seleccionar la situacion
            situacionList.appendChild(li);  // Añadir el situacion a la lista
        });
    }

    // Función para seleccionar un situacion y autocompletar el input
    function selectSituacion(situacion) {
        situacionInput.value = situacion.nombre;
        situacionIdInput.value = situacion.id;
        situacionList.style.display = 'none';  // Ocultar la lista después de la selección
        selectedSituacionIndex = -1; // Reiniciar el índice seleccionado
    }


    // Mostrar la lista de situaciones al ganar el foco, ya sea con clic o tabulador
    situacionInput.addEventListener('focus', function () {
        selectedSituacionIndex = -1;
        filterSituaciones(situacionInput.value);
        clasificacionList.style.display = 'none';
        tributacionList.style.display = 'none';
        tipoList.style.display = 'none';
    });

    // Filtrar situaciones en tiempo real mientras se escribe
    situacionInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();  // Convertir el texto a mayúsculas
        const filteredSituaciones = filterSituaciones(this.value);  // Obtener los situacion filtrados
        selectedSituacionIndex = -1; // Reiniciar el índice seleccionado

        // Si no hay coincidencias, se permite crear un nuevo situacion
        if (filteredSituaciones.length === 0) {
            situacionIdInput.value = '';  // Limpiar el campo oculto para nuevas situaciones
        }
    });

    // Manejar la navegación por teclado
    situacionInput.addEventListener('keydown', function (e) {
        const items = document.querySelectorAll('#situacion-list .autocomplete-item'); // Específicamente para la lista de situaciones
        if (items.length > 0) {
            if (e.key === 'ArrowDown') {
                e.preventDefault(); // Prevenir el scroll default
                selectedSituacionIndex = Math.min(selectedSituacionIndex + 1, items.length - 1); // Asegura que no exceda el último elemento
                updateActiveSituacionItem(items, selectedSituacionIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault(); // Prevenir el scroll default
                selectedSituacionIndex = Math.max(selectedSituacionIndex - 1, 0); // Asegura que no baje de 0
                updateActiveSituacionItem(items, selectedSituacionIndex);
            } else if (e.key === 'Enter') {
                e.preventDefault(); // Prevenir el comportamiento por defecto de 'Enter'
                if (selectedSituacionIndex >= 0 && selectedSituacionIndex < items.length) {
                    const selectedSituacion = situacionesData.find(situacion =>
                        situacion.nombre === items[selectedSituacionIndex].textContent
                    );
                    selectSituacion(selectedSituacion); // Seleccionar el Situacion
                }
            }
        }
    });

    // Función para actualizar el ítem activo de la lista de asuntos
    function updateActiveSituacionItem(items, index) {
        items.forEach(item => item.classList.remove('active'));
        if (items[index]) {
            items[index].classList.add('active');
            items[index].scrollIntoView({ block: "nearest" }); // Asegurar que esté visible
        }
    }


    // Cerrar la lista si se hace clic fuera del campo y de la lista correspondiente
    document.addEventListener('click', function (e) {
        // Cerrar lista de clientes si el clic no es dentro del input o lista de clasificaciones
        if (!clasificacionInput.contains(e.target) && !clasificacionList.contains(e.target)) {
            clasificacionList.style.display = 'none';
        }
        // Cerrar lista de asuntos si el clic no es dentro del input o lista de tributaciones
        if (!tributacionInput.contains(e.target) && !tributacionList.contains(e.target)) {
            tributacionList.style.display = 'none';
        }
        // Cerrar lista de tipos si el clic no es dentro del input o lista de tipos
        if (!tipoInput.contains(e.target) && !tipoList.contains(e.target)) {
            tipoList.style.display = 'none';
        }
        // Cerrar lista de tipos si el clic no es dentro del input o lista de situaciones
        if (!situacionInput.contains(e.target) && !situacionList.contains(e.target)) {
            situacionList.style.display = 'none';
        }
        // Cerrar lista de tipos si el clic no es dentro del input o lista de situaciones
        if (!userSelect.contains(e.target) && !userList.contains(e.target)) {
            userList.style.display = 'none';
        }
    });




    // Mostrar el modal de confirmación
    function showModalConfirm() {

        console.log("Nuevo Clasificación:", nuevoClasificacion);
        console.log("Nuevo Tipo Cliente:", nuevoTipoCliente);
        console.log("Nuevo Tributación:", nuevoTributacion);
        console.log("Nuevo Situación:", nuevoSituacion);

        if (modalClasificacionMessage) {
            modalClasificacionMessage.textContent = nuevoClasificacion
                ? `La clasificación "${nuevoClasificacion}" no existe. ¿Deseas crearla?`
                : '';
        }

        if (modalTributacionMessage) {
            modalTributacionMessage.textContent = nuevoTributacion
                ? `La tributación "${nuevoTributacion}" no existe. ¿Deseas crearla?`
                : '';
        }

        if (modalTipoClienteMessage) {
            modalTipoClienteMessage.textContent = nuevoTipoCliente
                ? `El tipo de cliente "${nuevoTipoCliente}" no existe. ¿Deseas crearlo?`
                : '';
        }

        if (modalSituacionMessage) {
            modalSituacionMessage.textContent = nuevoSituacion
                ? `La situación "${nuevoSituacion}" no existe. ¿Deseas crearla?`
                : '';
        }

        modal.style.display = 'flex'; // Mostrar el modal

        // Confirmación modal
        document.getElementById('confirm-modal-yes').addEventListener('click', function () {
            modal.style.display = 'none';
            submitCustomerForm(); // Cambiar a submitCustomerForm para manejar el envío del formulario de cliente
        });

        document.getElementById('confirm-modal-no').addEventListener('click', function () {
            modal.style.display = 'none';
        });
    }



    // Asignar Usuarios a un cliente
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
                case 'nombre':
                    valA = a.children[1].textContent.trim().toLowerCase();
                    valB = b.children[1].textContent.trim().toLowerCase();
                    break;
                case 'tipo_cliente':
                    valA = a.children[10].textContent.trim().toLowerCase();
                    valB = b.children[10].textContent.trim().toLowerCase();
                    break;
                case 'situacion':
                    valA = a.children[13].textContent.trim().toLowerCase();
                    valB = b.children[13].textContent.trim().toLowerCase();
                    break;
                case 'fecha_creacion':
                default:
                    // Utilizar created_at (última columna oculta)
                    valA = new Date(a.children[a.children.length - 1].textContent); // Última columna
                    valB = new Date(b.children[b.children.length - 1].textContent);

                    // Ordenar por fecha primero y luego por ID en caso de igualdad
                    if (valA.getTime() === valB.getTime()) {
                        const idA = parseInt(a.children[0].textContent); // Supuesto ID en la primera columna
                        const idB = parseInt(b.children[0].textContent);
                        return idA - idB; // Orden ascendente por ID
                    }

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




// Función para actualizar la tabla con el nuevo cliente
function updateCustomerTable(customers, isSingleCustomer = false, currentFilters = null, pagination = null) {
    const tableBody = document.querySelector('table tbody');

    // Si no es un cliente único (por ejemplo, en filtrado), limpiamos la tabla
    if (!isSingleCustomer) {
        tableBody.innerHTML = ''; // Limpiar la tabla existente
    }

    // Convertir el parámetro `customers` a un array si es un solo objeto
    const customersArray = isSingleCustomer ? [customers] : customers;

    customersArray.forEach(customer => {
        // Verificar si el cliente coincide con los filtros actuales (si es que hay filtros)
        if (currentFilters && !customerMatchesFilters(customer, currentFilters)) {
            // Si no coincide con los filtros actuales, no lo mostramos
            return;
        }

        const row = document.createElement('tr');
        row.setAttribute('data-customer-id', customer.id); // Asignar el id del cliente
        row.innerHTML = `
            <td>${customer.id}</td>
            <td>${customer.nombre_fiscal || 'Sin nombre fiscal'}</td>
            <td>${customer.nif || 'Sin NIF'}</td>
            <td>${customer.movil || 'Sin móvil'}</td>
            <td>${customer.segundo_telefono || 'Sin 2º teléfono'}</td>
            <td>${customer.fijo || 'Sin fijo'}</td>
            <td>${customer.persona_contacto || 'Sin Persona Contacto'}</td>
            <td>${customer.email || 'Sin email'}</td>
            <td>${customer.direccion || 'Sin dirección'}</td>
            <td>${customer.codigo_postal || 'Sin código postal'}</td>
            <td>${customer.poblacion || 'Sin población'}</td>
            <td>${customer.users && customer.users.length > 0 ? customer.users.map(user => user.name).join(', ') : 'Sin responsable'}</td>
            <td>${customer.tipo_cliente ? customer.tipo_cliente.nombre : 'Sin tipo de cliente'}</td>
            <td>${customer.tributacion ? customer.tributacion.nombre : 'Sin tributación'}</td>
            <td>${customer.clasificacion ? customer.clasificacion.nombre : 'Sin clasificación'}</td>
            <td>${customer.situacion ? customer.situacion.nombre : 'Sin situación'}</td>
            <td>${customer.datos_bancarios || 'Sin datos bancarios'}</td>
            <td>${customer.subclase || 'Sin subclase'}</td>
            <td>${customer.puntaje || 'N/A'}</td>
            <td>${customer.codigo_sage || 'N/A'}</td>
            <td style="display: none;">${customer.created_at || 'Sin fecha'}</td> <!-- Campo oculto para created_at -->
        `;

        // Insertar la nueva fila al principio si es un cliente único (añadir cliente)
        if (isSingleCustomer && tableBody.firstChild) {
            tableBody.insertBefore(row, tableBody.firstChild);
        } else {
            tableBody.appendChild(row);
        }

        // Añadir el evento de doble clic a las filas de la tabla
        addDoubleClickEventToRows();
    });
}

// Función para actualizar una fila específica en la tabla al editar un cliente
function updateSingleCustomerRow(customer) {
    // Buscar la fila existente con el ID del cliente
    const existingRow = document.querySelector(`tr[data-customer-id="${customer.id}"]`);

    if (existingRow) {
        // Si la fila ya existe, actualizar su contenido
        existingRow.innerHTML = `
            <td>${customer.id}</td>
            <td>${customer.nombre_fiscal || 'Sin nombre fiscal'}</td>
            <td>${customer.nif || 'Sin NIF'}</td>
            <td>${customer.movil || 'Sin móvil'}</td>
            <td>${customer.segundo_telefono || 'Sin 2º teléfono'}</td>
            <td>${customer.fijo || 'Sin fijo'}</td>
            <td>${customer.persona_contacto || 'Sin Persona Contacto'}</td>
            <td>${customer.email || 'Sin email'}</td>
            <td>${customer.direccion || 'Sin dirección'}</td>
            <td>${customer.codigo_postal || 'Sin código postal'}</td>
            <td>${customer.poblacion || 'Sin población'}</td>
            <td>${customer.users && customer.users.length > 0 ? customer.users.map(user => user.name).join(', ') : 'Sin responsable'}</td>
            <td>${customer.tipo_cliente ? customer.tipo_cliente.nombre : 'Sin tipo de cliente'}</td>
            <td>${customer.tributacion ? customer.tributacion.nombre : 'Sin tributación'}</td>
            <td>${customer.clasificacion ? customer.clasificacion.nombre : 'Sin clasificación'}</td>
            <td>${customer.situacion ? customer.situacion.nombre : 'Sin situación'}</td>
            <td>${customer.datos_bancarios || 'Sin datos bancarios'}</td>
            <td>${customer.subclase || 'Sin subclase'}</td>
            <td>${customer.puntaje || 'N/A'}</td>
            <td>${customer.codigo_sage || 'N/A'}</td>
            <td style="display: none;">${customer.created_at || 'Sin fecha'}</td>
        `;
    } else {
        console.error(`No se encontró una fila con el ID del cliente: ${customer.id}`);
    }

    // Añadir el evento de doble clic a la fila actualizada (si es necesario)
    addDoubleClickEventToRows();
}




// Función para verificar si un cliente coincide con los filtros actuales
function customerMatchesFilters(customer, filters) {
    // Verificar cada filtro
    if (filters.nombre_fiscal && (!customer.nombre_fiscal || !customer.nombre_fiscal.includes(filters.nombre_fiscal))) {
        return false;
    }

    if (filters.nif && (!customer.nif || !customer.nif.includes(filters.nif))) {
        return false;
    }

    if (filters.movil && (!customer.movil || !customer.movil.includes(filters.movil))) {
        return false;
    }

    if (filters.fijo && (!customer.fijo || !customer.fijo.includes(filters.fijo))) {
        return false;
    }

    if (filters.email && (!customer.email || !customer.email.includes(filters.email))) {
        return false;
    }

    if (filters.direccion && (!customer.direccion || !customer.direccion.includes(filters.direccion))) {
        return false;
    }

    if (filters.codigo_postal && (!customer.codigo_postal || !customer.codigo_postal.includes(filters.codigo_postal))) {
        return false;
    }

    if (filters.poblacion && (!customer.poblacion || !customer.poblacion.includes(filters.poblacion))) {
        return false;
    }

    if (filters.tipo_cliente && (!customer.tipo_cliente || !customer.tipo_cliente.nombre.includes(filters.tipo_cliente))) {
        return false;
    }

    if (filters.tributacion && (!customer.tributacion || !customer.tributacion.nombre.includes(filters.tributacion))) {
        return false;
    }

    if (filters.clasificacion && (!customer.clasificacion || !customer.clasificacion.nombre.includes(filters.clasificacion))) {
        return false;
    }

    if (filters.situacion && (!customer.situacion || !customer.situacion.nombre.includes(filters.situacion))) {
        return false;
    }

    if (filters.datos_bancarios && (!customer.datos_bancarios || !customer.datos_bancarios.includes(filters.datos_bancarios))) {
        return false;
    }

    if (filters.subclase && (!customer.subclase || !customer.subclase.includes(filters.subclase))) {
        return false;
    }

    if (filters.puntaje && parseInt(customer.puntaje) !== parseInt(filters.puntaje)) {
        return false;
    }

    if (filters.codigo_sage && parseInt(customer.codigo_sage) !== parseInt(filters.codigo_sage)) {
        return false;
    }

    return true;
}



function getCurrentFilters() {
    return {
        nombre_fiscal: document.getElementById('filter-nombre-fiscal-input') ? document.getElementById('filter-nombre-fiscal-input').value : '',
        nif: document.getElementById('filter-nif-input') ? document.getElementById('filter-nif-input').value : '',
        movil: document.getElementById('filter-movil-input') ? document.getElementById('filter-movil-input').value : '',
        fijo: document.getElementById('filter-fijo-input') ? document.getElementById('filter-fijo-input').value : '',
        email: document.getElementById('filter-email-input') ? document.getElementById('filter-email-input').value : '',
        direccion: document.getElementById('filter-direccion-input') ? document.getElementById('filter-direccion-input').value : '',
        codigo_postal: document.getElementById('filter-codigo-postal-input') ? document.getElementById('filter-codigo-postal-input').value : '',
        poblacion: document.getElementById('filter-poblacion-input') ? document.getElementById('filter-poblacion-input').value : '',
        tipo_cliente: document.getElementById('filter-tipo-cliente-input') ? document.getElementById('filter-tipo-cliente-input').value : '',
        tributacion: document.getElementById('filter-tributacion-input') ? document.getElementById('filter-tributacion-input').value : '',
        clasificacion: document.getElementById('filter-clasificacion-input') ? document.getElementById('filter-clasificacion-input').value : '',
        situacion: document.getElementById('filter-situacion-input') ? document.getElementById('filter-situacion-input').value : '',
        datos_bancarios: document.getElementById('filter-datos-bancarios-input') ? document.getElementById('filter-datos-bancarios-input').value : '',
        subclase: document.getElementById('filter-subclase-input') ? document.getElementById('filter-subclase-input').value : '',
        puntaje: document.getElementById('filter-puntaje-input') ? document.getElementById('filter-puntaje-input').value : '',
        codigo_sage: document.getElementById('filter-codigo-sage-input') ? document.getElementById('filter-codigo-sage-input').value : ''
    };
}


// Función para mostrar la notificación de éxito
function showSuccessNotification(message = "Cliente creado exitosamente") {
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