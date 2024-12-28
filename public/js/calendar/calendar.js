document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    let lastRange = { start: null, end: null }; // Variable para almacenar el último rango solicitado

    if (calendarEl) {

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es', // Idioma en español

            headerToolbar: {
                left: 'prev,next today', // Botones de navegación
                center: 'title', // Título del mes
                right: 'customLegend', // Añadimos un botón personalizado
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
              Tarea Periódica
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
