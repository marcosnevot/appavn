document.addEventListener('DOMContentLoaded', function () {
    console.log('El script de filtro ha sido cargado correctamente.');

    // Obtener los datos de clientes, asuntos y tipos desde los atributos data
    clientesData = JSON.parse(document.getElementById('clientes-data').getAttribute('data-clientes'));
    asuntosData = JSON.parse(document.getElementById('asuntos-data').getAttribute('data-asuntos'));
    tiposData = JSON.parse(document.getElementById('tipos-data').getAttribute('data-tipos'));

    const filterTaskButton = document.getElementById('filter-task-button');
    const filterTaskForm = document.getElementById('filter-task-form');
    const filterTaskFormContent = document.getElementById('filter-task-form-content');

    const applyFilterButton = document.getElementById('apply-filter-button');
    const cancelFilterButton = document.getElementById('cancel-filter-button');
    const clearFilterButton = document.getElementById('clear-filter-button');



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



    // Función para cerrar el formulario
    function closeFilterTaskForm() {
        filterTaskForm.classList.remove('show');
        filterTaskForm.classList.add('hide');
        setTimeout(() => {
            filterTaskForm.style.display = 'none';
        }, 400);

    }

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


    function loadFilteredTasks(page = 1, sortKey = 'fecha_planificacion', sortDirection = 'asc') {

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
            sortKey: sortKey, // Agregar el criterio de orden
            sortDirection: sortDirection // Agregar la dirección de orden
        };

        console.log('Datos de filtro:', filterData);
        window.currentFilters = filterData; // Actualiza los filtros activos en la variable global

        // Actualizar el panel con los filtros actuales
        updateFilterInfoPanel(filterData);

        // Realizar la solicitud al servidor para filtrar las tareas
        fetch(`/tareas/filtrar?page=${page}`, {  // <-- Asegúrate de pasar el número de página
            method: 'POST',
            body: JSON.stringify(filterData),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateTaskTable(data.filteredTasks);
                    updatePagination(data.pagination, (newPage) => loadFilteredTasks(newPage, sortKey, sortDirection)); // Paginación con filtros y orden
                    updateHoursSummaryFromTotals(data.totalTiempoPrevisto, data.totalTiempoReal);
                    resetFiltroRapidoPlanificacion();
                    updateSelectedUserNamesFromFilterForm();
                    closeFilterTaskForm();
                } else {
                    console.error('Error al filtrar tareas:', data.message);
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error.message);
            });
    }

    applyFilterButton.addEventListener('click', function (e) {
        e.preventDefault();
        loadFilteredTasks();
    });

    // Función para restablecer el filtro rápido de planificación
    function resetFiltroRapidoPlanificacion() {
        // Remover la clase 'active' de todos los botones
        document.querySelectorAll('.btn-filter-planificacion').forEach(btn => btn.classList.remove('active'));

        // Marcar el botón de "Todas" como activo
        const botonTodas = document.querySelector('.btn-filter-planificacion[data-fecha=""]');
        if (botonTodas) {
            botonTodas.classList.add('active');
        }
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

    // Inicializar estados predeterminados
    const estadoDefault = ['PENDIENTE', 'ENESPERA']; // Valores predeterminados
    const hiddenEstadoInput = document.getElementById('filter-estado-ids');
    hiddenEstadoInput.value = estadoDefault.join(',');

    // Marcar las casillas de los estados predeterminados
    estadoDefault.forEach(estado => {
        const checkbox = document.getElementById(`filter-estado-${estado.toLowerCase()}`);
        if (checkbox) {
            checkbox.checked = true;
        }
    });

    // Inicializar visualización de estados seleccionados
    const selectedEstadosContainer = document.getElementById('filter-selected-estados');
    estadoDefault.forEach(estado => {
        const span = document.createElement('span');
        span.textContent = estado;
        span.style.backgroundColor = '#f0f0f0';
        span.style.color = '#333';
        span.style.padding = '3px 8px';
        span.style.borderRadius = '15px';
        span.style.fontSize = '12px';
        span.style.lineHeight = '1.5';
        span.style.border = '1px solid #ddd';
        selectedEstadosContainer.appendChild(span);
    });


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
                .map(item => item.id || item.nombre) // Incluir IDs o nombres según estén disponibles
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
                    // Si hay un elemento seleccionado en la lista
                    const selectedItem = dataList.find(item =>
                        displayFormatter(item) === items[selectedIndex].textContent
                    );
                    selectItem(selectedItem);
                } else if (input.value.trim()) {
                    // Si no hay selección pero hay texto en el input
                    const query = input.value.trim();
                    if (!selectedItems.some(item => item.nombre === query)) {
                        selectedItems.push({ id: null, nombre: query }); // Agregar texto como un "nombre" sin ID
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

    function initializeChecklistFilter(fieldName, isBoolean = false) {
        const selectElement = document.getElementById(`filter-${fieldName}-select`);
        const listElement = document.getElementById(`filter-${fieldName}-list`);
        const hiddenInput = document.getElementById(`filter-${fieldName}-ids`);
        const selectedContainer = document.getElementById(`filter-selected-${fieldName}s`);
        let selectedItems = [];
        let currentFocus = -1;

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
                    selectedItems.push(value);
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
                    span.textContent = isBoolean ? (item === true ? 'Sí' : 'No') : item;
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
    }

    // Inicializar checklists
    ['estado', 'subtipo', 'facturado'].forEach(field => {
        initializeChecklistFilter(field);
    });

    // Inicializar el campo booleano "facturable"
    initializeChecklistFilter('facturable', true);






    $(document).ready(function () {
        const today = new Date().toISOString().split('T')[0];

        // Inicialización para el campo del formulario (Filter Form)
        const formFechaPlanificacionInput = $('#filter-fecha-planificacion');

        formFechaPlanificacionInput.daterangepicker({
            autoUpdateInput: true,
            startDate: today,
            endDate: today,
            locale: {
                format: 'YYYY-MM-DD',
                separator: ' - ',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Personalizado',
            },
            drops: 'up', // Mostrar por encima
        });



        formFechaPlanificacionInput.on('apply.daterangepicker', function (ev, picker) {
            ev.stopPropagation(); // Evitar que el formulario se cierre
            $(this).val(`${picker.startDate.format('YYYY-MM-DD')} - ${picker.endDate.format('YYYY-MM-DD')}`);
            // Las fechas seleccionadas ya se reflejan en el campo input del formulario

        });

        formFechaPlanificacionInput.on('cancel.daterangepicker', function () {
            $(this).val(''); // Limpiar el campo si se cancela la selección
        });

        // Inicializar el campo vacío
        formFechaPlanificacionInput.val(''); // Campo sin valor inicial (null en términos funcionales)
    });




    // Filtro según la planificación
    const planificacionFilterContainer = document.getElementById('planificacion-filter-buttons');

    // Función para obtener los días de la semana a partir de hoy
    function obtenerDiasFiltrado() {
        const diasSemana = ["L", "M", "X", "J", "V"];
        const hoy = new Date();
        const hoyIndex = hoy.getDay();
        const diasRestantes = [];

        diasRestantes.push({ nombre: "Todas", fecha: "" }); // Opción para ver todas las tareas

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
    // Bandera para evitar ejecución automática al generar botones
    let filtroInicializado = false;

    // Función para generar los botones de filtro de planificación
    function generarBotonesFiltroPlanificacion() {
        planificacionFilterContainer.innerHTML = ""; // Limpiar botones previos
        const diasRestantes = obtenerDiasFiltrado();

        diasRestantes.forEach(dia => {
            const button = document.createElement('button');
            button.type = 'button';
            button.classList.add('btn-filter-planificacion');
            button.textContent = dia.nombre;
            button.setAttribute('data-fecha', dia.fecha);
            button.onclick = () => filtrarTareasPorPlanificacion(dia.fecha);

            planificacionFilterContainer.appendChild(button);

            // Marcar "Todas" como activa al inicio si no está inicializado
            if (dia.nombre === "Todas" && !filtroInicializado) {
                button.classList.add('active');
                filtroInicializado = true; // Evitar que se llame de nuevo
                // filtrarTareasPorPlanificacion(dia.fecha); // Ejecutar solo una vez al inicio
            }
        });

        // Crear el botón de "Pasadas"
        const botonPasadas = document.createElement('button');
        botonPasadas.type = 'button';
        botonPasadas.classList.add('btn-filter-planificacion', 'btn-pasadas'); // Añadimos una clase específica
        botonPasadas.textContent = 'Pasadas';
        botonPasadas.setAttribute('data-fecha', 'past');
        botonPasadas.onclick = () => filtrarTareasPorPlanificacion('past');
        planificacionFilterContainer.appendChild(botonPasadas);
    }


    // Función para gestionar el filtrado de tareas
    function filtrarTareasPorPlanificacion(fecha, sortKey = 'fecha_planificacion', sortDirection = 'asc') {
        console.log('filtrarTareasPorPlanificacion llamada desde:', new Error().stack);

        // Actualizar la interfaz de botones
        document.querySelectorAll('.btn-filter-planificacion').forEach(btn => {
            btn.classList.remove('active', 'active-red'); // Limpiar clases activas y rojas
        });

        const selectedButton = document.querySelector(`.btn-filter-planificacion[data-fecha="${fecha}"]`);
        if (selectedButton) {
            if (fecha === 'past') {
                selectedButton.classList.add('active-red'); // Usar una clase especial para "Pasadas"
            } else {
                selectedButton.classList.add('active');

            }
        }

        // Preparar los datos de filtro, combinando los filtros rápidos con los filtros del formulario principal
        const filterData = {
            cliente: document.getElementById('filter-cliente-ids').value || '', // Usar los IDs de clientes seleccionados
            asunto: document.getElementById('filter-asunto-ids').value || '',  // Usar los IDs/nombres de asuntos seleccionados
            tipo: document.getElementById('filter-tipo-ids').value || '',
            subtipo: document.getElementById('filter-subtipo-ids').value || '', // Usar el campo oculto con los valores seleccionados
            estado: document.getElementById('filter-estado-ids').value || '', // Predeterminar a "PENDIENTE, ENESPERA"
            usuario: document.getElementById('filter-user-ids')?.value || '',
            archivo: document.getElementById('filter-archivo')?.value || '',
            facturable: document.getElementById('filter-facturable-ids').value || '', // Usar el campo oculto con los valores seleccionados
            facturado: document.getElementById('filter-facturado-ids').value || '', // Usar el campo oculto con los valores seleccionados
            precio: document.getElementById('filter-precio')?.value || '',
            suplido: document.getElementById('filter-suplido')?.value || '',
            coste: document.getElementById('filter-coste')?.value || '',
            fecha_inicio: document.getElementById('filter-fecha-inicio')?.value || '',
            fecha_vencimiento: document.getElementById('filter-fecha-vencimiento')?.value || '',
            fecha_planificacion: fecha === "past" ? "past" : fecha, // Este valor viene del filtro rápido de planificación
            tiempo_previsto: document.getElementById('filter-tiempo-previsto')?.value || '',
            tiempo_real: document.getElementById('filter-tiempo-real')?.value || '',
            sortKey: sortKey, // Clave para ordenar
            sortDirection: sortDirection // Dirección de orden
        };

        // console.log('Datos de filtro:', filterData);
        window.currentFilters = filterData;
        // Actualizar el panel con los filtros actuales
        updateFilterInfoPanel(filterData);
        console.log('Parámetros enviados al servidor:', {
            ...filterData,
            sortKey: 'fecha_planificacion',
            sortDirection: 'asc'
        });

        // Realizar la solicitud al servidor para filtrar las tareas
        fetch(`/tareas/filtrar`, {
            method: 'POST',
            body: JSON.stringify(filterData),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // console.log("Tareas filtradas recibidas:", data.filteredTasks);

                    updateTaskTable(data.filteredTasks); // Actualizar la tabla con las tareas filtradas
                    updatePagination(data.pagination, loadFilteredTasks);  // Pasar loadFilteredTasks para paginación si es necesario
                    updateHoursSummaryFromTotals(data.totalTiempoPrevisto, data.totalTiempoReal);
                } else {
                    console.error('Error al filtrar tareas:', data.message);
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error.message);
            });
    }


    // Generar los botones de filtro de planificación al cargar la página
    generarBotonesFiltroPlanificacion();



    // Filtros rápidos en encabezados

    // Añadir listeners a los encabezados de tabla con `data-sort-key`
    const headers = document.querySelectorAll('th[data-field]');

    headers.forEach(header => {
        header.addEventListener('contextmenu', function (event) {
            event.preventDefault();
            const field = header.getAttribute('data-field'); // Toma solo data-field
            if (!field) {
                console.error('El encabezado no tiene el atributo data-field.');
                return;
            }

            const dropdownList = document.querySelector(`#${field}-list`);
            const inputField = document.querySelector(`#filter-task-form [name="${field}"]`);
            const autocompleteContainer = document.querySelector(`#filter-${field}-input`)?.closest('.autocomplete');

            if (autocompleteContainer) {
                const dataList = getAutocompleteData(field); // Usa data-field para datos
                showAutocompleteField(header, autocompleteContainer, dataList, field);
            } else if (dropdownList) {
                showDropdownList(header, dropdownList, field);
            } else if (inputField) {
                showInputField(header, inputField);
            } else {
                console.error(`No se encontró ningún elemento asociado para: ${field}`);
            }
        });
    });


    // Función para obtener los datos del autocompletar según el campo
    function getAutocompleteData(field) {
        if (field === 'cliente') return clientesData; // Clientes
        if (field === 'asunto') return asuntosData;   // Asuntos
        if (field === 'tipo') return tiposData;       // Tipos
        console.error(`No se encontró un conjunto de datos para el campo: ${field}`);
        return [];
    }







    function showDropdownList(header, dropdownList, field) {
        hideAllDropdownLists(); // Ocultar otras listas desplegadas

        // Clonar visualmente la lista desplegable
        const mirroredList = dropdownList.cloneNode(true);
        mirroredList.id = `${dropdownList.id}-mirrored`; // Evitar conflictos de ID
        mirroredList.style.position = 'absolute';
        mirroredList.style.display = 'block';
        mirroredList.classList.add('autocomplete-container'); // Asegurar que tenga la clase base

        // Ajustar posición y ancho basado en el encabezado
        const updatePosition = () => {
            const rect = header.getBoundingClientRect();
            mirroredList.style.top = `${rect.bottom + window.scrollY - 1}px`;
            mirroredList.style.left = `${rect.left + window.scrollX - 25}px`; // Desplazar 10px a la izquierda
            mirroredList.style.width = `${rect.width + 50}px`; // Incrementar ancho por 20px
        };
        updatePosition();

        const scrollContainer = document.querySelector('.table-container'); // Contenedor de scroll

        // Cerrar desplegable al hacer scroll
        const handleScroll = () => {
            if (document.body.contains(mirroredList)) {
                document.body.removeChild(mirroredList);
            }
            scrollContainer.removeEventListener('scroll', handleScroll);
        };
        scrollContainer.addEventListener('scroll', handleScroll);

        // Añadir al documento
        document.body.appendChild(mirroredList);

        // Activar la animación
        setTimeout(() => {
            mirroredList.classList.add('show');
        }, 10); // Retraso mínimo para que CSS tome el cambio

        // Manejar las selecciones
        const checkboxes = mirroredList.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const originalCheckbox = dropdownList.querySelector(`#${checkbox.id}`);
                if (originalCheckbox) {
                    originalCheckbox.checked = checkbox.checked; // Sincronizar estado del checkbox
                }
                updateHiddenField(field, dropdownList);
                applyFilterOnChange(); // Activar el filtrado automáticamente
            });
            // Manejar clic en el texto asociado
            const label = mirroredList.querySelector(`label[for="${checkbox.id}"]`);
            if (label) {
                label.addEventListener('click', function (event) {
                    event.preventDefault(); // Evitar comportamiento predeterminado
                    checkbox.checked = !checkbox.checked; // Alternar el estado del checkbox
                    checkbox.dispatchEvent(new Event('change')); // Activar el evento `change`
                });
            }
        });

        // Manejar clic fuera con verificación
        setTimeout(() => {
            document.addEventListener('click', function handleClickOutside(event) {
                if (
                    !mirroredList.contains(event.target) &&
                    !header.contains(event.target)
                ) {
                    if (document.body.contains(mirroredList)) {
                        mirroredList.classList.remove('show');
                        setTimeout(() => {
                            document.body.removeChild(mirroredList);
                        }, 100);
                    }
                    document.removeEventListener('click', handleClickOutside);
                }
            });
        }, 0);
    }

    function showInputField(header, inputField) {
        hideAllDropdownLists(); // Ocultar cualquier lista desplegada

        // Clonar visualmente el input
        const inputClone = inputField.cloneNode(true);
        inputClone.id = `${inputField.id}-mirrored`; // Evitar conflictos de ID
        inputClone.style.position = 'absolute';
        inputClone.classList.add('autocomplete-container'); // Asegurar que tenga la clase base


        // Ajustar posición y ancho basado en el encabezado
        const updatePosition = () => {
            const rect = header.getBoundingClientRect();
            inputClone.style.top = `${rect.bottom + window.scrollY - 1}px`;
            inputClone.style.left = `${rect.left + window.scrollX - 25}px`;
            inputClone.style.width = `${rect.width + 50}px`; // Ajustar al ancho del encabezado
            inputClone.style.height = '50px';

        };
        updatePosition();

        const scrollContainer = document.querySelector('.table-container'); // Contenedor de scroll

        // Cerrar input al hacer scroll
        const handleScroll = () => {
            if (document.body.contains(inputClone)) {
                document.body.removeChild(inputClone);
            }
            scrollContainer.removeEventListener('scroll', handleScroll);
        };
        scrollContainer.addEventListener('scroll', handleScroll);

        // Añadir al documento
        document.body.appendChild(inputClone);

        // Activar la animación
        setTimeout(() => {
            inputClone.classList.add('show');
        }, 10); // Retraso mínimo para que CSS tome el cambio

        // Si el input tiene asociado un daterangepicker, inicializarlo en el input clonado
        if (inputField.id === 'filter-fecha-planificacion') {
            $(inputClone).daterangepicker({
                autoUpdateInput: true,
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: ' - ',
                    applyLabel: 'Aplicar',
                    cancelLabel: 'Cancelar',
                },
                drops: 'down',
            });

            // Manejar la selección de fechas
            $(inputClone).on('apply.daterangepicker', function (ev, picker) {
                ev.stopPropagation(); // Evitar cierre prematuro
                inputField.value = `${picker.startDate.format('YYYY-MM-DD')} - ${picker.endDate.format('YYYY-MM-DD')}`;
                applyFilterOnChange(); // Activar el filtro automáticamente
            });

            // Manejar la cancelación
            $(inputClone).on('cancel.daterangepicker', function () {
                inputField.value = ''; // Limpiar el campo original
                applyFilterOnChange(); // Activar el filtro automáticamente
            });

            // Inicializar valor vacío
            $(inputClone).val('');
        }

        // Sincronizar valores en tiempo real
        inputClone.addEventListener('input', function () {
            inputField.value = inputClone.value; // Actualizar el valor del input original
            applyFilterOnChange(); // Activar el filtrado automáticamente
        });

        // Manejar clic fuera con verificación
        setTimeout(() => {
            document.addEventListener('click', function handleClickOutside(event) {
                if (
                    !inputClone.contains(event.target) &&
                    !header.contains(event.target) &&
                    !event.target.closest('.daterangepicker') // Prevenir cierre al interactuar con el calendario
                ) {
                    if (document.body.contains(inputClone)) {
                        inputClone.classList.remove('show');
                        setTimeout(() => {
                            document.body.removeChild(inputClone);
                        }, 100);
                    }
                    $(inputClone).data('daterangepicker')?.remove(); // Eliminar instancia de daterangepicker
                    scrollContainer.removeEventListener('scroll', handleScroll);
                    document.removeEventListener('click', handleClickOutside);
                }
            });
        }, 0);

        inputClone.focus(); // Foco automático en el input clonado
    }

    function showAutocompleteField(header, autocompleteContainer, dataList, field) {
        console.log('Autocompletar detectado');
        hideAllDropdownLists(); // Ocultar otras listas desplegadas

        // Clonar el contenedor completo del autocompletar
        const clonedContainer = autocompleteContainer.cloneNode(true);
        clonedContainer.id = `${autocompleteContainer.id}-mirrored`; // Evitar conflictos de ID
        clonedContainer.classList.add('autocomplete-container'); // Asegurar que tenga la clase base
        clonedContainer.style.position = 'absolute';

        // Eliminar el div de `selected-items-container` del clon
        const selectedContainerClone = clonedContainer.querySelector('.selected-items-container');
        if (selectedContainerClone) {
            clonedContainer.removeChild(selectedContainerClone);
        }

        // Ajustar posición y ancho basado en el encabezado
        const updatePosition = () => {
            const rect = header.getBoundingClientRect();
            clonedContainer.style.top = `${rect.bottom + window.scrollY - 5}px`;
            clonedContainer.style.left = `${rect.left + window.scrollX - 10}px`;
            clonedContainer.style.width = `${rect.width + 50}px`; // Ajustar al ancho del encabezado

        };

        updatePosition();

        const scrollContainer = document.querySelector('.table-container'); // Contenedor de scroll
        // Cerrar input al hacer scroll
        const handleScroll = () => {
            if (document.body.contains(clonedContainer)) {
                document.body.removeChild(clonedContainer);
            }
            scrollContainer.removeEventListener('scroll', handleScroll);
        };
        scrollContainer.addEventListener('scroll', handleScroll);

        // Añadir al documento
        document.body.appendChild(clonedContainer);

        // Activar la animación
        setTimeout(() => {
            clonedContainer.classList.add('show');
        }, 10); // Retraso mínimo para que CSS tome el cambio

        const input = clonedContainer.querySelector('.autocomplete-input');
        const list = clonedContainer.querySelector('.autocomplete-list');
        const hiddenInput = clonedContainer.querySelector('input[type="hidden"]'); // Campo oculto del contenedor clonado
        list.innerHTML = ''; // Limpiar cualquier lista previa

        // Definir la propiedad según el campo
        const property = getAutocompleteProperty(field);
        if (!property) {
            console.error(`No se puede proceder con el autocompletar para el campo: ${field}`);
            return;
        }

        // Mapeo de identificadores para optimizar la lógica
        const fieldMapping = {
            cliente: { hidden: '#filter-cliente-ids', visible: '#filter-cliente-input', selected: '#filter-cliente-selected' },
            asunto: { hidden: '#filter-asunto-ids', visible: '#filter-asunto-input', selected: '#filter-asunto-selected' },
            tipo: { hidden: '#filter-tipo-ids', visible: '#filter-tipo-input', selected: '#filter-tipo-selected' }
        };

        // Manejar la entrada del usuario
        input.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            console.log('Valor del input clonado:', query);

            if (!query) {
                // Ocultar la lista y contraer el contenedor
                list.style.display = 'none';
                clonedContainer.style.minHeight = '50px'; // Contraer si no hay resultados

                // Restablecer campos ocultos y visibles dinámicamente
                Object.keys(fieldMapping).forEach(field => {
                    if (input.id.includes(field)) {
                        const { hidden, visible, selected } = fieldMapping[field];

                        // Restablecer campo oculto
                        const mainHiddenInputField = document.querySelector(hidden);
                        if (mainHiddenInputField) {
                            mainHiddenInputField.value = '';
                            console.log(`Campo oculto principal restablecido: ${mainHiddenInputField.name}`);
                        }

                        // Restablecer campo visible
                        const mainVisibleInputField = document.querySelector(visible);
                        if (mainVisibleInputField) {
                            mainVisibleInputField.value = '';
                            console.log(`Campo visible principal restablecido: ${mainVisibleInputField.id}`);
                        }

                        // Limpiar el contenedor de "selected-items" del formulario original
                        const selectedContainer = document.querySelector(selected);
                        if (selectedContainer) {
                            selectedContainer.innerHTML = ''; // Limpiar cualquier selección previa
                            console.log(`Selected items limpiados en: ${selected}`);
                        }
                    }
                });

                // Aplicar el filtro para mostrar todos los resultados
                applyFilterOnChange();
                return;
            }

            // Filtrar y renderizar resultados
            const filteredData = dataList.filter(item =>
                item && item[property] && item[property].toLowerCase().includes(query)
            );
            renderAutocompleteList(list, filteredData, property, input, hiddenInput, clonedContainer);
        });




        // Manejar clic fuera con verificación
        setTimeout(() => {
            document.addEventListener('click', function handleClickOutside(event) {
                if (
                    !clonedContainer.contains(event.target) &&
                    !header.contains(event.target)
                ) {
                    if (document.body.contains(clonedContainer)) {
                        clonedContainer.classList.remove('show');
                        setTimeout(() => {
                            document.body.removeChild(clonedContainer);
                        }, 100);
                    }
                    document.removeEventListener('click', handleClickOutside);
                    scrollContainer.removeEventListener('scroll', handleScroll);
                    document.removeEventListener('click', handleClickOutside);
                }
            });
        }, 0);

        input.focus(); // Foco automático en el input clonado
    }



    function renderAutocompleteList(list, data, property, inputField, hiddenInputField, autocompleteContainer) {
        list.innerHTML = ''; // Limpia la lista
        if (data.length === 0) {
            list.style.display = 'none';
            if (autocompleteContainer) {
                autocompleteContainer.style.maxHeight = '50px'; // Contraer si no hay resultados
            }
            return;
        }

        // Crear y agregar elementos a la lista
        data.forEach(item => {
            if (item && item[property]) {
                const li = document.createElement('li');
                li.textContent = item[property]; // Mostrar el valor de la propiedad dinámica
                li.classList.add('autocomplete-item');
                li.style.padding = '8px'; // Espaciado para los elementos

                li.addEventListener('click', () => {
                    // Mapeo de identificadores para optimizar la lógica
                    const fieldMapping = {
                        cliente: { hidden: '#filter-cliente-ids', visible: '#filter-cliente-input', selected: '#filter-cliente-selected' },
                        asunto: { hidden: '#filter-asunto-ids', visible: '#filter-asunto-input', selected: '#filter-asunto-selected' },
                        tipo: { hidden: '#filter-tipo-ids', visible: '#filter-tipo-input', selected: '#filter-tipo-selected' }
                    };

                    // Identificar el campo correspondiente
                    Object.keys(fieldMapping).forEach(field => {
                        if (inputField.id.includes(field)) {
                            const { hidden, visible, selected } = fieldMapping[field];

                            // Reflejar la selección en el contenedor de "selected items"
                            const selectedContainer = document.querySelector(selected);
                            if (selectedContainer) {
                                selectedContainer.innerHTML = ''; // Limpiar cualquier selección previa
                                const selectedItem = document.createElement('div');
                                selectedItem.classList.add('selected-item');
                                selectedItem.textContent = item[property];

                                // Botón para eliminar el ítem seleccionado
                                const removeBtn = document.createElement('button');
                                removeBtn.textContent = '×';
                                removeBtn.classList.add('remove-item');
                                removeBtn.addEventListener('click', () => {
                                    selectedContainer.innerHTML = ''; // Limpiar el contenedor
                                    const mainHiddenInputField = document.querySelector(hidden);
                                    if (mainHiddenInputField) {
                                        mainHiddenInputField.value = ''; // Limpiar el campo oculto
                                    }
                                });

                                selectedItem.appendChild(removeBtn);
                                selectedContainer.appendChild(selectedItem);
                            }

                            // Actualizar el campo oculto con el ID del elemento seleccionado
                            const mainHiddenInputField = document.querySelector(hidden);
                            if (mainHiddenInputField) {
                                mainHiddenInputField.value = item.id || '';
                                console.log(`Campo oculto actualizado: ${mainHiddenInputField.name} = ${mainHiddenInputField.value}`);
                            }

                            // Mantener funcionalidad de búsqueda
                            const mainVisibleInputField = document.querySelector(visible);
                            if (mainVisibleInputField) {
                                mainVisibleInputField.value = item[property];
                                applyFilterOnChange(); // Aplicar el filtro inmediatamente
                            }
                        }
                    });

                    // Eliminar el contenedor después de la selección
                    if (autocompleteContainer && autocompleteContainer.parentNode) {
                        autocompleteContainer.parentNode.removeChild(autocompleteContainer);
                    }
                    applyFilterOnChange(); // Aplicar el filtro inmediatamente

                    // Limpiar el input visible
                    inputField.value = '';

                    // Ocultar la lista
                    list.style.display = 'none';
                });

                list.appendChild(li);
            }
        });

        // Mostrar lista
        list.style.display = 'block';
        list.style.marginTop = '5px';
        list.style.marginLeft = '-5px';

        // Ajustar altura dinámica del contenedor
        if (autocompleteContainer) {
            autocompleteContainer.style.minHeight = '200px'; // Fijar altura al escribir
            autocompleteContainer.style.overflowY = 'auto'; // Activar scroll si es necesario
        }
    }

    // Mapeo de propiedades según el campo
    function getAutocompleteProperty(field) {

        const fieldToPropertyMap = {
            cliente: 'nombre_fiscal',
            asunto: 'nombre',
            tipo: 'nombre'
        };

        if (!fieldToPropertyMap[field]) {
            console.error(`No se encontró una propiedad para el campo: ${field}`);
            return null; // O lanza un error si quieres forzar la definición
        }

        return fieldToPropertyMap[field];
    }





    function hideAllDropdownLists() {
        const mirroredLists = document.querySelectorAll('[id$="-mirrored"]'); // Identificar todas las listas clonadas
        mirroredLists.forEach(list => {
            list.remove(); // Eliminar clones del DOM
        });
    }

    function updateHiddenField(field, dropdownList) {
        const hiddenField = document.querySelector(`#${field}-ids`); // Buscar el campo oculto relacionado
        const selectedValues = [];

        // Recopilar valores seleccionados
        const checkboxes = dropdownList.querySelectorAll('input[type="checkbox"]:checked');
        checkboxes.forEach(checkbox => {
            // Si el campo es "facturable", convertir 1/0 a true/false, de lo contrario usar el valor original
            const value = field === "facturable" ? (checkbox.value === "1" ? true : false) : checkbox.value;
            selectedValues.push(value);
        });

        // Actualizar el campo oculto con los valores seleccionados
        if (hiddenField) {
            hiddenField.value = selectedValues.join(','); // Guardar valores como lista separada por comas
            console.log(`Campo oculto actualizado (${field}-ids):`, hiddenField.value); // Verifica el valor
        }
    }


    function applyFilterOnChange() {
        console.log("Aplicando filtro automáticamente.");
        loadFilteredTasks(); // Invocar la función existente para actualizar la tabla
    }
});
