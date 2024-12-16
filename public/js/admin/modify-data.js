document.addEventListener('DOMContentLoaded', () => {
    const API_ENDPOINTS = {
        asuntos: '/api/asuntos',
        tipos: '/api/tipos',
    };

    let asuntosData = [];
    let tiposData = [];

    async function initializeSanctum() {
        try {
            const response = await fetch('/sanctum/csrf-cookie', {
                method: 'GET',
                credentials: 'include',
            });
            if (!response.ok) {
                throw new Error(`Error inicializando Sanctum: ${response.status}`);
            }
        } catch (error) {
            console.error("Error inicializando Sanctum:", error.message);
        }
    }

    async function fetchData(url) {
        try {
            const response = await fetch(url, {
                method: 'GET',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }

            return await response.json();
        } catch (error) {
            console.error(`Error fetching data from ${url}:`, error.message);
            return [];
        }
    }
    function renderList(items, container, key, entity) {
        container.innerHTML = ''; // Clear the container
        if (items.length === 0) {
            container.innerHTML = '<p class="empty-message">No hay datos disponibles.</p>';
            return;
        }

        const ul = document.createElement('ul');
        ul.className = 'data-list';
        items.forEach(item => {
            const li = document.createElement('li');
            li.className = 'data-item';

            // Nombre del dato
            const span = document.createElement('span');
            span.className = 'data-name';
            span.textContent = item[key];

            // Contenedor de acciones
            const actions = document.createElement('div');
            actions.className = 'admin-actions';

            // Botón Editar
            const editButton = document.createElement('button');
            editButton.className = 'btn-refresh edit';
            editButton.setAttribute('aria-label', `Editar ${item[key]}`);
            editButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 26 26">
            <path d="M 20.09375 0.25 C 19.5 0.246094 18.917969 0.457031 18.46875 0.90625 L 17.46875 1.9375 L 24.0625 8.5625 L 25.0625 7.53125 C 25.964844 6.628906 25.972656 5.164063 25.0625 4.25 L 21.75 0.9375 C 21.292969 0.480469 20.6875 0.253906 20.09375 0.25 Z M 16.34375 2.84375 L 14.78125 4.34375 L 21.65625 11.21875 L 23.25 9.75 Z M 13.78125 5.4375 L 2.96875 16.15625 C 2.71875 16.285156 2.539063 16.511719 2.46875 16.78125 L 0.15625 24.625 C 0.0507813 24.96875 0.144531 25.347656 0.398438 25.601563 C 0.652344 25.855469 1.03125 25.949219 1.375 25.84375 L 9.21875 23.53125 C 9.582031 23.476563 9.882813 23.222656 10 22.875 L 20.65625 12.3125 L 19.1875 10.84375 L 8.25 21.8125 L 3.84375 23.09375 L 2.90625 22.15625 L 4.25 17.5625 L 15.09375 6.75 Z M 16.15625 7.84375 L 5.1875 18.84375 L 6.78125 19.1875 L 7 20.65625 L 18 9.6875 Z"></path>
            </svg>`;


            // Botón Borrar
            const deleteButton = document.createElement('button');
            deleteButton.className = 'btn-refresh delete';
            deleteButton.setAttribute('aria-label', `Borrar ${item[key]}`);
            deleteButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 30 30">
                <path d="M 14.984375 2.4863281 A 1.0001 1.0001 0 0 0 14 3.5 L 14 4 L 8.5 4 A 1.0001 1.0001 0 0 0 7.4863281 5 L 6 5 A 1.0001 1.0001 0 1 0 6 7 L 24 7 A 1.0001 1.0001 0 1 0 24 5 L 22.513672 5 A 1.0001 1.0001 0 0 0 21.5 4 L 16 4 L 16 3.5 A 1.0001 1.0001 0 0 0 14.984375 2.4863281 z M 6 9 L 7.7929688 24.234375 C 7.9109687 25.241375 8.7633438 26 9.7773438 26 L 20.222656 26 C 21.236656 26 22.088031 25.241375 22.207031 24.234375 L 24 9 L 6 9 z"></path>
            </svg>`;

            // Asignar eventos
            editButton.addEventListener('click', () => enableEditMode(li, item, key, entity));
            deleteButton.addEventListener('click', () => enableDeleteMode(li, item, key, entity));

            // Agregar botones al contenedor de acciones
            actions.appendChild(editButton);
            actions.appendChild(deleteButton);

            // Agregar elementos al ítem
            li.appendChild(span);
            li.appendChild(actions);
            ul.appendChild(li);
        });

        container.appendChild(ul);
    }

    function enableEditMode(li, item, key, entity) {
        const originalName = li.querySelector('.data-name').textContent;
        li.innerHTML = ''; // Limpiar contenido

        // Input de edición
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'edit-input';
        input.value = originalName;

        // Botón Guardar
        const saveButton = document.createElement('button');
        saveButton.className = 'btn-refresh save';
        saveButton.innerHTML = '💾';
        saveButton.addEventListener('click', () => saveEdit(li, input.value, item, key, entity));

        // Botón Cancelar
        const cancelButton = document.createElement('button');
        cancelButton.className = 'btn-refresh cancel';
        cancelButton.innerHTML = '✖️';
        cancelButton.addEventListener('click', () => disableEditMode(li, originalName, item, key, entity));

        // Añadir elementos al ítem
        li.appendChild(input);
        const actions = document.createElement('div');
        actions.className = 'admin-actions';
        actions.appendChild(saveButton);
        actions.appendChild(cancelButton);
        li.appendChild(actions);
    }

    function disableEditMode(li, originalName, item, key, entity) {
        li.innerHTML = ''; // Restaurar vista
        const span = document.createElement('span');
        span.className = 'data-name';
        span.textContent = originalName;

        const actions = document.createElement('div');
        actions.className = 'admin-actions';

        // Botón Editar
        const editButton = document.createElement('button');
        editButton.className = 'btn-refresh edit';
        editButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 26 26">
            <path d="M 20.09375 0.25 C 19.5 0.246094 18.917969 0.457031 18.46875 0.90625 L 17.46875 1.9375 L 24.0625 8.5625 L 25.0625 7.53125 C 25.964844 6.628906 25.972656 5.164063 25.0625 4.25 L 21.75 0.9375 C 21.292969 0.480469 20.6875 0.253906 20.09375 0.25 Z M 16.34375 2.84375 L 14.78125 4.34375 L 21.65625 11.21875 L 23.25 9.75 Z M 13.78125 5.4375 L 2.96875 16.15625 C 2.71875 16.285156 2.539063 16.511719 2.46875 16.78125 L 0.15625 24.625 C 0.0507813 24.96875 0.144531 25.347656 0.398438 25.601563 C 0.652344 25.855469 1.03125 25.949219 1.375 25.84375 L 9.21875 23.53125 C 9.582031 23.476563 9.882813 23.222656 10 22.875 L 20.65625 12.3125 L 19.1875 10.84375 L 8.25 21.8125 L 3.84375 23.09375 L 2.90625 22.15625 L 4.25 17.5625 L 15.09375 6.75 Z M 16.15625 7.84375 L 5.1875 18.84375 L 6.78125 19.1875 L 7 20.65625 L 18 9.6875 Z"></path>
            </svg>`;
        editButton.addEventListener('click', () => enableEditMode(li, item, key, entity));

        // Botón Borrar
        const deleteButton = document.createElement('button');
        deleteButton.className = 'btn-refresh delete';
        deleteButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 30 30">
                <path d="M 14.984375 2.4863281 A 1.0001 1.0001 0 0 0 14 3.5 L 14 4 L 8.5 4 A 1.0001 1.0001 0 0 0 7.4863281 5 L 6 5 A 1.0001 1.0001 0 1 0 6 7 L 24 7 A 1.0001 1.0001 0 1 0 24 5 L 22.513672 5 A 1.0001 1.0001 0 0 0 21.5 4 L 16 4 L 16 3.5 A 1.0001 1.0001 0 0 0 14.984375 2.4863281 z M 6 9 L 7.7929688 24.234375 C 7.9109687 25.241375 8.7633438 26 9.7773438 26 L 20.222656 26 C 21.236656 26 22.088031 25.241375 22.207031 24.234375 L 24 9 L 6 9 z"></path>
            </svg>`;
        deleteButton.addEventListener('click', () => enableDeleteMode(li, item, key, entity));

        actions.appendChild(editButton);
        actions.appendChild(deleteButton);
        li.appendChild(span);
        li.appendChild(actions);
    }

    async function saveEdit(li, newValue, item, key, entity) {
        try {
            // Obtén el token CSRF de las cookies (Laravel Sanctum lo configura automáticamente)
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch(`/api/${entity}/${item.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, // Añadir el token CSRF
                },
                body: JSON.stringify({ nombre: newValue }), // Enviar el nuevo nombre
            });

            if (!response.ok) {
                throw new Error('Error guardando los cambios');
            }

            // Actualizar localmente
            item[key] = newValue;
            disableEditMode(li, newValue, item, key, entity);
        } catch (error) {
            console.error('Error al guardar los cambios:', error);
        }
    }


    function enableDeleteMode(li, item, key, entity) {
        const originalName = li.querySelector('.data-name').textContent; // Guardar nombre original
        li.innerHTML = ''; // Limpiar contenido

        // Determinar el tipo de dato en función del key
        const typeLabel = entity === 'asuntos' ? 'asunto' : entity === 'tipos' ? 'tipo' : 'dato';

        // Mensaje de confirmación
        const confirmationMessage = document.createElement('span');
        confirmationMessage.className = 'confirmation-message';
        confirmationMessage.textContent = `¿Eliminar este ${typeLabel}?`;

        // Botón Confirmar
        const confirmButton = document.createElement('button');
        confirmButton.className = 'btn-refresh confirm';
        confirmButton.innerHTML = '✔️';
        confirmButton.addEventListener('click', () => deleteItem(li, item, entity));

        // Botón Cancelar
        const cancelButton = document.createElement('button');
        cancelButton.className = 'btn-refresh cancel';
        cancelButton.innerHTML = '✖️';
        cancelButton.addEventListener('click', () => disableDeleteMode(li, originalName, item, key, entity));

        // Añadir elementos al ítem
        li.appendChild(confirmationMessage);
        const actions = document.createElement('div');
        actions.className = 'admin-actions';
        actions.appendChild(confirmButton);
        actions.appendChild(cancelButton);
        li.appendChild(actions);
    }

    function disableDeleteMode(li, originalName, item, key, entity) {
        li.innerHTML = ''; // Restaurar contenido original

        // Nombre
        const span = document.createElement('span');
        span.className = 'data-name';
        span.textContent = originalName;

        // Contenedor de acciones
        const actions = document.createElement('div');
        actions.className = 'admin-actions';

        // Botón Editar
        const editButton = document.createElement('button');
        editButton.className = 'btn-refresh edit';
        editButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 26 26">
        <path d="M 20.09375 0.25 C 19.5 0.246094 18.917969 0.457031 18.46875 0.90625 L 17.46875 1.9375 L 24.0625 8.5625 L 25.0625 7.53125 C 25.964844 6.628906 25.972656 5.164063 25.0625 4.25 L 21.75 0.9375 C 21.292969 0.480469 20.6875 0.253906 20.09375 0.25 Z M 16.34375 2.84375 L 14.78125 4.34375 L 21.65625 11.21875 L 23.25 9.75 Z M 13.78125 5.4375 L 2.96875 16.15625 C 2.71875 16.285156 2.539063 16.511719 2.46875 16.78125 L 0.15625 24.625 C 0.0507813 24.96875 0.144531 25.347656 0.398438 25.601563 C 0.652344 25.855469 1.03125 25.949219 1.375 25.84375 L 9.21875 23.53125 C 9.582031 23.476563 9.882813 23.222656 10 22.875 L 20.65625 12.3125 L 19.1875 10.84375 L 8.25 21.8125 L 3.84375 23.09375 L 2.90625 22.15625 L 4.25 17.5625 L 15.09375 6.75 Z M 16.15625 7.84375 L 5.1875 18.84375 L 6.78125 19.1875 L 7 20.65625 L 18 9.6875 Z"></path>
        </svg>`;
        editButton.addEventListener('click', () => enableEditMode(li, item, key, entity));

        // Botón Borrar
        const deleteButton = document.createElement('button');
        deleteButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 30 30">
                <path d="M 14.984375 2.4863281 A 1.0001 1.0001 0 0 0 14 3.5 L 14 4 L 8.5 4 A 1.0001 1.0001 0 0 0 7.4863281 5 L 6 5 A 1.0001 1.0001 0 1 0 6 7 L 24 7 A 1.0001 1.0001 0 1 0 24 5 L 22.513672 5 A 1.0001 1.0001 0 0 0 21.5 4 L 16 4 L 16 3.5 A 1.0001 1.0001 0 0 0 14.984375 2.4863281 z M 6 9 L 7.7929688 24.234375 C 7.9109687 25.241375 8.7633438 26 9.7773438 26 L 20.222656 26 C 21.236656 26 22.088031 25.241375 22.207031 24.234375 L 24 9 L 6 9 z"></path>
            </svg>`;
        deleteButton.addEventListener('click', () => enableDeleteMode(li, item, key, entity));

        actions.appendChild(editButton);
        actions.appendChild(deleteButton);
        li.appendChild(span);
        li.appendChild(actions);
    }

    async function deleteItem(li, item, entity) {
        try {
            // Obtén el token CSRF del meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch(`/api/${entity}/${item.id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, // Añadir el token CSRF
                },
            });

            if (!response.ok) {
                throw new Error('Error al eliminar el dato');
            }

            // Eliminar visualmente el ítem de la lista
            li.remove();
        } catch (error) {
            console.error('Error al eliminar el dato:', error);
        }
    }



    async function loadData(endpoint, containerId, key, entity, dataStore) {
        const container = document.getElementById(containerId);
        const refreshButton = document.querySelector(`#refresh-${containerId.split('-')[0]}`);
        refreshButton.disabled = true;

        const data = await fetchData(endpoint);
        dataStore.length = 0;
        dataStore.push(...data);

        // Ahora pasamos `entity` a renderList
        renderList(data, container, key, entity);

        refreshButton.disabled = false;
    }

    function setupSearch(inputId, dataStore, containerId, key, entity) {
        const input = document.getElementById(inputId);
        const container = document.getElementById(containerId);

        input.addEventListener('input', () => {
            const query = input.value.toLowerCase();
            const filteredData = dataStore.filter(item =>
                item[key].toLowerCase().includes(query)
            );

            // Pasamos también `entity` a renderList
            renderList(filteredData, container, key, entity);
        });
    }

    document.getElementById('refresh-asuntos').addEventListener('click', () => {
        loadData(API_ENDPOINTS.asuntos, 'asuntos-list', 'nombre', 'asuntos', asuntosData);
    });

    document.getElementById('refresh-tipos').addEventListener('click', () => {
        loadData(API_ENDPOINTS.tipos, 'tipos-list', 'nombre', 'tipos', tiposData);
    });

    (async () => {
        await initializeSanctum();

        // Pasamos 'asuntos' y 'tipos' como el tipo de entidad
        loadData(API_ENDPOINTS.asuntos, 'asuntos-list', 'nombre', 'asuntos', asuntosData);
        loadData(API_ENDPOINTS.tipos, 'tipos-list', 'nombre', 'tipos', tiposData);

        setupSearch('search-asuntos', asuntosData, 'asuntos-list', 'nombre', 'asuntos');
        setupSearch('search-tipos', tiposData, 'tipos-list', 'nombre', 'tipos');
    })();


});
