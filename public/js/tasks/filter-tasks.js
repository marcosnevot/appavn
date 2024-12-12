
const filterTaskButton = document.getElementById('filter-task-button');
const filterTaskForm = document.getElementById('filter-task-form');
const filterTaskFormContent = document.getElementById('filter-task-form-content');

const applyFilterButton = document.getElementById('apply-filter-button');
const cancelFilterButton = document.getElementById('cancel-filter-button');
const clearFilterButton = document.getElementById('clear-filter-button');

let isLoading = false; // Bandera para evitar solicitudes concurrentes





function loadFilteredTasks(page = 1, sortKey = 'fecha_planificacion', sortDirection = 'asc') {
    if (isLoading) return; // Salir si ya hay una solicitud en curso
    isLoading = true; // Marcar que una solicitud está en curso

    // Extraer fechas del rango de planificación desde el formulario
    const fechaPlanificacionInput = document.getElementById('filter-fecha-planificacion').value || '';
    const [fechaPlanificacionInicio, fechaPlanificacionFin] = fechaPlanificacionInput === 'past'
        ? ['past', 'past'] // Manejar "past" como caso especial
        : fechaPlanificacionInput.split(' - ');

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
        descripcion: document.getElementById('filter-descripcion-input').value || '', // Nuevo campo para descripción
        observaciones: document.getElementById('filter-observaciones-input').value || '', // Nuevo campo para observaciones
        sortKey: sortKey, // Agregar el criterio de orden
        sortDirection: sortDirection // Agregar la dirección de orden
    };

    console.log('Datos de filtro:', filterData);
    window.currentFilters = filterData; // Actualiza los filtros activos en la variable global

    // Actualizar el panel con los filtros actuales
    updateFilterInfoPanel(filterData);

    sincronizarBotonesConFecha(); // Sincronizar botones antes de resetear
    resetFiltroRapidoPlanificacion(); // Ajustar estado solo si es necesario

    // Realizar la solicitud al servidor para filtrar las tareas
    fetch(`/tareas/filtrar?page=${page}`, {  // <-- Asegúrate de pasar el número de página
        method: 'POST',
        body: JSON.stringify(filterData),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateTaskTable(data.filteredTasks);
                updatePagination(data.pagination, (newPage) => loadFilteredTasks(newPage, sortKey, sortDirection)); // Paginación con filtros y orden
                updateHoursSummaryFromTotals(data.totalTiempoPrevisto, data.totalTiempoReal);
                updateSelectedUserNamesFromFilterForm();
                closeFilterTaskForm();
            } else {
                console.error('Error al filtrar tareas:', data.message);
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error.message);
        })
        .finally(() => {
            isLoading = false; // Restablecer la bandera al finalizar
        });
}

let usersData = JSON.parse(document.getElementById('usuarios-data').getAttribute('data-usuarios'));

