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

    function renderList(items, container, key) {
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
            actions.className = 'actions';

            // Botón Editar
            const editButton = document.createElement('button');
            editButton.className = 'btn-refresh edit';
            editButton.setAttribute('aria-label', `Editar ${item[key]}`);
            editButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="icon-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
  <path d="M11 3h2m-1 1v12m-2 0h4m6 4H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2zM15 3h4M21 3h4"/>
</svg>


            `;

            // Botón Borrar
            const deleteButton = document.createElement('button');
            deleteButton.className = 'btn-refresh delete';
            deleteButton.setAttribute('aria-label', `Borrar ${item[key]}`);
            deleteButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="icon-delete" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path d="M19 7H5m0 0L5 19a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7M9 7V5a2 2 0 1 1 4 0v2m-6 4v6m4-6v6m4-6v6"/>
</svg>

            `;

            // Agregar botones al contenedor de acciones
            actions.appendChild(editButton);
            actions.appendChild(deleteButton);

            // Agregar elementos al item
            li.appendChild(span);
            li.appendChild(actions);
            ul.appendChild(li);
        });

        container.appendChild(ul);
    }


    async function loadData(endpoint, containerId, key, dataStore) {
        const container = document.getElementById(containerId);
        const refreshButton = document.querySelector(`#refresh-${containerId.split('-')[0]}`);
        refreshButton.disabled = true;

        const data = await fetchData(endpoint);
        dataStore.length = 0;
        dataStore.push(...data);
        renderList(data, container, key);

        refreshButton.disabled = false;
    }

    function setupSearch(inputId, dataStore, containerId, key) {
        const input = document.getElementById(inputId);
        const container = document.getElementById(containerId);

        input.addEventListener('input', () => {
            const query = input.value.toLowerCase();
            const filteredData = dataStore.filter(item =>
                item[key].toLowerCase().includes(query)
            );
            renderList(filteredData, container, key);
        });
    }

    document.getElementById('refresh-asuntos').addEventListener('click', () => {
        loadData(API_ENDPOINTS.asuntos, 'asuntos-list', 'nombre', asuntosData);
    });

    document.getElementById('refresh-tipos').addEventListener('click', () => {
        loadData(API_ENDPOINTS.tipos, 'tipos-list', 'nombre', tiposData);
    });

    (async () => {
        await initializeSanctum();
        loadData(API_ENDPOINTS.asuntos, 'asuntos-list', 'nombre', asuntosData);
        loadData(API_ENDPOINTS.tipos, 'tipos-list', 'nombre', tiposData);

        setupSearch('search-asuntos', asuntosData, 'asuntos-list', 'nombre');
        setupSearch('search-tipos', tiposData, 'tipos-list', 'nombre');
    })();
});
