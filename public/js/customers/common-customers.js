window.currentFilters = {}; // Variable global para los filtros activos
 // Obtener los datos de clientes, asuntos y tipos desde los atributos data
 let clasificacionesData = JSON.parse(document.getElementById('clasificaciones-data').getAttribute('data-clasificaciones'));
 let tributacionesData = JSON.parse(document.getElementById('tributaciones-data').getAttribute('data-tributaciones'));
 let situacionesData = JSON.parse(document.getElementById('situaciones-data').getAttribute('data-situaciones'));
 let tiposData = JSON.parse(document.getElementById('tipos-data').getAttribute('data-tipos'));
 let clientesData = JSON.parse(document.getElementById('clientes-data').getAttribute('data-clientes'));
 let usersData = JSON.parse(document.getElementById('usuarios-data').getAttribute('data-usuarios'));

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


// Función para abrir el modal con los detalles del cliente
function openCustomerModal(customerId) {
    console.log("Abriendo modal para el cliente:", customerId); // Log para verificar cuándo se abre el modal

    // Realizar una solicitud AJAX para obtener los detalles del cliente
    fetch(`/clientes/${customerId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                // Insertar el HTML de la vista en el modal
                const modalContent = document.getElementById('customer-detail-modal-content');
                modalContent.innerHTML = data.html;

                // Mostrar el modal
                const modal = document.getElementById('customer-detail-modal');
                modal.setAttribute('data-customer-id', customerId); // Guardar el customerId en el modal

                // Reiniciar las clases de animación
                modal.classList.remove('hide', 'show');
                modalContent.classList.remove('hide', 'show');

                // Mostrar el modal
                modal.style.display = 'flex';

                // Aplicar la animación de entrada
                setTimeout(() => {
                    modal.classList.add('show'); // Añadir la clase para mostrar el fondo
                    modalContent.classList.add('show'); // Añadir la clase para mostrar el contenido
                }, 10); // Un pequeño retraso para que el DOM se actualice antes de iniciar la animación

            } else {
                console.error('Error al cargar los detalles del customer:', data.error);
            }
        })
        .catch(error => {
            console.error('Error al cargar los detalles del customer:', error);
        });
}

// Función para cerrar el modal
function closeCustomersModal() {
    const modal = document.getElementById('customer-detail-modal');
    const modalContent = document.getElementById('customer-detail-modal-content');

    // Iniciar la animación de salida
    modal.classList.add('hide');
    modalContent.classList.add('hide');

    // Esconder el modal después de la animación
    setTimeout(() => {
        modal.style.display = 'none';
        modal.classList.remove('show');
        modalContent.classList.remove('show');
    }, 400); // Tiempo de la animación de salida
}

// Añadir el evento de cierre solo una vez
document.addEventListener('DOMContentLoaded', () => {
    const closeModalButton = document.getElementById('close-customer-detail-modal');
    if (closeModalButton) {
        closeModalButton.addEventListener('click', closeCustomersModal); // Añadir el evento de cierre
    }
});


// Función para asignar eventos de doble clic a las filas de la tabla solo una vez
function addDoubleClickEventToRows() {
    const rows = document.querySelectorAll('table tbody tr');
    rows.forEach(row => {
        if (!row.dataset.hasDblClick) { // Asegurarse de que no tiene el evento agregado previamente
            row.addEventListener('dblclick', function () {
                const customerId = this.getAttribute('data-customer-id');
                openCustomerModal(customerId);
            });
            row.dataset.hasDblClick = true; // Marcar que el evento ya fue agregado
        }
    });
}


// Función para mostrar la notificación unificada
function showNotification(message = "Acción completada", type = "success") {
    const notification = document.getElementById('notification');
    const notificationMessage = document.querySelector('.notification-message');
    const notificationIcon = document.querySelector('.notification-icon');
    const notificationTimer = document.querySelector('.notification-timer');

    // Reiniciar visibilidad y clases para asegurar que la notificación esté lista
    notification.style.visibility = 'visible'; // Asegurar que sea visible
    notification.classList.remove('hide', 'show', 'success', 'error', 'info', 'warning'); // Eliminar todas las clases

    // Cambiar el mensaje
    notificationMessage.textContent = message;

    // Cambiar el icono y color de fondo según el tipo
    switch (type) {
        case 'success':
            notificationIcon.textContent = '✔️';
            notification.classList.add('success');
            break;
        case 'error':
            notificationIcon.textContent = '🗑️';
            notification.classList.add('error');
            break;
        case 'info':
            notificationIcon.textContent = 'ℹ️';
            notification.classList.add('info');
            break;
        case 'warning':
            notificationIcon.textContent = '⚠️';
            notification.classList.add('warning');
            break;
        default:
            notificationIcon.textContent = '✔️';
            notification.classList.add('success');
            break;
    }

    // Reiniciar la barra de tiempo
    notificationTimer.style.width = '100%';

    // Mostrar la notificación
    setTimeout(() => {
        notification.classList.add('show');
        notification.classList.remove('hide');
        notificationTimer.style.width = '0%'; // Animar la barra de progreso
    }, 10); // Pequeño retraso para una transición más suave

    // Ocultar la notificación después de 3.5 segundos
    setTimeout(() => {
        notification.classList.add('hide');
        notification.classList.remove('show');

        // Ocultar visibilidad al terminar la animación
        setTimeout(() => {
            notification.style.visibility = 'hidden';
        }, 500); // Esperar el tiempo de la animación para ocultar visibilidad
    }, 3100);
}