function updateFilterInfoPanel(filters) {
    const filterInfoContent = document.getElementById('filter-info-content');
    const filterInfoPanel = document.getElementById('filter-info-panel');

    filterInfoContent.innerHTML = ''; // Limpiar contenido anterior

    // Filtrar las entradas con valores no vacíos
    const filterEntries = Object.entries(filters).filter(([key, value]) => value !== '');

    if (filterEntries.length === 0) {
        // Ocultar el panel cuando no hay filtros aplicados
        filterInfoPanel.classList.add('hide');
    } else {
        filterEntries.forEach(([key, value]) => {
            const p = document.createElement('p');

            if (key === 'cliente') {
                // Manejo para clientes
                const clienteIds = value.split(',').map(id => parseInt(id));
                const clienteNames = clienteIds
                    .map(id => {
                        const cliente = clientesData.find(cliente => cliente.id === id);
                        return cliente ? cliente.nombre_fiscal : 'Desconocido';
                    })
                    .join(', ');

                p.textContent = `Cliente(s): ${clienteNames || 'Desconocido'}`;
            } else if (key === 'asunto') {
                // Manejo para asuntos
                const asuntoIds = value.split(',').map(id => parseInt(id));
                const asuntoNames = asuntoIds
                    .map(id => {
                        const asunto = asuntosData.find(asunto => asunto.id === id);
                        return asunto ? asunto.nombre : 'Desconocido';
                    })
                    .join(', ');

                p.textContent = `Asunto(s): ${asuntoNames || 'Desconocido'}`;
            } else if (key === 'tipo') {
                // Manejo para tipos
                const tipoIds = value.split(',').map(id => parseInt(id));
                const tipoNames = tipoIds
                    .map(id => {
                        const tipo = tiposData.find(tipo => tipo.id === id);
                        return tipo ? tipo.nombre : 'Desconocido';
                    })
                    .join(', ');

                p.textContent = `Tipo(s): ${tipoNames || 'Desconocido'}`;
            } else if (key === 'usuario') {
                // Manejo para usuarios
                const userIds = value.split(',').map(id => parseInt(id));
                const userNames = userIds
                    .map(id => {
                        const usuario = usersData.find(usuario => usuario.id === id);
                        return usuario ? usuario.name : 'Desconocido';
                    })
                    .join(', ');

                p.textContent = `Mostrando Tareas De: ${userNames}`;
            } else {
                p.textContent = `${capitalizeFirstLetter(key)}: ${value}`;
            }

            filterInfoContent.appendChild(p);
        });


        // Mostrar el panel si hay filtros
        filterInfoPanel.classList.remove('hide');
    }
}


// Función para capitalizar la primera letra
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1).replace('_', ' ');
}





// Función para actualizar el nombre de los usuarios en el título desde el formulario
function updateSelectedUserNamesFromFilterForm() {
    const selectedUserNamesElement = document.getElementById('selected-user-names'); // Seleccionar el span en el título
    const selectedCheckboxes = document.querySelectorAll('#filter-user-list input.user-checkbox:checked'); // Checkboxes seleccionados

    if (selectedCheckboxes.length === 0) {
        // Si no hay usuarios seleccionados, mostrar "todos"
        selectedUserNamesElement.textContent = 'todos';
    } else {
        // Obtén los nombres de los usuarios seleccionados
        const selectedNames = Array.from(selectedCheckboxes).map(checkbox => {
            const label = document.querySelector(`label[for="${checkbox.id}"]`);
            return label ? label.textContent : ''; // Extrae el texto del label asociado
        }).join(', ');

        // Actualiza el contenido del span con los nombres seleccionados
        selectedUserNamesElement.textContent = selectedNames;
    }
}

// Función para cerrar el formulario
function closeFilterTaskForm() {
    filterTaskForm.classList.remove('show');
    filterTaskForm.classList.add('hide');
    setTimeout(() => {
        filterTaskForm.style.display = 'none';
    }, 400);

}










