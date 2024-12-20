const filterCustomerButton = document.getElementById('filter-customer-button');
const filterCustomerForm = document.getElementById('filter-customer-form');
const filterCustomerFormContent = document.getElementById('filter-customer-form-content');

const applyFilterButton = document.getElementById('apply-filter-button');
const cancelFilterButton = document.getElementById('cancel-filter-button');
const clearFilterButton = document.getElementById('clear-filter-button');



function loadFilteredCustomers(page = 1) {

    const filterData = {
        nombre_fiscal: document.getElementById('filter-nombrefiscal-ids').value || '',
        nif: document.getElementById('filter-nif-ids').value || '',
        movil: document.getElementById('filter-movil-input').value || '',
        fijo: document.getElementById('filter-fijo-input').value || '',
        email: document.getElementById('filter-email-input').value || '',
        direccion: document.getElementById('filter-direccion-input').value || '',
        codigo_postal: document.getElementById('filter-codigo-postal-input').value || '',
        poblacion: document.getElementById('filter-poblacion-input').value || '',
        tipo_cliente: document.getElementById('filter-tipocliente-ids').value || '',
        clasificacion: document.getElementById('filter-clasificacion-ids').value || '',
        tributacion: document.getElementById('filter-tributacion-ids').value || '',
        situacion: document.getElementById('filter-situacion-ids').value || '',
        datos_bancarios: document.getElementById('filter-datos-bancarios-input').value || '',
        subclase: document.getElementById('filter-subclase-input').value || '',
        puntaje: document.getElementById('filter-puntaje-input').value || '',
        codigo_sage: document.getElementById('filter-codigo-sage-input').value || '',
        usuario: document.getElementById('filter-user-ids').value || '', // Para el responsable asignado
        persona_contacto: document.getElementById('filter-persona_contacto-input').value || '', // Persona de contacto
        segundo_telefono: document.getElementById('filter-segundo_telefono-input').value || '' // Segundo teléfono
    };

    console.log('Datos de filtro:', filterData);
    window.currentFilters = filterData; // Actualiza los filtros activos en la variable global

    // Actualizar el panel con los filtros actuales
    updateFilterInfoPanel(filterData);

    // Realizar la solicitud al servidor para filtrar los clientes
    fetch(`/clientes/filtrar?page=${page}`, {  // <-- Asegúrate de pasar el número de página
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
                updateCustomerTable(data.filteredCustomers);
                updatePagination(data.pagination, loadFilteredCustomers);  // Pasa loadFilteredTasks como argumento
                closeFilterCustomerForm();
            } else {
                console.error('Error al filtrar clientes:', data.message);
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error.message);
        });
}

