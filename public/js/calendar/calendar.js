document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    let lastRange = { start: null, end: null }; // Variable para almacenar el último rango solicitado

    if (calendarEl) {

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es', // Idioma español
            firstDay: 1,
            eventOrder: "classNames,start", // Ordenar por tipo de evento (classNames) y luego por fecha de inicio
            headerToolbar: {
                left: 'prev,next today', // Botones de navegación
                center: 'title', // Título del mes
                right: 'customLegend', // Añadimos un botón personalizado
            },
            buttonText: {
                today: 'Hoy', // Cambia el texto del botón "Today" a "Hoy"
            },
            customButtons: {
                customLegend: {
                    text: '', // Vacío, porque agregaremos el HTML dinámicamente
                    click: function () { } // No necesita funcionalidad
                }
            },

            events: function (fetchInfo, successCallback, failureCallback) {
                // Corregir el formato de las fechas
                const start = fetchInfo.startStr.trim();
                const end = fetchInfo.endStr.trim();
                console.log("Fechas enviadas al backend:", { start, end });

                fetch(`/api/calendar/tasks?start=${encodeURIComponent(start)}&end=${encodeURIComponent(end)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Error en la respuesta del servidor");
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Eventos recibidos:", data); // Depuración
                        successCallback(data); // Renderiza los eventos
                    })
                    .catch(error => {
                        console.error("Error cargando eventos:", error);
                        failureCallback(error);
                    });

            },
            eventClick: function (info) {
                // Redirigir al detalle de la tarea al hacer clic en un evento
                window.location.href = `/tareas?task_id=${info.event.id}`;
            },
            dateClick: function (info) {
                // Abrir modal al hacer doble clic en un día
                showModal(info.dateStr);
            },
            eventContent: function (arg) {
                // Crear contenido personalizado para el evento
                let asunto = document.createElement('div');
                asunto.textContent = arg.event.title; // Mostrar el asunto como título
                asunto.classList.add('fc-event-asunto'); // Asignar clase para estilos

                let cliente = document.createElement('div');
                cliente.textContent = arg.event.extendedProps.cliente || 'Sin cliente'; // Mostrar cliente o valor por defecto
                cliente.classList.add('fc-event-cliente'); // Asignar clase para estilos

                // Agrupar ambos elementos
                let content = document.createElement('div');
                content.classList.add('fc-event-content'); // Clase general para el contenedor
                if (arg.event.classNames) {
                    arg.event.classNames.forEach(className => content.classList.add(className)); // Aplicar clases específicas
                }
                content.appendChild(asunto);
                content.appendChild(cliente);

                // Si el evento tiene la clase 'evento-periodicidad', valida su contenido
                if (arg.event.classNames.includes('evento-periodicidad')) {
                    console.log("Renderizando evento de periodicidad:", arg.event);
                }

                return { domNodes: [content] }; // Devolver nodos personalizados
            },

            datesSet: function (info) {
                // Verifica si el rango solicitado es diferente del último

            },


            loading: function (isLoading) {
                if (isLoading) {
                    console.log("Cargando eventos...");
                } else {
                    console.log("Eventos cargados.");
                }
            }
        });

        calendar.render(); // Renderizar el calendario
        // Insertar la leyenda manualmente
        const legendHTML = `
     <div class="calendar-legend" style="display: flex;  gap: 10px; align-items: center;">
         <span style="display: flex;  align-items: center; gap: 5px;">
             <span style="width: 15px; height: 15px; background-color: #90CAF9; display: inline-block;"></span>
              Tarea Planificada
         </span> |
         <span style="display: flex; align-items: center; gap: 5px;">
             <span style="width: 15px; height: 15px; background-color: #FFCDD2; display: inline-block;"></span>
              Vencimiento de Tarea
         </span> |
         <span style="display: flex; align-items: center; gap: 5px;">
             <span style="width: 15px; height: 15px; background-color: #FFF59D; display: inline-block;"></span>
              Tarea Periódica (Próxima Generación)
         </span>
     </div>
 `;
        const customLegendButton = calendarEl.querySelector('.fc-customLegend-button');
        if (customLegendButton) {
            customLegendButton.innerHTML = legendHTML;
            customLegendButton.style.cursor = 'default'; // Evitar el puntero de clic
        }
    }
});
function showModal(date) {
    // Crear overlay dinámicamente si no existe
    let overlay = document.getElementById('calendar-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'calendar-overlay';
        overlay.classList.add('calendar-overlay'); // Clase para el overlay
        document.body.appendChild(overlay);
    }

    // Crear modal dinámicamente si no existe
    let modal = document.getElementById('calendar-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'calendar-modal';
        modal.classList.add('calendar-modal'); // Aplicar estilos del CSS
        document.body.appendChild(modal);
    }

    // Limpiar contenido previo
    modal.innerHTML = '';

    // Título del modal
    const modalTitle = document.createElement('h3');
    modalTitle.textContent = `Tareas del ${formatDate(date)}`;
    modalTitle.classList.add('calendar-modal-title'); // Aplicar estilos del CSS
    modal.appendChild(modalTitle);

    // Contenedor dinámico de eventos
    const eventsContainer = document.createElement('div');
    eventsContainer.classList.add('calendar-modal-content'); // Aplicar estilos del CSS
    modal.appendChild(eventsContainer);

    // Cargar eventos desde la API
    fetch(`/api/calendar/events?date=${encodeURIComponent(date)}`)
        .then(response => response.json())
        .then(events => {
            if (events.length === 0) {
                eventsContainer.innerHTML = '<p class="calendar-modal-no-events">No hay tareas para este día.</p>';
                return;
            }

            events.forEach(event => {
                const eventContainer = document.createElement('div');
                eventContainer.classList.add('calendar-modal-event');

                if (event.classNames && Array.isArray(event.classNames)) {
                    event.classNames.forEach(className => eventContainer.classList.add(className));
                }

                if (event.color) {
                    eventContainer.style.backgroundColor = event.color;
                }

                const title = document.createElement('div');
                title.textContent = event.title;
                title.classList.add('fc-event-asunto');

                const client = document.createElement('div');
                client.textContent = event.extendedProps?.cliente || 'Sin Cliente';
                client.classList.add('fc-event-cliente');

                const description = document.createElement('div');
                description.textContent = event.description || '';
                description.classList.add('fc-event-description');

                eventContainer.appendChild(title);
                eventContainer.appendChild(client);
                eventContainer.appendChild(description);

                // Listener para redirigir al detalle de la tarea
                eventContainer.addEventListener('click', () => {
                    // Redirigir usando el id del evento
                    window.location.href = `/tareas?task_id=${event.id}`;
                });

                eventsContainer.appendChild(eventContainer);
            });
        })
        .catch(error => {
            console.error("Error al cargar eventos del día:", error);
            eventsContainer.innerHTML = '<p class="calendar-modal-error">Error al cargar los eventos.</p>';
        });

    // Botón de cierre en un contenedor
    const closeButtonContainer = document.createElement('div');
    closeButtonContainer.classList.add('calendar-modal-close-container'); // Nueva clase para el contenedor

    const closeButton = document.createElement('button');
    closeButton.textContent = 'Cerrar';
    closeButton.classList.add('calendar-modal-close');
    closeButton.addEventListener('click', () => {
        modal.style.display = 'none';
        overlay.style.display = 'none';
    });

    // Añadir el botón al contenedor y luego al modal
    closeButtonContainer.appendChild(closeButton);
    modal.appendChild(closeButtonContainer);

    // Mostrar el modal y el overlay
    modal.style.display = 'block';
    overlay.style.display = 'block';
}


function formatDate(dateString) {
    console.log("Procesando fecha:", dateString);

    if (!dateString) return "N/A";

    // Normalizar la fecha si tiene el formato "DD/MM/YYYY"
    if (/^\d{2}\/\d{2}\/\d{4}$/.test(dateString)) {
        const [day, month, year] = dateString.split("/");
        dateString = `${year}-${month}-${day}`; // Convertir a "YYYY-MM-DD"
    }

    const date = new Date(dateString);

    if (isNaN(date.getTime())) {
        console.warn(`Formato de fecha inválido: ${dateString}`);
        return "Fecha inválida";
    }

    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}