document.addEventListener('DOMContentLoaded', function () {
    console.log('El script de filtro ha sido cargado correctamente.');

    // Obtener los datos de clientes, asuntos y tipos desde los atributos data


    // Mostrar el formulario de filtrar tareas
    filterTaskButton.addEventListener('click', function () {
        filterTaskForm.style.display = 'block';
        setTimeout(() => {
            filterTaskForm.classList.remove('hide');
            filterTaskForm.classList.add('show');
        }, 10);
    });

    // Ocultar el formulario de filtrar tareas
    cancelFilterButton.addEventListener('click', function () {
        closeFilterTaskForm();
    });


    // Ocultar el formulario cuando se hace clic fuera de él
    document.addEventListener('click', function (event) {
        const isInsideForm = filterTaskForm.contains(event.target); // Verifica si el clic fue dentro del formulario
        const isfilterTaskButton = document.getElementById('filter-task-button').contains(event.target);
        const isDateRangePicker = event.target.closest('.daterangepicker'); // Verifica si el clic fue dentro del Date Range Picker
        const isSelectedItem = event.target.closest('.selected-item'); // Verifica si el clic fue dentro de un item seleccionado

        // Verifica si el clic no es dentro del formulario, del botón, del Date Range Picker o de un ítem seleccionado
        if (!isInsideForm && !isfilterTaskButton && !isDateRangePicker && !isSelectedItem) {
            if (filterTaskForm.classList.contains('show')) {
                closeFilterTaskForm();
            }
        }
    });





    // Lógica para limpiar los campos del formulario de filtros
    clearFilterButton.addEventListener('click', function (e) {
        e.preventDefault(); // Evitar el comportamiento predeterminado del botón

        // Usar el método reset() para limpiar todos los campos del formulario
        filterTaskFormContent.reset();

        // Limpiar los usuarios seleccionados
        resetSelectedUsers();

        // Limpiar los campos ocultos que almacenan los IDs
        document.getElementById('filter-cliente-ids').value = '';
        document.getElementById('filter-asunto-ids').value = '';
        document.getElementById('filter-tipo-ids').value = '';
        document.getElementById('filter-user-ids').value = '';

        // Limpiar las visualizaciones de autocompletar
        document.getElementById('filter-cliente-input').value = '';
        document.getElementById('filter-asunto-input').value = '';
        document.getElementById('filter-tipo-input').value = '';

        // Limpiar los contenedores de seleccionados
        document.getElementById('filter-cliente-selected').innerHTML = '';
        document.getElementById('filter-asunto-selected').innerHTML = '';
        document.getElementById('filter-tipo-selected').innerHTML = '';

        // Si las listas de autocompletar están visibles, ocultarlas
        document.getElementById('filter-cliente-list').style.display = 'none';
        document.getElementById('filter-asunto-list').style.display = 'none';
        document.getElementById('filter-tipo-list').style.display = 'none';
        document.getElementById('filter-user-list').style.display = 'none';

        // Limpiar los checklists dinámicos (Estado, Subtipo, Facturable, Facturado)
        ['estado', 'subtipo', 'facturable', 'facturado'].forEach(field => {
            resetChecklistFilter(field);
        });

        // Opcional: Cerrar todas las listas desplegables
        document.querySelectorAll('.dropdown-list').forEach(list => {
            list.style.display = 'none';
        });


    });

    // Función para limpiar un checklist dinámico
    function resetChecklistFilter(fieldName) {
        const hiddenInput = document.getElementById(`filter-${fieldName}-ids`);
        const selectedContainer = document.getElementById(`filter-selected-${fieldName}s`);
        const checkboxes = document.querySelectorAll(`#filter-${fieldName}-list input[type="checkbox"]`);

        // Limpiar el campo oculto
        hiddenInput.value = '';

        // Desmarcar todos los checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        // Restablecer la visualización del checklist a su estado inicial
        selectedContainer.innerHTML = '';
        const placeholder = document.createElement('span');
        placeholder.textContent = 'Seleccionar...';
        placeholder.style.color = '#aaa';
        placeholder.style.fontStyle = 'italic';
        selectedContainer.appendChild(placeholder);
    }




    applyFilterButton.addEventListener('click', function (e) {
        e.preventDefault();
        loadFilteredTasks();
    });









    // Función para limpiar los usuarios seleccionados
    function resetSelectedUsers() {
        // Limpiar el contenedor de usuarios seleccionados
        const selectedUsersContainer = document.getElementById('filter-selected-users');
        selectedUsersContainer.innerHTML = '';  // Limpiar el contenido visual

        // Limpiar el campo oculto que contiene los IDs de los usuarios seleccionados
        const filterUserIdsInput = document.getElementById('filter-user-ids');
        filterUserIdsInput.value = '';  // Restablecer el valor oculto

        // Desmarcar todos los checkboxes de la lista de usuarios
        const userCheckboxes = document.querySelectorAll('#filter-user-list input[type="checkbox"]');
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = false;  // Desmarcar el checkbox
        });
    }



    // Autocompletar para cliente, asunto, tipo (igual que en add-task)
    // Autocompletar para Cliente
    setupAutocomplete(
        'filter-cliente-input',
        'filter-cliente-ids',
        'filter-cliente-list',
        clientesData,
        item => `${item.nombre_fiscal} (${item.nif})`, // Mostrar nombre y NIF
        item => item.nombre_fiscal, // Comparar con el nombre
        item => item.nif // Comparar también con el NIF
    );

    // Autocompletar para Asunto
    setupAutocomplete(
        'filter-asunto-input',
        'filter-asunto-ids',
        'filter-asunto-list',
        asuntosData,
        item => item.nombre, // Mostrar nombre
        item => item.nombre // Comparar con el nombre
    );

    // Autocompletar para Tipo
    setupAutocomplete(
        'filter-tipo-input',
        'filter-tipo-ids',
        'filter-tipo-list',
        tiposData,
        item => item.nombre, // Mostrar nombre
        item => item.nombre // Comparar con el nombre
    );


    document.addEventListener('click', function (e) {
        // Cerrar todas las listas de autocompletar si el clic no es en un input o en una lista de autocompletar
        closeAutocompleteListIfClickedOutside('filter-cliente-input', 'filter-cliente-list', e);
        closeAutocompleteListIfClickedOutside('filter-asunto-input', 'filter-asunto-list', e);
        closeAutocompleteListIfClickedOutside('filter-tipo-input', 'filter-tipo-list', e);
    });


    // Reutilizamos las funciones de autocompletar aquí
    function setupAutocomplete(inputId, hiddenInputId, listId, dataList, displayFormatter, itemSelector, extraMatchSelector = null) {
        const input = document.getElementById(inputId);
        const hiddenInput = document.getElementById(hiddenInputId);
        const list = document.getElementById(listId);
        const selectedContainer = document.getElementById(`${inputId.replace('-input', '')}-selected`);
        let selectedItems = []; // Array para almacenar las selecciones
        let selectedIndex = -1;

        function filterItems(query) {
            const filtered = dataList.filter(item => {
                const mainMatch = itemSelector(item).toLowerCase().includes(query.toLowerCase());
                const extraMatch = extraMatchSelector ? extraMatchSelector(item)?.toLowerCase().includes(query.toLowerCase()) : false;
                return mainMatch || extraMatch;
            });
            renderList(filtered);
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
                li.textContent = displayFormatter(item);
                li.setAttribute('data-id', item.id);
                li.classList.add('autocomplete-item');
                if (index === selectedIndex) {
                    li.classList.add('active');
                }
                li.addEventListener('click', () => selectItem(item));
                list.appendChild(li);
            });
        }

        function renderSelectedItems() {
            // Mantener el ancho del contenedor igual al del input
            selectedContainer.style.width = `${input.offsetWidth}px`;
            selectedContainer.style.overflowX = 'auto'; // Habilitar scroll horizontal
            selectedContainer.innerHTML = ''; // Limpiar el contenedor

            selectedItems.forEach(item => {
                const span = document.createElement('span');
                span.classList.add('selected-item');
                span.textContent = displayFormatter(item);

                const removeBtn = document.createElement('button');
                removeBtn.textContent = 'x';
                removeBtn.addEventListener('click', () => removeItem(item));
                span.appendChild(removeBtn);

                selectedContainer.appendChild(span);
            });

            updateHiddenInput(); // Actualizar el campo oculto
        }



        function updateHiddenInput() {
            hiddenInput.value = selectedItems
                .map(item => {
                    // Usa `nombre_fiscal` para clientes, `nombre` para otros
                    return item.id || item.nombre_fiscal || item.nombre || '';
                })
                .join(',');
            console.log(`Valores para el filtro: ${hiddenInput.value}`);
        }



        function selectItem(item) {
            if (!selectedItems.some(selected => selected.id === item.id)) {
                selectedItems.push(item);
                renderSelectedItems();
            }
            input.value = '';
            list.style.display = 'none';
            selectedIndex = -1;
        }

        function removeItem(item) {
            selectedItems = selectedItems.filter(selected => selected.id !== item.id);
            renderSelectedItems();
        }

        input.addEventListener('focus', function () {
            closeAllAutocompleteLists();
            selectedIndex = -1;
            filterItems(input.value);
        });

        input.addEventListener('input', function () {
            this.value = this.value.toUpperCase();
            filterItems(this.value);
        });

        input.addEventListener('keydown', function (e) {
            const items = document.querySelectorAll(`#${listId} .autocomplete-item`);
            if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedIndex >= 0 && selectedIndex < items.length) {
                    // Selección desde la lista desplegable
                    const selectedItem = dataList.find(item =>
                        displayFormatter(item) === items[selectedIndex].textContent
                    );
                    selectItem(selectedItem);
                } else if (input.value.trim()) {
                    // Si no hay selección, agregar el texto como ítem personalizado
                    const query = input.value.trim();
                    const newItem = {
                        id: null,
                        nombre_fiscal: inputId.includes('cliente') ? query : '', // Usar `nombre_fiscal` para clientes
                        nombre: !inputId.includes('cliente') ? query : '' // Usar `nombre` para otros
                    };

                    if (!selectedItems.some(item =>
                        item.nombre_fiscal === newItem.nombre_fiscal || item.nombre === newItem.nombre)) {
                        selectedItems.push(newItem); // Agregar ítem al array seleccionado
                        renderSelectedItems();
                    }
                    input.value = ''; // Limpiar el input
                    list.style.display = 'none'; // Ocultar la lista
                    selectedIndex = -1; // Reiniciar el índice
                }
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateActiveItem(items, selectedIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, 0);
                updateActiveItem(items, selectedIndex);
            }
        });



        function updateActiveItem(items, index) {
            items.forEach(item => item.classList.remove('active'));
            if (items[index]) {
                items[index].classList.add('active');
                items[index].scrollIntoView({ block: "nearest" });
            }
        }
    }


    function closeAutocompleteListIfClickedOutside(inputId, listId, event) {
        const input = document.getElementById(inputId);
        const list = document.getElementById(listId);

        if (!input.contains(event.target) && !list.contains(event.target)) {
            list.style.display = 'none';
        }
    }

    function closeAllAutocompleteLists() {
        document.getElementById('filter-cliente-list').style.display = 'none';
        document.getElementById('filter-asunto-list').style.display = 'none';
        document.getElementById('filter-tipo-list').style.display = 'none';
    }

    // Función para manejar la selección de usuarios en el formulario de filtrado
    const filterUserSelect = document.getElementById('filter-user-select');
    const filterUserList = document.getElementById('filter-user-list');
    const filterSelectedUsersContainer = document.getElementById('filter-selected-users');
    const filterUserIdsInput = document.getElementById('filter-user-ids');
    let filterSelectedUsers = [];
    let filterCurrentFocus = -1;




    // Obtener el ID del usuario en sesión y agregarlo como seleccionado
    const sessionUserId = document.getElementById('user-session-id').value;
    const sessionUserCheckbox = document.getElementById(`filter-user-${sessionUserId}`);

    if (sessionUserCheckbox) {
        sessionUserCheckbox.checked = true;
        const sessionUserName = sessionUserCheckbox.nextElementSibling.textContent;

        // Añadir el usuario en sesión a la lista de seleccionados al cargar la página
        filterSelectedUsers.push({ id: sessionUserId, name: sessionUserName });
        updateFilterSelectedUsersDisplay();
        updateFilterUserIdsInput();
    }

    // Actualiza el panel de información del filtro para mostrar el filtro del usuario en sesión
    updateFilterInfoPanel({
        usuario: sessionUserId  // Define el usuario en sesión como filtro activo
    });
    // Llama a la función inicialmente para cargar el título al abrir la página
    updateSelectedUserNamesFromFilterForm();

    // Mostrar el panel de información de filtros
    document.getElementById('filter-info-panel').classList.remove('hide');


    // Mostrar/ocultar la lista de usuarios al hacer clic o presionar Enter/Espacio
    filterUserSelect.addEventListener('click', toggleFilterUserList);
    filterUserSelect.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleFilterUserList();
        } else if (e.key === 'Escape') {
            filterUserList.style.display = 'none';
        }
    });

    // Función para alternar la visibilidad de la lista desplegable
    function toggleFilterUserList() {
        if (filterUserList.style.display === 'block') {
            filterUserList.style.display = 'none';
        } else {
            filterUserList.style.display = 'block';
            filterCurrentFocus = -1; // Reiniciar la selección cuando se vuelve a abrir
            focusNextFilterCheckbox(1); // Foco en el primer checkbox cuando se abre la lista
        }
    }

    // Manejar la selección de usuarios en el filtro
    filterUserList.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const userId = this.value;
            const userName = this.nextElementSibling.textContent;

            if (this.checked) {
                filterSelectedUsers.push({ id: userId, name: userName });
            } else {
                filterSelectedUsers = filterSelectedUsers.filter(user => user.id !== userId);
            }

            updateFilterSelectedUsersDisplay();
            updateFilterUserIdsInput();
            filterUserList.style.display = 'none'; // Cerrar la lista después de seleccionar un usuario
            filterUserSelect.focus(); // Devolver el foco al select principal
        });
    });

    // Función para actualizar la visualización de los usuarios seleccionados en el filtro
    function updateFilterSelectedUsersDisplay() {
        filterSelectedUsersContainer.innerHTML = '';
        filterSelectedUsers.forEach(user => {
            const span = document.createElement('span');
            span.textContent = user.name;
            filterSelectedUsersContainer.appendChild(span);
        });
    }

    // Función para actualizar el campo oculto con los IDs de usuarios seleccionados en el filtro
    function updateFilterUserIdsInput() {
        filterUserIdsInput.value = filterSelectedUsers.map(user => user.id).join(',');
    }

    // Cerrar la lista al perder el foco o al presionar Escape
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.custom-select') && e.target !== filterUserSelect) {
            filterUserList.style.display = 'none';
        }
    });

    // Función para navegar dentro de la lista con el teclado
    filterUserList.addEventListener('keydown', function (e) {
        const checkboxes = filterUserList.querySelectorAll('input[type="checkbox"]');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            focusNextFilterCheckbox(1);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            focusNextFilterCheckbox(-1);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (filterCurrentFocus >= 0 && filterCurrentFocus < checkboxes.length) {
                checkboxes[filterCurrentFocus].click(); // Simular un click para seleccionar el usuario
            }
        } else if (e.key === 'Escape') {
            filterUserList.style.display = 'none';
            filterUserSelect.focus(); // Volver el foco al select principal
        }
    });

    // Función para manejar el enfoque de los checkboxes en el filtro
    function focusNextFilterCheckbox(direction) {
        const checkboxes = filterUserList.querySelectorAll('input[type="checkbox"]');
        filterCurrentFocus = (filterCurrentFocus + direction + checkboxes.length) % checkboxes.length; // Calcular el índice
        checkboxes[filterCurrentFocus].focus();
    }





    // Checklists de los campos Estado, Subtipo, facturable y Facturado

    function initializeChecklistFilter(fieldName, isBoolean = false, defaultValues = []) {
        const selectElement = document.getElementById(`filter-${fieldName}-select`);
        const listElement = document.getElementById(`filter-${fieldName}-list`);
        const hiddenInput = document.getElementById(`filter-${fieldName}-ids`);
        const selectedContainer = document.getElementById(`filter-selected-${fieldName}s`);
        let selectedItems = [...defaultValues]; // Incluir los valores predeterminados
        let currentFocus = -1;

        // Marcar las casillas de los valores predeterminados
        if (defaultValues.length > 0) {
            defaultValues.forEach(value => {
                const checkbox = document.querySelector(`#filter-${fieldName}-list input[value="${value}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }

        // Configurar el campo oculto con los valores predeterminados
        hiddenInput.value = selectedItems.join(',');

        // Renderizar los valores predeterminados en el contenedor
        updateSelectedDisplay(selectedContainer, selectedItems, isBoolean);

        // Alternar visibilidad de la lista desplegable
        selectElement.addEventListener('click', function (event) {
            event.stopPropagation(); // Evitar que se cierre inmediatamente
            toggleListVisibility();
        });

        // Manejar selección de checkboxes
        const checkboxes = Array.from(listElement.querySelectorAll('input[type="checkbox"]')); // Convertir NodeList a Array
        checkboxes.forEach((checkbox, index) => {
            checkbox.dataset.index = index; // Asignar índice único al checkbox

            checkbox.addEventListener('change', function () {
                const value = isBoolean ? this.value === "1" : this.value;

                if (this.checked) {
                    if (!selectedItems.includes(value)) {
                        selectedItems.push(value);
                    }
                } else {
                    selectedItems = selectedItems.filter(item => item !== value);
                }

                hiddenInput.value = selectedItems.join(',');
                updateSelectedDisplay(selectedContainer, selectedItems, isBoolean);
            });

            // Manejar el foco de los checkboxes
            checkbox.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    focusNextCheckbox(1);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    focusNextCheckbox(-1);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    checkboxes[currentFocus].click(); // Simular clic para seleccionar/deseleccionar
                } else if (e.key === 'Escape') {
                    listElement.style.display = 'none';
                    selectElement.focus(); // Devolver el foco al select principal
                }
            });
        });

        // Alternar visibilidad de la lista
        function toggleListVisibility() {
            if (listElement.style.display === 'block') {
                listElement.style.display = 'none';
            } else {
                listElement.style.display = 'block';
                currentFocus = -1; // Reiniciar la selección cuando se vuelve a abrir
                focusNextCheckbox(1); // Foco en el primer checkbox al abrir
            }
        }

        // Actualizar visualización de ítems seleccionados
        function updateSelectedDisplay(container, items, isBoolean) {
            container.innerHTML = '';
            if (items.length === 0) {
                const placeholder = document.createElement('span');
                placeholder.textContent = 'Cualquiera...';
                placeholder.style.color = '#aaa';
                placeholder.style.fontStyle = 'italic';
                container.appendChild(placeholder);
            } else {
                items.forEach(item => {
                    const span = document.createElement('span');
                    span.textContent = isBoolean ? (item === true ? 'SI' : 'NO') : item;
                    span.style.backgroundColor = '#f0f0f0';
                    span.style.color = '#333';
                    span.style.padding = '3px 8px';
                    span.style.borderRadius = '15px';
                    span.style.fontSize = '12px';
                    span.style.lineHeight = '1.5';
                    span.style.border = '1px solid #ddd';
                    container.appendChild(span);
                });
            }
        }

        // Función para manejar el enfoque de los checkboxes
        function focusNextCheckbox(direction) {
            const checkboxes = Array.from(listElement.querySelectorAll('input[type="checkbox"]'));
            currentFocus = (currentFocus + direction + checkboxes.length) % checkboxes.length; // Calcular el índice
            checkboxes[currentFocus].focus();
        }

        // Cerrar la lista al hacer clic fuera
        document.addEventListener('click', function (e) {
            if (!e.target.closest(`#filter-${fieldName}-list`) && e.target !== selectElement) {
                listElement.style.display = 'none';
            }
        });

        // Al final de initializeChecklistFilter
        listElement.querySelectorAll('li').forEach(li => {
            li.addEventListener('click', function (e) {
                // Evitar interferir si se hace clic directamente en el <label> o el <input>
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'LABEL') {
                    return;
                }

                const checkbox = this.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked; // Alternar estado
                    checkbox.dispatchEvent(new Event('change')); // Disparar evento `change`
                }
            });
        });



    }

   

    // Inicializar checklists con valores predeterminados
    initializeChecklistFilter('estado', false, ['PENDIENTE', 'ENESPERA']);
    initializeChecklistFilter('subtipo');
    initializeChecklistFilter('facturado');
    initializeChecklistFilter('facturable', true);










});
