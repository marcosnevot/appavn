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
            estado: document.getElementById('filter-estado').value || '',
            usuario: document.getElementById('filter-user-ids').value || '',
            archivo: document.getElementById('filter-archivo').value || '',
            facturable: document.getElementById('filter-facturable').value || '',
            facturado: document.getElementById('filter-facturado').value || '',
            precio: document.getElementById('filter-precio').value || '',
            suplido: document.getElementById('filter-suplido').value || '',
            coste: document.getElementById('filter-coste').value || '',
            fecha_planificacion: document.getElementById('filter-fecha-planificacion').value || '',
            fecha_inicio: document.getElementById('filter-fecha-inicio').value || '',
            fecha_vencimiento: document.getElementById('filter-fecha-vencimiento').value || '',
            fecha_imputacion: document.getElementById('filter-fecha-imputacion')?.value || fechaImputacionInput.value, // Priorizar formulario
            tiempo_previsto: document.getElementById('filter-tiempo-previsto').value || '',
            tiempo_real: document.getElementById('filter-tiempo-real').value || ''
        };

        console.log('Datos de filtro:', filterData);

        // Obtener los IDs de usuario seleccionados como array
        const selectedUserIds = filterData.usuario ? filterData.usuario.split(',').map(id => parseInt(id)) : [];

        const selectedDate = filterData.fecha_imputacion;
        // Sincronizar el filtro rápido de usuario con los seleccionados en el formulario
        syncQuickFilterWithFormFilter(selectedUserIds, selectedDate);

        // Actualizar el panel con los filtros actuales
        updateFilterInfoPanel(filterData);

        window.currentFilters = filterData; // Actualiza los filtros activos en la variable global

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
                    updatePagination(data.pagination, loadFilteredTasks);  // Pasa loadFilteredTasks como argumento
                    updateHoursSummary(data.filteredTasks);
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



    // Filtro rápido asignación
    const quickFilterUserSelect = document.getElementById('quick-filter-user-select');
    const quickFilterUserList = document.getElementById('quick-filter-user-list');
    const quickFilterSelectedUsersContainer = document.getElementById('quick-filter-selected-users');
    const quickFilterUserIdsInput = document.getElementById('quick-filter-user-ids');
    const selectedUserNamesContainer = document.getElementById('selected-user-names');
    let quickFilterSelectedUsers = [];
    const sessionUserCheckboxQuick = document.getElementById(`quick-filter-user-${sessionUserId}`);


    const fechaImputacionInput = document.getElementById('quick-fecha-imputacion');
    const today = new Date().toISOString().split('T')[0];
    let quickFilterSelectedDates = [today]; // Inicia con la fecha de hoy
    const fechaImputacionField = document.getElementById('filter-fecha-imputacion');

    if (fechaImputacionField && !fechaImputacionField.value) {
        fechaImputacionField.value = today; // Establecer la fecha de hoy solo si no hay valor previo
    }


    // Establecer el valor inicial en el campo de fecha imputación
    if (fechaImputacionInput) {
        fechaImputacionInput.value = today;
    }

    // Evento para actualizar el filtro cuando cambie la fecha
    fechaImputacionInput.addEventListener('change', function () {
        quickFilterSelectedDates = [fechaImputacionInput.value];
        applyQuickFilters();
    });

    // Seleccionar automáticamente el usuario en sesión
    if (sessionUserCheckboxQuick) {
        sessionUserCheckboxQuick.checked = true;
        const sessionUserName = sessionUserCheckboxQuick.nextElementSibling.textContent;
        quickFilterSelectedUsers.push({ id: sessionUserId, name: sessionUserName });
        updateQuickFilterSelectedUsersDisplay();
        updateQuickFilterUserIdsInput();
        applyQuickFilters();
    }


    // Función para actualizar el filtro rápido basado en el filtro del formulario
    function syncQuickFilterWithFormFilter(selectedUserIds, selectedDate) {
        // Limpiar las selecciones actuales en el filtro rápido
        quickFilterSelectedUsers = [];

        // Desmarcar todos los checkboxes en el filtro rápido
        quickFilterUserList.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });

        // Marcar checkboxes según los IDs seleccionados en el formulario de filtro
        selectedUserIds.forEach(userId => {
            const checkbox = document.getElementById(`quick-filter-user-${userId}`);
            if (checkbox) {
                checkbox.checked = true;
                const userName = checkbox.nextElementSibling.textContent;
                quickFilterSelectedUsers.push({ id: userId, name: userName });
            }
        });

        if (selectedDate) {
            fechaImputacionInput.value = selectedDate;
            quickFilterSelectedDates = [selectedDate];
        }

        // Actualizar la visualización y el input oculto en el filtro rápido
        updateQuickFilterSelectedUsersDisplay();
        updateQuickFilterUserIdsInput();
        updateSelectedUserNames();
        updateQuickFilterDateInput();
        applyQuickFilters(); // Aplicar el filtro en tiempo real
    }

    // Actualizar el campo oculto con la fecha seleccionada en el filtro rápido
    function updateQuickFilterDateInput() {
        fechaImputacionInput.value = quickFilterSelectedDates.length > 0 ? quickFilterSelectedDates[0] : '';
    }

    // Mostrar/ocultar la lista de usuarios al hacer clic
    quickFilterUserSelect.addEventListener('click', toggleQuickFilterUserList);

    function toggleQuickFilterUserList() {
        quickFilterUserList.style.display = quickFilterUserList.style.display === 'block' ? 'none' : 'block';
    }

    // Manejar la selección de usuarios en el filtro rápido
    quickFilterUserList.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const userId = this.value;
            const userName = this.nextElementSibling.textContent;

            if (this.checked) {
                quickFilterSelectedUsers.push({ id: userId, name: userName });
            } else {
                quickFilterSelectedUsers = quickFilterSelectedUsers.filter(user => user.id !== userId);
            }
            updateQuickFilterSelectedUsersDisplay();
            updateQuickFilterUserIdsInput();
            updateSelectedUserNames();
            applyQuickFilters();
        });
    });

    // Actualizar la visualización de los usuarios seleccionados en el filtro rápido
    function updateQuickFilterSelectedUsersDisplay() {
        quickFilterSelectedUsersContainer.innerHTML = '';
        quickFilterSelectedUsers.forEach(user => {
            const span = document.createElement('span');
            span.textContent = user.name;
            quickFilterSelectedUsersContainer.appendChild(span);
        });
    }

    // Actualizar el campo oculto con los IDs de usuarios seleccionados en el filtro rápido
    function updateQuickFilterUserIdsInput() {
        quickFilterUserIdsInput.value = quickFilterSelectedUsers.map(user => user.id).join(',');
    }

    // Actualizar el nombre de los usuarios en el título
    function updateSelectedUserNames() {
        const title = document.querySelector('.title');
        if (quickFilterSelectedUsers.length === 0) {
            title.textContent = 'Tiempos de todos';
        } else {
            const names = quickFilterSelectedUsers.map(user => user.name).join(', ');
            title.textContent = `Tiempos de ${names}`;
        }
    }
    updateSelectedUserNames();

    // Aplicar el filtro de usuarios seleccionados en la tabla de tareas
    function applyQuickFilters() {

        const filterData = {
            cliente: document.getElementById('filter-cliente-id-input')?.value || '', // Usar el ID del cliente
            asunto: document.getElementById('filter-asunto-input')?.value || '',
            tipo: document.getElementById('filter-tipo-input')?.value || '',
            subtipo: document.getElementById('filter-subtipo')?.value || '',
            estado: document.getElementById('filter-estado')?.value || '',
            archivo: document.getElementById('filter-archivo')?.value || '',
            facturable: document.getElementById('filter-facturable')?.value || '',
            facturado: document.getElementById('filter-facturado')?.value || '',
            precio: document.getElementById('filter-precio')?.value || '',
            suplido: document.getElementById('filter-suplido')?.value || '',
            coste: document.getElementById('filter-coste')?.value || '',
            fecha_inicio: document.getElementById('filter-fecha-inicio')?.value || '',
            fecha_vencimiento: document.getElementById('filter-fecha-vencimiento')?.value || '',
            fecha_imputacion: fechaImputacionInput.value,
            tiempo_previsto: document.getElementById('filter-tiempo-previsto')?.value || '',
            tiempo_real: document.getElementById('filter-tiempo-real')?.value || '',
            usuario: quickFilterUserIdsInput.value // Usar el ID(s) de los usuarios seleccionados en el filtro rápido
        };
        // Actualizar el panel con los filtros actuales
        updateFilterInfoPanel(filterData);

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
                    updateTaskTable(data.filteredTasks); // Actualizar la tabla con las tareas filtradas
                    // Actualizar el resumen de horas
                    updateHoursSummary(data.filteredTasks);
                } else {
                    console.error('Error al filtrar tareas:', data.message);
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error.message);
            });
    }

    // Cerrar el selector al hacer clic fuera
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.custom-select') && e.target !== quickFilterUserSelect) {
            quickFilterUserList.style.display = 'none';
        }
    });


    // Función para actualizar el resumen de horas
    function updateHoursSummary(tasks) {
        let totalTiempoPrevisto = 0;
        let totalTiempoReal = 0;

        // Iterar sobre las tareas y sumar los valores
        tasks.forEach(task => {
            totalTiempoPrevisto += parseFloat(task.tiempo_previsto || 0);
            totalTiempoReal += parseFloat(task.tiempo_real || 0);
        });

        // Actualizar los valores en el panel, verificando la existencia de los elementos
        const tiempoPrevistoElement = document.getElementById('total-tiempo-previsto');
        const tiempoRealElement = document.getElementById('total-tiempo-real');

        if (tiempoPrevistoElement) {
            tiempoPrevistoElement.textContent = totalTiempoPrevisto.toFixed(2);
        } else {
            console.warn("Elemento 'total-tiempo-previsto' no encontrado en el DOM.");
        }

        if (tiempoRealElement) {
            tiempoRealElement.textContent = totalTiempoReal.toFixed(2);
        } else {
            console.warn("Elemento 'total-tiempo-real' no encontrado en el DOM.");
        }
    }




});