// Función para cerrar el formulario
function closeFilterCustomerForm() {
    filterCustomerForm.classList.remove('show');
    filterCustomerForm.classList.add('hide');
    setTimeout(() => {
        filterCustomerForm.style.display = 'none';
    }, 400);

}
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

            if (key === 'usuario') {
                // Manejo para usuarios
                const usuario = usersData.find(usuario => usuario.id === parseInt(value));
                p.textContent = `Usuario Asignado: ${usuario ? usuario.name : 'Desconocido'}`;
            }
            else if (key === 'clasificacion') {
                // Manejo para clasificaciones
                const clasificacionIds = value.split(',').map(id => parseInt(id));
                const clasificacionNames = clasificacionIds
                    .map(id => {
                        const clasificacion = clasificacionesData.find(c => c.id === id);
                        return clasificacion ? clasificacion.nombre : 'Desconocido';
                    })
                    .join(', ');
                p.textContent = `Clasificación: ${clasificacionNames}`;
            }
            else if (key === 'situacion') {
                // Manejo para situaciones
                const situacionIds = value.split(',').map(id => parseInt(id));
                const situacionNames = situacionIds
                    .map(id => {
                        const situacion = situacionesData.find(s => s.id === id);
                        return situacion ? situacion.nombre : 'Desconocido';
                    })
                    .join(', ');
                p.textContent = `Situación: ${situacionNames}`;
            }
            else if (key === 'tributacion') {
                // Manejo para tributaciones
                const tributacionIds = value.split(',').map(id => parseInt(id));
                const tributacionNames = tributacionIds
                    .map(id => {
                        const tributacion = tributacionesData.find(t => t.id === id);
                        return tributacion ? tributacion.nombre : 'Desconocido';
                    })
                    .join(', ');
                p.textContent = `Tributación: ${tributacionNames}`;
            }
            else if (key === 'tipo_cliente') {
                // Manejo para tipo de cliente
                const tipoClienteIds = value.split(',').map(id => parseInt(id));
                const tipoClienteNames = tipoClienteIds
                    .map(id => {
                        const tipoCliente = tiposData.find(t => t.id === id);
                        return tipoCliente ? tipoCliente.nombre : 'Desconocido';
                    })
                    .join(', ');
                p.textContent = `Tipo Cliente: ${tipoClienteNames}`;
            }
            else {
                // Default para otros campos
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




document.addEventListener('DOMContentLoaded', function () {
    console.log('El script de filtro Clientes ha sido cargado correctamente.');





    // Mostrar el formulario de filtrar clientes
    filterCustomerButton.addEventListener('click', function () {
        filterCustomerForm.style.display = 'block';
        setTimeout(() => {
            filterCustomerForm.classList.remove('hide');
            filterCustomerForm.classList.add('show');
        }, 10);
    });

    // Ocultar el formulario de filtrar clientes
    cancelFilterButton.addEventListener('click', function () {
        closeFilterCustomerForm();
    });

    // Ocultar el formulario cuando se hace clic fuera de él
    document.addEventListener('click', function (event) {
        const isInsideForm = filterCustomerForm.contains(event.target); // Verifica si el clic fue dentro del formulario
        const isfilterCustomerButton = document.getElementById('filter-customer-button').contains(event.target);
        const isSelectedItem = event.target.closest('.selected-item'); // Verifica si el clic fue dentro de un item seleccionado

        // Verifica si el clic no es dentro del formulario o dentro del botón de abrir el formulario
        if (!isInsideForm && !isfilterCustomerButton && !isSelectedItem) {
            if (filterCustomerForm.classList.contains('show')) {
                closeFilterCustomerForm();
            }
        }
    });





    // Lógica para limpiar los campos del formulario de filtros
    clearFilterButton.addEventListener('click', function (e) {
        e.preventDefault(); // Evitar que se envíe el formulario al hacer clic en "Limpiar"

        // Usar el método reset() para limpiar todos los campos del formulario
        filterCustomerFormContent.reset();

        // Limpiar los usuarios seleccionados
        resetSelectedUsers();

        // Limpiar los campos ocultos que almacenan los IDs
        document.getElementById('filter-tipo-cliente-ids').value = '';
        document.getElementById('filter-clasificacion-ids').value = '';
        document.getElementById('filter-tributacion-ids').value = '';
        document.getElementById('filter-situacion-ids').value = '';
        document.getElementById('filter-user-ids').value = '';

        // Limpiar las visualizaciones de autocompletar
        document.getElementById('filter-tipo-cliente-input').value = '';
        document.getElementById('filter-clasificacion-input').value = '';
        document.getElementById('filter-tributacion-input').value = '';
        document.getElementById('filter-situacion-input').value = '';

        // Limpiar los contenedores de seleccionados
        document.getElementById('filter-tipo-cliente-selected').innerHTML = '';
        document.getElementById('filter-clasificacion-selected').innerHTML = '';
        document.getElementById('filter-tributacion-selected').innerHTML = '';
        document.getElementById('filter-situacion-selected').innerHTML = '';

        // Si las listas de autocompletar están visibles, ocultarlas
        document.getElementById('filter-clasificacion-list').style.display = 'none';
        document.getElementById('filter-tributacion-list').style.display = 'none';
        document.getElementById('filter-situacion-list').style.display = 'none';
        document.getElementById('filter-tipo-cliente-list').style.display = 'none';
        document.getElementById('filter-user-list').style.display = 'none';
    });



    applyFilterButton.addEventListener('click', function (e) {
        e.preventDefault();
        loadFilteredCustomers();
    });


    let usersData = JSON.parse(document.getElementById('usuarios-data').getAttribute('data-usuarios'));



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



    // Autocompletar para clasificación, tributación, situación y tipo (igual que en add-customer)

    // Autocompletar para Clasificación

    setupAutocomplete(
        'filter-clasificacion-input',
        'filter-clasificacion-ids',
        'filter-clasificacion-list',
        clasificacionesData,
        item => item.nombre, // Comparar con el nombre
        item => item.nombre // Comparar también con el NIF
    );

    // Autocompletar para tributacion
    setupAutocomplete(
        'filter-tributacion-input',
        'filter-tributacion-ids',
        'filter-tributacion-list',
        tributacionesData,
        item => item.nombre, // Comparar con el nombre
        item => item.nombre // Comparar también con el NIF
    );
    // Autocompletar para situacion
    setupAutocomplete(
        'filter-situacion-input',
        'filter-situacion-ids',
        'filter-situacion-list',
        situacionesData,
        item => item.nombre, // Comparar con el nombre
        item => item.nombre // Comparar también con el NIF
    );

    // Autocompletar para Tipo
    setupAutocomplete(
        'filter-tipocliente-input',
        'filter-tipocliente-ids',
        'filter-tipocliente-list',
        tiposData,
        item => item.nombre, // Comparar con el nombre
        item => item.nombre // Comparar también con el NIF
    );

    setupAutocomplete(
        'filter-nombrefiscal-input',
        'filter-nombrefiscal-ids',
        'filter-nombrefiscal-list',
        clientesData,
        item => item.nombre_fiscal, // Comparar con `nombre_fiscal`
        item => item.nombre_fiscal  // Mostrar en la lista
    );

    setupAutocomplete(
        'filter-nif-input',
        'filter-nif-ids',
        'filter-nif-list',
        clientesData,
        item => item.nif, // Comparar con `nif`
        item => item.nif  // Mostrar en la lista
    );




    document.addEventListener('click', function (e) {
        // Cerrar todas las listas de autocompletar si el clic no es en un input o en una lista de autocompletar
        closeAutocompleteListIfClickedOutside('filter-tipocliente-input', 'filter-tipocliente-list', e);
        closeAutocompleteListIfClickedOutside('filter-clasificacion-input', 'filter-clasificacion-list', e);
        closeAutocompleteListIfClickedOutside('filter-tributacion-input', 'filter-tributacion-list', e);
        closeAutocompleteListIfClickedOutside('filter-situacion-input', 'filter-situacion-list', e);
        closeAutocompleteListIfClickedOutside('filter-nombrefiscal-input', 'filter-nombrefiscal-list', e);
        closeAutocompleteListIfClickedOutside('filter-nif-input', 'filter-nif-list', e)

    });


    // Reutilizamos las funciones de autocompletar aquí

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
                const mainMatch = (itemSelector(item) || '').toLowerCase().includes(query.toLowerCase());
                const extraMatch = extraMatchSelector ? (extraMatchSelector(item) || '').toLowerCase().includes(query.toLowerCase()) : false;
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

            // Filtrar elementos que tienen valores válidos según displayFormatter
            const validItems = filtered.filter(item => {
                const displayValue = displayFormatter(item);
                return displayValue && displayValue.trim() !== ''; // Excluir valores nulos o vacíos
            });

            if (validItems.length === 0) {
                list.style.display = 'none';
                return;
            }

            list.style.display = 'block';
            validItems.forEach((item, index) => {
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
                // Orden de prioridad para mostrar el texto
                const displayText = item.nif || item.nombre_fiscal || item.nombre || 'Valor desconocido';
                span.textContent = displayText;

                const removeBtn = document.createElement('button');
                removeBtn.textContent = 'x';
                removeBtn.addEventListener('click', () => removeItem(item));
                span.appendChild(removeBtn);

                selectedContainer.appendChild(span);
            });

            updateHiddenInput(); // Actualizar el campo oculto
        }

        function updateHiddenInput() {
            let value = '';

            // Determinar el tipo de filtro según el inputId
            if (input.id.includes('nif')) {
                // Enviar solo los NIFs
                value = selectedItems.map(item => item.nif).filter(v => v).join(',');
            } else if (input.id.includes('nombrefiscal')) {
                // Enviar solo los nombres fiscales
                value = selectedItems.map(item => item.nombre_fiscal).filter(v => v).join(',');
            } else {
                // Para otros casos (clasificación, tributación, etc.), enviar nombre
                value = selectedItems.map(item => item.nombre).filter(v => v).join(',');
            }

            hiddenInput.value = value;

            console.log(`Valores actualizados para el filtro: ${hiddenInput.value}`);
        }



        function selectItem(item) {
            // Determinar si el valor ya está seleccionado
            const exists = selectedItems.some(selected =>
                (selected.id && selected.id === item.id) ||
                (selected.nombre && selected.nombre === item.nombre)
            );

            if (!exists) {
                // Crear un nuevo objeto con las propiedades relevantes
                const newItem = {
                    id: item.id || null,
                    nombre_fiscal: item.nombre_fiscal || '',
                    nif: item.nif || '' // Asegurarse de capturar el NIF correctamente
                };
                selectedItems.push(newItem);
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
                    // Texto libre si no está en la lista
                    const query = input.value.trim();
                    const newItem = {
                        id: null, // ID nulo para elementos personalizados
                        nombre_fiscal: inputId.includes('nombre-fiscal') ? query : '',
                        nif: inputId.includes('nif') ? query : '',
                        nombre: query // Nombre genérico para otros campos
                    };

                    // Validar duplicados
                    const exists = selectedItems.some(item =>
                        item.nombre === newItem.nombre ||
                        item.nombre_fiscal === newItem.nombre_fiscal ||
                        item.nif === newItem.nif
                    );

                    if (!exists) {
                        selectItem(newItem);
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
        document.getElementById('filter-tipocliente-list').style.display = 'none';
        document.getElementById('filter-clasificacion-list').style.display = 'none';
        document.getElementById('filter-tributacion-list').style.display = 'none';
        document.getElementById('filter-situacion-list').style.display = 'none';
        document.getElementById('filter-nombrefiscal-list').style.display = 'none';
        document.getElementById('filter-nif-list').style.display = 'none';

    }









    // Función para manejar la selección de usuarios en el formulario de filtrado
    const filterUserSelect = document.getElementById('filter-user-select');
    const filterUserList = document.getElementById('filter-user-list');
    const filterSelectedUsersContainer = document.getElementById('filter-selected-users');
    const filterUserIdsInput = document.getElementById('filter-user-ids');
    let filterSelectedUsers = [];
    let filterCurrentFocus = -1;

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


});
