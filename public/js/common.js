// Función para actualizar la paginación
function updatePagination(pagination, loadFunction, isFiltered = false) {
    const paginationContainer = document.getElementById('pagination-controls');
    paginationContainer.innerHTML = ''; // Limpiar el contenedor de paginación

    const paginationList = document.createElement('ul'); // Crear una lista para la paginación
    paginationList.classList.add('pagination'); // Añadir clase de paginación

    // Botón "Anterior"
    if (pagination.prev_page_url) {
        const prevButton = document.createElement('li');
        prevButton.innerHTML = `<button>Anterior</button>`;
        prevButton.classList.add('pagination-button');
        prevButton.addEventListener('click', function () {
            loadFunction(pagination.current_page - 1);
        });
        paginationList.appendChild(prevButton);
    }

    // Botones de números de páginas
    for (let i = 1; i <= pagination.last_page; i++) {
        const pageButton = document.createElement('li');
        pageButton.innerHTML = `<button>${i}</button>`;
        pageButton.classList.add('pagination-button');

        // Establecer la página actual como activa
        if (i === pagination.current_page) {
            pageButton.classList.add('active');
        }

        pageButton.addEventListener('click', function () {
            loadFunction(i);
        });

        paginationList.appendChild(pageButton);
    }

    // Botón "Siguiente"
    if (pagination.next_page_url) {
        const nextButton = document.createElement('li');
        nextButton.innerHTML = `<button>Siguiente</button>`;
        nextButton.classList.add('pagination-button');
        nextButton.addEventListener('click', function () {
            loadFunction(pagination.current_page + 1);
        });
        paginationList.appendChild(nextButton);
    }

    // Añadir la lista al contenedor de paginación
    paginationContainer.appendChild(paginationList);
}


// Función común para manejar errores
function handleError(message) {
    console.error('Error:', message);
    const tableBody = document.querySelector('table tbody');
    tableBody.innerHTML = '<tr><td colspan="21" class="text-center text-red-500">Error al cargar los datos.</td></tr>';
}

