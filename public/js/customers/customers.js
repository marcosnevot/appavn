document.addEventListener('DOMContentLoaded', function () {
    console.log('El script customers.js ha sido cargado correctamente.');

    // Variables globales para la paginación
    let currentPage = 1;
    let globalCustomersArray = []; // Definir una variable global para las tareas

    // Variables globales para la paginación y ordenación
    let currentSortKey = null; // Almacena la clave de ordenación actual
    let currentSortDirection = 'none'; // Dirección de orden actual
    const sessionUserId = document.getElementById('user-session-id').value;

    // Cargar clientes inicialmente
    loadCustomers();

    // Función para cargar las tareas mediante AJAX con paginación
    function loadCustomers(page = 1, sortKey = 'created_at', sortDirection = 'desc') {
        const tableBody = document.querySelector('table tbody');
        tableBody.innerHTML = '<tr><td colspan="21" class="text-center">Cargando clientes...</td></tr>'; // Mensaje de carga

        // Construir los parámetros de la URL
        const params = new URLSearchParams({
            ...window.currentFilters, // Usar filtros activos de la variable global
            page, // Página actual
            sortKey, // Clave de ordenación
            sortDirection, // Dirección de ordenación
            user_id: sessionUserId // Usuario actual
        });
        console.log('Parámetros enviados:', params);

        fetch(`/clientes/getCustomers?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadInitialCustomers(data.customers);
                    updatePagination(data.pagination, (newPage) => loadCustomers(newPage, sortKey, sortDirection));
                } else {
                    console.error('Error al cargar clientes:', data.message);
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error.message);
                tableBody.innerHTML = '<tr><td colspan="21" class="text-center text-red-500">Error al cargar los clientes.</td></tr>';
            });
    }


    // Manejar clics en los encabezados de las columnas para ordenar
    document.querySelectorAll('th[data-sort-key]').forEach(header => {
        header.addEventListener('click', function () {
            const sortKey = this.getAttribute('data-sort-key');

            // Determinar la dirección de orden
            if (currentSortKey === sortKey) {
                currentSortDirection = currentSortDirection === 'asc'
                    ? 'desc'
                    : currentSortDirection === 'desc'
                        ? 'none'
                        : 'asc';
            } else {
                currentSortKey = sortKey;
                currentSortDirection = 'asc'; // Reiniciar a ascendente para nueva columna
            }

            // Quitar clases de todos los encabezados
            document.querySelectorAll('th[data-sort-key]').forEach(th => {
                th.classList.remove('sorted-asc', 'sorted-desc');
            });

            // Añadir clase según la dirección actual
            if (currentSortDirection === 'asc') {
                this.classList.add('sorted-asc');
            } else if (currentSortDirection === 'desc') {
                this.classList.add('sorted-desc');
            }

            // Si el estado es "none", reestablecer al orden original
            const sortKeyToSend = currentSortDirection === 'none' ? 'created_at' : currentSortKey;
            const sortDirectionToSend = currentSortDirection === 'none' ? 'desc' : currentSortDirection;

            // Recargar tareas con la nueva ordenación y filtros activos
            loadCustomers(1, sortKeyToSend, sortDirectionToSend);
        });
    });



    // Función para cargar y actualizar la tabla de clientes inicialmente
    function loadInitialCustomers(customers) {
        const tableBody = document.querySelector('table tbody');
        tableBody.innerHTML = ''; // Limpiar la tabla existente

        customers.forEach(customer => {
            const row = document.createElement('tr');
            row.setAttribute('data-customer-id', customer.id); // Asignar el id del cliente

            row.innerHTML = `
            <td>${customer.id}</td>
            <td>${customer.nombre_fiscal || 'Sin nombre fiscal'}</td>
            <td>${customer.nif || 'Sin NIF'}</td>
            <td>${customer.movil || 'Sin móvil'}</td>
            <td>${customer.fijo || 'Sin fijo'}</td>
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
            tableBody.appendChild(row);

            // Añadir el evento de doble clic a las filas de la tabla
            addDoubleClickEventToRows();
        });
    }







});
