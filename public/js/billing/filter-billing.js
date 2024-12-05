document.addEventListener('DOMContentLoaded', function () {
    console.log('El script de filtro ha sido cargado correctamente.');

    // Obtener los datos de clientes, asuntos y tipos desde los atributos data
    const clientesData = JSON.parse(document.getElementById('clientes-data').getAttribute('data-clientes'));
    const asuntosData = JSON.parse(document.getElementById('asuntos-data').getAttribute('data-asuntos'));
    const tiposData = JSON.parse(document.getElementById('tipos-data').getAttribute('data-tipos'));

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

        // Verifica si el clic no es dentro del formulario o dentro del botón de abrir el formulario
        if (!isInsideForm && !isfilterTaskButton) {
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
        e.preventDefault(); // Evitar que se envíe el formulario al hacer clic en "Limpiar"

        // Usar el método reset() para limpiar todos los campos del formulario
        filterTaskFormContent.reset();

        // Limpiar los usuarios seleccionados
        resetSelectedUsers();

        // Limpiar los campos ocultos que almacenan los IDs
        document.getElementById('filter-cliente-id-input').value = '';
        document.getElementById('filter-asunto-id-input').value = '';
        document.getElementById('filter-tipo-id-input').value = '';
        document.getElementById('filter-user-ids').value = '';

        // Limpiar las visualizaciones de autocompletar
        document.getElementById('filter-cliente-input').value = '';
        document.getElementById('filter-asunto-input').value = '';
        document.getElementById('filter-tipo-input').value = '';

        // Si las listas de autocompletar están visibles, ocultarlas
        document.getElementById('filter-cliente-list').style.display = 'none';
        document.getElementById('filter-asunto-list').style.display = 'none';
        document.getElementById('filter-tipo-list').style.display = 'none';
        document.getElementById('filter-user-list').style.display = 'none';
    });


    function loadFilteredTasks(page = 1) {

        const filterData = {
            cliente: document.getElementById('filter-cliente-id-input').value || '', // Usar el ID del cliente
            asunto: document.getElementById('filter-asunto-input').value || '',
            tipo: document.getElementById('filter-tipo-input').value || '',
            subtipo: document.getElementById('filter-subtipo').value || '',
            estado: document.getElementById('filter-estado-ids').value || '', // Predeterminar a "PENDIENTE, ENESPERA"
            usuario: document.getElementById('filter-user-ids').value || '',
            fecha_inicio: document.getElementById('filter-fecha-inicio').value || '',
            fecha_vencimiento: document.getElementById('filter-fecha-vencimiento').value || '',
            fecha_planificacion: document.getElementById('filter-fecha-planificacion').value || '',
            tiempo_previsto: document.getElementById('filter-tiempo-previsto').value || '',
            tiempo_real: document.getElementById('filter-tiempo-real').value || '',

            // Filtros personalizados para Facturación
            facturable: document.getElementById('filter-facturable-ids').value || '', // Usar el campo oculto con los valores seleccionados
            facturado: document.getElementById('filter-facturado-ids').value || '', // Usar el campo oculto con los valores seleccionados
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
                    console.log('Datos enviados:', filterData);
                    console.log('Respuesta del servidor:', data);
                    updateTaskTable(data.filteredTasks);
                    updatePagination(data.pagination, loadFilteredTasks);  // Pasa loadFilteredTasks como argumento
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

        // Agregar filtros predeterminados para facturación
        //document.getElementById('filter-facturable').value = 'true';
        //document.getElementById('filter-facturado').value = 'No';

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


    let usersData = JSON.parse(document.getElementById('usuarios-data').getAttribute('data-usuarios'));

    // Función para actualizar el panel de filtros
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

                // Manejo especial para mostrar el nombre del cliente en lugar del ID
                if (key === 'cliente') {
                    const cliente = clientesData.find(cliente => cliente.id === parseInt(value));
                    p.textContent = `Cliente: ${cliente ? cliente.nombre_fiscal : 'Desconocido'}`;
                }
                else if (key === 'usuario') {
                    const userIds = value.split(',').map(id => parseInt(id)); // Convertir IDs en array de números
                    const userNames = userIds
                        .map(id => {
                            const usuario = usersData.find(usuario => usuario.id === id);
                            return usuario ? usuario.name : 'Desconocido';
                        })
                        .join(', '); // Combinar nombres de usuarios en una cadena

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
        'filter-cliente-id-input',
        'filter-cliente-list',
        clientesData,
        item => `${item.nombre_fiscal} (${item.nif})`,  // Mostrar nombre y NIF
        item => item.nombre_fiscal,  // Comparar con el nombre
        item => item.nif  // Comparar también con el NIF
    );

    // Autocompletar para Asunto
    setupAutocomplete('filter-asunto-input', 'filter-asunto-id-input', 'filter-asunto-list', asuntosData,
        item => item.nombre,
        item => item.nombre
    );

    // Autocompletar para Tipo
    setupAutocomplete('filter-tipo-input', 'filter-tipo-id-input', 'filter-tipo-list', tiposData,
        item => item.nombre,
        item => item.nombre
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
        let selectedIndex = -1;

        function filterItems(query) {
            const filtered = dataList.filter(item => {
                const mainMatch = itemSelector(item).toLowerCase().includes(query.toLowerCase());
                const extraMatch = extraMatchSelector ? extraMatchSelector(item)?.toLowerCase().includes(query.toLowerCase()) : false; // Verifica que extraMatchSelector(item) no sea null
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

        function selectItem(item) {
            input.value = displayFormatter(item);
            hiddenInput.value = item.id;
            list.style.display = 'none';
            selectedIndex = -1;
        }

        input.addEventListener('focus', function () {
            // Cerrar todas las listas de autocompletar cuando se hace foco en un nuevo campo
            closeAllAutocompleteLists();
            selectedIndex = -1;
            filterItems(input.value);
        });

        input.addEventListener('input', function () {
            this.value = this.value.toUpperCase();  // Convertir a mayúsculas
            hiddenInput.value = '';  // Limpiar el campo oculto
            filterItems(this.value);
        });

        input.addEventListener('keydown', function (e) {
            const items = document.querySelectorAll(`#${listId} .autocomplete-item`);
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
                        const selectedItem = dataList.find(item =>
                            displayFormatter(item) === items[selectedIndex].textContent
                        );
                        selectItem(selectedItem);
                    }
                }
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


    // Checklists de los campos Facturable y Facturado

    function initializeChecklistFilter(fieldName, isBoolean = false, defaultValues = []) {
        const selectElement = document.getElementById(`filter-${fieldName}-select`);
        const listElement = document.getElementById(`filter-${fieldName}-list`);
        const hiddenInput = document.getElementById(`filter-${fieldName}-ids`);
        const selectedContainer = document.getElementById(`filter-selected-${fieldName}s`);
        let selectedItems = [...defaultValues]; // Inicializar con valores predeterminados
        let currentFocus = -1;

        // Marcar los checkboxes correspondientes a los valores predeterminados
        const checkboxes = Array.from(listElement.querySelectorAll('input[type="checkbox"]')); // Convertir NodeList a Array
        checkboxes.forEach((checkbox, index) => {
            checkbox.dataset.index = index; // Asignar índice único al checkbox
            const value = isBoolean ? checkbox.value === "1" : checkbox.value;

            // Marcar los valores predeterminados
            if (defaultValues.includes(value)) {
                checkbox.checked = true;
            }

            // Manejar selección/deselección
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
        selectElement.addEventListener('click', function (event) {
            event.stopPropagation(); // Evitar que se cierre inmediatamente
            toggleListVisibility();
        });

        // Cerrar lista al hacer clic fuera
        document.addEventListener('click', function (e) {
            if (!e.target.closest(`#filter-${fieldName}-list`) && e.target !== selectElement) {
                listElement.style.display = 'none';
            }
        });

        // Función para mostrar u ocultar la lista
        function toggleListVisibility() {
            listElement.style.display = listElement.style.display === 'block' ? 'none' : 'block';

            if (listElement.style.display === 'block') {
                currentFocus = -1; // Reiniciar la selección cuando se vuelve a abrir
                focusNextCheckbox(1); // Foco en el primer checkbox al abrir
            }
        }

        // Actualizar visualización de ítems seleccionados
        function updateSelectedDisplay(container, items, isBoolean) {
            container.innerHTML = '';
            if (items.length === 0) {
                const placeholder = document.createElement('span');
                placeholder.textContent = 'Seleccionar...';
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
            currentFocus = (currentFocus + direction + checkboxes.length) % checkboxes.length; // Calcular el índice
            checkboxes[currentFocus].focus();
        }

        // Actualizar la visualización inicial con valores predeterminados
        hiddenInput.value = selectedItems.join(',');
        updateSelectedDisplay(selectedContainer, selectedItems, isBoolean);
    }


    initializeChecklistFilter('facturado', false, ['NO']); // Facturado predeterminado a "NO"
    initializeChecklistFilter('facturable', true, [true]); // Facturable predeterminado a true










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

            // Marcar "Todas" como activa al inicio
            if (dia.nombre === "Todas") {
                button.classList.add('active');
                // filtrarTareasPorPlanificacion(dia.fecha);
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
    function filtrarTareasPorPlanificacion(fecha) {
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
            cliente: document.getElementById('filter-cliente-id-input')?.value || '', // Usar el ID del cliente
            asunto: document.getElementById('filter-asunto-input')?.value || '',
            tipo: document.getElementById('filter-tipo-input')?.value || '',
            subtipo: document.getElementById('filter-subtipo')?.value || '',
            estado: document.getElementById('filter-estado-ids').value || '', // Predeterminar a "PENDIENTE, ENESPERA"
            usuario: document.getElementById('filter-user-ids')?.value || '',
            archivo: document.getElementById('filter-archivo')?.value || '',

            precio: document.getElementById('filter-precio')?.value || '',
            suplido: document.getElementById('filter-suplido')?.value || '',
            coste: document.getElementById('filter-coste')?.value || '',
            fecha_inicio: document.getElementById('filter-fecha-inicio')?.value || '',
            fecha_vencimiento: document.getElementById('filter-fecha-vencimiento')?.value || '',
            fecha_planificacion: fecha === "past" ? "past" : fecha, // Este valor viene del filtro rápido de planificación
            tiempo_previsto: document.getElementById('filter-tiempo-previsto')?.value || '',
            tiempo_real: document.getElementById('filter-tiempo-real')?.value || '',
            // Filtros por defecto para la vista de Facturación
            facturable: true,
            facturado: 'NO'
        };

        console.log('Datos de filtro:', filterData);

        // Actualizar el panel con los filtros actuales
        updateFilterInfoPanel(filterData);

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
                    console.log("Tareas filtradas recibidas:", data.filteredTasks);

                    updateTaskTable(data.filteredTasks); // Actualizar la tabla con las tareas filtradas
                    updatePagination(data.pagination, loadFilteredTasks);  // Pasar loadFilteredTasks para paginación si es necesario
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
    ['estado'].forEach(field => {
        initializeChecklistFilter(field);
    });

    // Inicializar valores predeterminados para Facturable
    const facturableDefault = ['1']; // "1" representa "Sí"
    const hiddenFacturableInput = document.getElementById('filter-facturable-ids');
    hiddenFacturableInput.value = facturableDefault.join(',');

    // Marcar las casillas de Facturable predeterminadas
    facturableDefault.forEach(facturable => {
        const checkbox = document.getElementById(`filter-facturable-${facturable}`);
        if (checkbox) {
            checkbox.checked = true;
        }
    });

    // Visualizar los valores seleccionados de Facturable
    const selectedFacturablesContainer = document.getElementById('filter-selected-facturables');
    facturableDefault.forEach(facturable => {
        const span = document.createElement('span');
        span.textContent = facturable === '1' ? 'Sí' : 'No';
        span.style.backgroundColor = '#f0f0f0';
        span.style.color = '#333';
        span.style.padding = '3px 8px';
        span.style.borderRadius = '15px';
        span.style.fontSize = '12px';
        span.style.lineHeight = '1.5';
        span.style.border = '1px solid #ddd';
        selectedFacturablesContainer.appendChild(span);
    });

    // Inicializar valores predeterminados para Facturado
    const facturadoDefault = ['NO']; // "NO" es el valor predeterminado
    const hiddenFacturadoInput = document.getElementById('filter-facturado-ids');
    hiddenFacturadoInput.value = facturadoDefault.join(',');

    // Marcar las casillas de Facturado predeterminadas
    facturadoDefault.forEach(facturado => {
        const checkbox = document.getElementById(`filter-facturado-${facturado.toLowerCase()}`);
        if (checkbox) {
            checkbox.checked = true;
        }
    });

    // Visualizar los valores seleccionados de Facturado
    const selectedFacturadosContainer = document.getElementById('filter-selected-facturados');
    facturadoDefault.forEach(facturado => {
        const span = document.createElement('span');
        span.textContent = facturado;
        span.style.backgroundColor = '#f0f0f0';
        span.style.color = '#333';
        span.style.padding = '3px 8px';
        span.style.borderRadius = '15px';
        span.style.fontSize = '12px';
        span.style.lineHeight = '1.5';
        span.style.border = '1px solid #ddd';
        selectedFacturadosContainer.appendChild(span);
    });



});

