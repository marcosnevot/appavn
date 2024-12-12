//Filtros Planificación
function sincronizarBotonesConFecha() {
    const dateRangePicker = document.getElementById('filter-fecha-planificacion');
    const dateRangeValue = dateRangePicker.value || '';
    const [fechaInicio, fechaFin] = dateRangeValue === 'past'
        ? ['past', 'past'] // Caso especial para "Pasadas"
        : dateRangeValue.split(' - ');

    const buttons = document.querySelectorAll('.btn-filter-planificacion');
    let botonSeleccionado = false;

    // Limpiar clases activas de todos los botones
    buttons.forEach(button => button.classList.remove('active', 'active-red'));

    buttons.forEach(button => {
        const botonFecha = button.getAttribute('data-fecha');

        if (botonFecha === 'past' && fechaInicio === 'past' && fechaFin === 'past') {
            // Activar "Pasadas"
            button.classList.add('active-red');
            botonSeleccionado = true;
        } else if (botonFecha === '' && fechaInicio === '' && fechaFin === '') {
            // Activar "Todas"
            button.classList.add('active');
            botonSeleccionado = true;
        } else if (fechaInicio === botonFecha && fechaFin === botonFecha) {
            // Activar fechas específicas
            button.classList.add('active');
            botonSeleccionado = true;
        }
    });

    if (!botonSeleccionado) {
        console.log('No hay botón correspondiente para el rango de fechas seleccionado.');
    }
}

// Función para restablecer el filtro rápido de planificación
function resetFiltroRapidoPlanificacion() {
    // Obtener el botón actualmente activo
    const activeButton = document.querySelector('.btn-filter-planificacion.active, .btn-filter-planificacion.active-red');

    // Si ya hay un botón activo, no hacer nada
    if (activeButton) {
        console.log('No se resetea el filtro rápido porque ya hay un botón activo.');
        return;
    }

    // Si no hay botón activo, establecer "Todas" como activo
    const botonTodas = document.querySelector('.btn-filter-planificacion[data-fecha=""]');
    if (botonTodas) {
        botonTodas.classList.add('active');
    }
}



document.addEventListener('DOMContentLoaded', function () {




    $(document).ready(function () {
        const today = new Date().toISOString().split('T')[0];

        // Inicialización para el campo del formulario (Filter Form)
        const formFechaPlanificacionInput = $('#filter-fecha-planificacion');

        formFechaPlanificacionInput.daterangepicker({
            autoUpdateInput: true,
            startDate: today,
            endDate: today,
            locale: {
                format: 'YYYY-MM-DD',
                separator: ' - ',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Personalizado',
            },
            drops: 'up', // Mostrar por encima
        });



        formFechaPlanificacionInput.on('apply.daterangepicker', function (ev, picker) {
            ev.stopPropagation(); // Evitar que el formulario se cierre
            $(this).val(`${picker.startDate.format('YYYY-MM-DD')} - ${picker.endDate.format('YYYY-MM-DD')}`);
            // Las fechas seleccionadas ya se reflejan en el campo input del formulario

        });

        formFechaPlanificacionInput.on('cancel.daterangepicker', function () {
            $(this).val(''); // Limpiar el campo si se cancela la selección
        });

        // Inicializar el campo vacío
        formFechaPlanificacionInput.val(''); // Campo sin valor inicial (null en términos funcionales)
    });

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
        });

        // Crear el botón de "Pasadas"
        const botonPasadas = document.createElement('button');
        botonPasadas.type = 'button';
        botonPasadas.classList.add('btn-filter-planificacion', 'btn-pasadas'); // Añadimos una clase específica
        botonPasadas.textContent = 'Pasadas';
        botonPasadas.setAttribute('data-fecha', 'past');
        botonPasadas.onclick = () => filtrarTareasPorPlanificacion('past');
        planificacionFilterContainer.appendChild(botonPasadas);
        
        
        sincronizarBotonesConFecha();

        // Seleccionar "Todas" inicialmente
        const botonTodas = document.querySelector('.btn-filter-planificacion[data-fecha=""]');
        if (botonTodas) {
            botonTodas.classList.add('active');
        }

        // Llamar a la sincronización después de generar todos los botones

    }





    // Función para gestionar el filtrado de tareas
    function filtrarTareasPorPlanificacion(fecha, sortKey = 'fecha_planificacion', sortDirection = 'asc') {
        console.log('filtrarTareasPorPlanificacion llamada desde:', new Error().stack);

        // Actualizar la interfaz de botones
        document.querySelectorAll('.btn-filter-planificacion').forEach(btn => {
            btn.classList.remove('active', 'active-red'); // Limpiar clases activas y rojas
        });



        // Actualizar el campo date range picker
        const dateRangePicker = document.getElementById('filter-fecha-planificacion');
        if (fecha === 'past') {
            // Establecer el valor del daterangepicker como "past"
            dateRangePicker.value = 'past';
        } else if (fecha === '') {
            // Vaciar el daterangepicker para "Todas"
            dateRangePicker.value = '';
        } else {
            // Establecer el rango de fechas seleccionado
            dateRangePicker.value = `${fecha} - ${fecha}`;
        }

        sincronizarBotonesConFecha(); // Actualizar la selección de botones
        loadFilteredTasks(1, sortKey, sortDirection); // Cargar tareas con los filtros
    }


    // Generar los botones de filtro de planificación al cargar la página
    generarBotonesFiltroPlanificacion();


    // Filtros rápidos en encabezados

    // Añadir listeners a los encabezados de tabla con `data-sort-key`
    const headers = document.querySelectorAll('th[data-field]');

    headers.forEach(header => {
        header.addEventListener('contextmenu', function (event) {
            event.preventDefault();
            const field = header.getAttribute('data-field'); // Toma solo data-field
            if (!field) {
                console.error('El encabezado no tiene el atributo data-field.');
                return;
            }

            const dropdownList = document.querySelector(`#${field}-list`);
            const inputField = document.querySelector(`#filter-task-form [name="${field}"]`);
            const autocompleteContainer = document.querySelector(`#filter-${field}-input`)?.closest('.autocomplete');

            if (autocompleteContainer) {
                const dataList = getAutocompleteData(field); // Usa data-field para datos
                showAutocompleteField(header, autocompleteContainer, dataList, field);
            } else if (dropdownList) {
                showDropdownList(header, dropdownList, field);
            } else if (inputField) {
                showInputField(header, inputField);
            } else {
                console.error(`No se encontró ningún elemento asociado para: ${field}`);
            }
        });
    });


    // Función para obtener los datos del autocompletar según el campo
    function getAutocompleteData(field) {
        if (field === 'cliente') return clientesData; // Clientes
        if (field === 'asunto') return asuntosData;   // Asuntos
        if (field === 'tipo') return tiposData;       // Tipos
        console.error(`No se encontró un conjunto de datos para el campo: ${field}`);
        return [];
    }







    function showDropdownList(header, dropdownList, field) {
        hideAllDropdownLists(); // Ocultar otras listas desplegadas

        // Clonar visualmente la lista desplegable
        const mirroredList = dropdownList.cloneNode(true);
        mirroredList.id = `${dropdownList.id}-mirrored`; // Evitar conflictos de ID
        mirroredList.style.position = 'absolute';
        mirroredList.style.display = 'block';
        mirroredList.classList.add('autocomplete-container'); // Asegurar que tenga la clase base

        // Ajustar posición y ancho basado en el encabezado
        const updatePosition = () => {
            const rect = header.getBoundingClientRect();
            mirroredList.style.top = `${rect.bottom + window.scrollY - 1}px`;
            mirroredList.style.left = `${rect.left + window.scrollX - 25}px`; // Desplazar 10px a la izquierda
            mirroredList.style.width = `${rect.width + 50}px`; // Incrementar ancho por 20px
        };
        updatePosition();

        const scrollContainer = document.querySelector('.table-container'); // Contenedor de scroll

        // Cerrar desplegable al hacer scroll
        const handleScroll = () => {
            if (document.body.contains(mirroredList)) {
                document.body.removeChild(mirroredList);
            }
            scrollContainer.removeEventListener('scroll', handleScroll);
        };
        scrollContainer.addEventListener('scroll', handleScroll);

        // Añadir al documento
        document.body.appendChild(mirroredList);

        // Activar la animación
        setTimeout(() => {
            mirroredList.classList.add('show');
        }, 10); // Retraso mínimo para que CSS tome el cambio

        // Manejar las selecciones
        const checkboxes = mirroredList.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const originalCheckbox = dropdownList.querySelector(`#${checkbox.id}`);
                if (originalCheckbox) {
                    originalCheckbox.checked = checkbox.checked; // Sincronizar estado del checkbox
                }
                updateHiddenField(field, dropdownList);
                applyFilterOnChange(); // Activar el filtrado automáticamente
            });
            // Manejar clic en el texto asociado
            const label = mirroredList.querySelector(`label[for="${checkbox.id}"]`);
            if (label) {
                label.addEventListener('click', function (event) {
                    event.preventDefault(); // Evitar comportamiento predeterminado
                    checkbox.checked = !checkbox.checked; // Alternar el estado del checkbox
                    checkbox.dispatchEvent(new Event('change')); // Activar el evento `change`
                });
            }
        });

        // Manejar clic fuera con verificación
        setTimeout(() => {
            document.addEventListener('click', function handleClickOutside(event) {
                if (
                    !mirroredList.contains(event.target) &&
                    !header.contains(event.target)
                ) {
                    if (document.body.contains(mirroredList)) {
                        mirroredList.classList.remove('show');
                        setTimeout(() => {
                            document.body.removeChild(mirroredList);
                        }, 100);
                    }
                    document.removeEventListener('click', handleClickOutside);
                }
            });
        }, 0);
    }

    function showInputField(header, inputField) {
        hideAllDropdownLists(); // Ocultar cualquier lista desplegada

        // Clonar visualmente el input
        const inputClone = inputField.cloneNode(true);
        inputClone.id = `${inputField.id}-mirrored`; // Evitar conflictos de ID
        inputClone.style.position = 'absolute';
        inputClone.classList.add('autocomplete-container'); // Asegurar que tenga la clase base

        const updatePosition = () => {
            const rect = header.getBoundingClientRect(); // Obtener las dimensiones y posición del encabezado
            const computedStyles = window.getComputedStyle(header); // Obtener los estilos aplicados al encabezado

            // Calcular el ancho visible del encabezado considerando `max-width`
            const actualWidth = header.offsetWidth; // Ancho visible del encabezado
            const maxWidth = parseFloat(computedStyles.maxWidth) || actualWidth; // Respetar `max-width` si está definido
            const finalWidth = Math.min(actualWidth, maxWidth); // Determinar el ancho efectivo

            // Calcular extraWidth dinámicamente según la clase de la columna
            let extraWidth = 50; // Valor por defecto
            if (header.classList.contains('col-descripcion') || header.classList.contains('col-observaciones')) {
                const padding = parseFloat(computedStyles.paddingLeft) + parseFloat(computedStyles.paddingRight);
                const border = parseFloat(computedStyles.borderLeftWidth) + parseFloat(computedStyles.borderRightWidth);
                extraWidth = -rect.width * 0.1 - padding - border; // Ajuste basado en proporción
            }

            // Calcular posición centrada
            const centerX = rect.left + window.scrollX + rect.width / 2; // Centro horizontal del encabezado
            const inputCloneWidth = finalWidth + extraWidth; // Ancho del `inputClone`

            // Configurar el estilo del input clonado
            inputClone.style.position = 'absolute';
            inputClone.style.top = `${rect.bottom + window.scrollY}px`; // Posicionarlo justo debajo del encabezado
            inputClone.style.left = `${centerX - inputCloneWidth / 2}px`; // Centrar horizontalmente
            inputClone.style.width = `${inputCloneWidth}px`; // Establecer el ancho
            inputClone.style.height = '50px'; // Altura consistente
        };
        updatePosition();



        const scrollContainer = document.querySelector('.table-container'); // Contenedor de scroll

        // Cerrar input al hacer scroll
        const handleScroll = () => {
            if (document.body.contains(inputClone)) {
                document.body.removeChild(inputClone);
            }
            scrollContainer.removeEventListener('scroll', handleScroll);
        };
        scrollContainer.addEventListener('scroll', handleScroll);

        // Añadir al documento
        document.body.appendChild(inputClone);

        // Activar la animación
        setTimeout(() => {
            inputClone.classList.add('show');
        }, 10); // Retraso mínimo para que CSS tome el cambio

        // Si el input tiene asociado un daterangepicker, inicializarlo en el input clonado
        if (inputField.id === 'filter-fecha-planificacion') {
            $(inputClone).daterangepicker({
                autoUpdateInput: true,
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: ' - ',
                    applyLabel: 'Aplicar',
                    cancelLabel: 'Cancelar',
                },
                drops: 'down',
            });

            // Manejar la selección de fechas
            $(inputClone).on('apply.daterangepicker', function (ev, picker) {
                ev.stopPropagation(); // Evitar cierre prematuro
                inputField.value = `${picker.startDate.format('YYYY-MM-DD')} - ${picker.endDate.format('YYYY-MM-DD')}`;
                applyFilterOnChange(); // Activar el filtro automáticamente
            });

            // Manejar la cancelación
            $(inputClone).on('cancel.daterangepicker', function () {
                inputField.value = ''; // Limpiar el campo original
                applyFilterOnChange(); // Activar el filtro automáticamente
            });

            // Inicializar valor vacío
            $(inputClone).val('');
        }

        // Sincronizar valores en tiempo real
        inputClone.addEventListener('input', function () {
            inputField.value = inputClone.value; // Actualizar el valor del input original
            applyFilterOnChange(); // Activar el filtrado automáticamente
        });

        // Manejar clic fuera con verificación
        setTimeout(() => {
            document.addEventListener('click', function handleClickOutside(event) {
                if (
                    !inputClone.contains(event.target) &&
                    !header.contains(event.target) &&
                    !event.target.closest('.daterangepicker') // Prevenir cierre al interactuar con el calendario
                ) {
                    if (document.body.contains(inputClone)) {
                        inputClone.classList.remove('show');
                        setTimeout(() => {
                            document.body.removeChild(inputClone);
                        }, 100);
                    }
                    $(inputClone).data('daterangepicker')?.remove(); // Eliminar instancia de daterangepicker
                    scrollContainer.removeEventListener('scroll', handleScroll);
                    document.removeEventListener('click', handleClickOutside);
                }
            });
        }, 0);

        setTimeout(() => {
            inputClone.focus(); // Foco automático tras un pequeño retraso
        }, 50); // Retraso mínimo para asegurar que está en el DOM // Foco automático en el input clonado
    }

    function showAutocompleteField(header, autocompleteContainer, dataList, field) {
        console.log('Autocompletar detectado');
        hideAllDropdownLists(); // Ocultar otras listas desplegadas

        // Clonar el contenedor completo del autocompletar
        const clonedContainer = autocompleteContainer.cloneNode(true);
        clonedContainer.id = `${autocompleteContainer.id}-mirrored`; // Evitar conflictos de ID
        clonedContainer.classList.add('autocomplete-container'); // Asegurar que tenga la clase base
        clonedContainer.style.position = 'absolute';

        // Eliminar el div de `selected-items-container` del clon
        const selectedContainerClone = clonedContainer.querySelector('.selected-items-container');
        if (selectedContainerClone) {
            clonedContainer.removeChild(selectedContainerClone);
        }

        // Ajustar posición y ancho basado en el encabezado
        const updatePosition = () => {
            const rect = header.getBoundingClientRect();
            clonedContainer.style.top = `${rect.bottom + window.scrollY - 5}px`;
            clonedContainer.style.left = `${rect.left + window.scrollX - 10}px`;
            clonedContainer.style.width = `${rect.width + 50}px`; // Ajustar al ancho del encabezado

        };

        updatePosition();

        const scrollContainer = document.querySelector('.table-container'); // Contenedor de scroll
        // Cerrar input al hacer scroll
        const handleScroll = () => {
            if (document.body.contains(clonedContainer)) {
                document.body.removeChild(clonedContainer);
            }
            scrollContainer.removeEventListener('scroll', handleScroll);
        };
        scrollContainer.addEventListener('scroll', handleScroll);

        // Añadir al documento
        document.body.appendChild(clonedContainer);

        // Activar la animación
        setTimeout(() => {
            clonedContainer.classList.add('show');
        }, 10); // Retraso mínimo para que CSS tome el cambio

        const input = clonedContainer.querySelector('.autocomplete-input');
        const list = clonedContainer.querySelector('.autocomplete-list');
        const hiddenInput = clonedContainer.querySelector('input[type="hidden"]'); // Campo oculto del contenedor clonado
        list.innerHTML = ''; // Limpiar cualquier lista previa

        // Definir la propiedad según el campo
        const property = getAutocompleteProperty(field);
        if (!property) {
            console.error(`No se puede proceder con el autocompletar para el campo: ${field}`);
            return;
        }

        // Mapeo de identificadores para optimizar la lógica
        const fieldMapping = {
            cliente: { hidden: '#filter-cliente-ids', visible: '#filter-cliente-input', selected: '#filter-cliente-selected' },
            asunto: { hidden: '#filter-asunto-ids', visible: '#filter-asunto-input', selected: '#filter-asunto-selected' },
            tipo: { hidden: '#filter-tipo-ids', visible: '#filter-tipo-input', selected: '#filter-tipo-selected' }
        };

        // Manejar la entrada del usuario
        input.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            console.log('Valor del input clonado:', query);

            if (!query) {
                // Ocultar la lista y contraer el contenedor
                list.style.display = 'none';
                clonedContainer.style.minHeight = '50px'; // Contraer si no hay resultados

                // Restablecer campos ocultos y visibles dinámicamente
                Object.keys(fieldMapping).forEach(field => {
                    if (input.id.includes(field)) {
                        const { hidden, visible, selected } = fieldMapping[field];

                        // Restablecer campo oculto
                        const mainHiddenInputField = document.querySelector(hidden);
                        if (mainHiddenInputField) {
                            mainHiddenInputField.value = '';
                            console.log(`Campo oculto principal restablecido: ${mainHiddenInputField.name}`);
                        }

                        // Restablecer campo visible
                        const mainVisibleInputField = document.querySelector(visible);
                        if (mainVisibleInputField) {
                            mainVisibleInputField.value = '';
                            console.log(`Campo visible principal restablecido: ${mainVisibleInputField.id}`);
                        }

                        // Limpiar el contenedor de "selected-items" del formulario original
                        const selectedContainer = document.querySelector(selected);
                        if (selectedContainer) {
                            selectedContainer.innerHTML = ''; // Limpiar cualquier selección previa
                            console.log(`Selected items limpiados en: ${selected}`);
                        }
                    }
                });

                // Aplicar el filtro para mostrar todos los resultados
                applyFilterOnChange();
                return;
            }

            // Filtrar y renderizar resultados
            const filteredData = dataList.filter(item =>
                item && item[property] && item[property].toLowerCase().includes(query)
            );
            renderAutocompleteList(list, filteredData, property, input, hiddenInput, clonedContainer);
        });

        // Manejar la tecla "Enter"
        input.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevenir el comportamiento predeterminado de "Enter"

                const query = input.value.trim(); // Obtener el valor del input
                if (query) {
                    // Asignar el valor al filtro correspondiente
                    if (fieldMapping[field]) {
                        const { hidden, visible, selected } = fieldMapping[field];
                        const mainHiddenInputField = document.querySelector(hidden);
                        const mainVisibleInputField = document.querySelector(visible);

                        if (mainHiddenInputField) {
                            mainHiddenInputField.value = query; // Asignar valor al campo oculto
                        }
                        if (mainVisibleInputField) {
                            mainVisibleInputField.value = query; // Asignar valor al campo visible
                        }

                    }

                    console.log(`Filtro aplicado para ${field}:`, query);

                    // Ejecutar la función de filtrado
                    applyFilterOnChange();

                    // Cerrar el input clonado
                    clonedContainer.classList.remove('show');
                    setTimeout(() => {
                        document.body.removeChild(clonedContainer);
                    }, 100);
                }
            }
        });

        // Manejar clic fuera con verificación
        setTimeout(() => {
            document.addEventListener('click', function handleClickOutside(event) {
                if (
                    !clonedContainer.contains(event.target) &&
                    !header.contains(event.target)
                ) {
                    if (document.body.contains(clonedContainer)) {
                        clonedContainer.classList.remove('show');
                        setTimeout(() => {
                            document.body.removeChild(clonedContainer);
                        }, 100);
                    }
                    document.removeEventListener('click', handleClickOutside);
                    scrollContainer.removeEventListener('scroll', handleScroll);
                    document.removeEventListener('click', handleClickOutside);
                }
            });
        }, 0);

        setTimeout(() => {
            input.focus(); // Foco automático tras un pequeño retraso
        }, 50); // Retraso mínimo para asegurar que está en el DOM
    }



    function renderAutocompleteList(list, data, property, inputField, hiddenInputField, autocompleteContainer) {
        list.innerHTML = ''; // Limpia la lista
        if (data.length === 0) {
            list.style.display = 'none';
            if (autocompleteContainer) {
                autocompleteContainer.style.maxHeight = '50px'; // Contraer si no hay resultados
            }
            return;
        }

        // Crear y agregar elementos a la lista
        data.forEach(item => {
            if (item && item[property]) {
                const li = document.createElement('li');
                li.textContent = item[property]; // Mostrar el valor de la propiedad dinámica
                li.classList.add('autocomplete-item');
                li.style.padding = '8px'; // Espaciado para los elementos

                li.addEventListener('click', () => {
                    // Mapeo de identificadores para optimizar la lógica
                    const fieldMapping = {
                        cliente: { hidden: '#filter-cliente-ids', visible: '#filter-cliente-input', selected: '#filter-cliente-selected' },
                        asunto: { hidden: '#filter-asunto-ids', visible: '#filter-asunto-input', selected: '#filter-asunto-selected' },
                        tipo: { hidden: '#filter-tipo-ids', visible: '#filter-tipo-input', selected: '#filter-tipo-selected' }
                    };

                    // Identificar el campo correspondiente
                    Object.keys(fieldMapping).forEach(field => {
                        if (inputField.id.includes(field)) {
                            const { hidden, visible, selected } = fieldMapping[field];

                            // Reflejar la selección en el contenedor de "selected items"
                            const selectedContainer = document.querySelector(selected);
                            if (selectedContainer) {
                                selectedContainer.innerHTML = ''; // Limpiar cualquier selección previa
                                const selectedItem = document.createElement('div');
                                selectedItem.classList.add('selected-item');
                                selectedItem.textContent = item[property];

                                // Botón para eliminar el ítem seleccionado
                                const removeBtn = document.createElement('button');
                                removeBtn.textContent = '×';
                                removeBtn.classList.add('remove-item');
                                removeBtn.addEventListener('click', () => {
                                    selectedContainer.innerHTML = ''; // Limpiar el contenedor
                                    const mainHiddenInputField = document.querySelector(hidden);
                                    if (mainHiddenInputField) {
                                        mainHiddenInputField.value = ''; // Limpiar el campo oculto
                                    }
                                });

                                selectedItem.appendChild(removeBtn);
                                selectedContainer.appendChild(selectedItem);
                            }

                            // Actualizar el campo oculto con el ID del elemento seleccionado
                            const mainHiddenInputField = document.querySelector(hidden);
                            if (mainHiddenInputField) {
                                mainHiddenInputField.value = item.id || '';
                                console.log(`Campo oculto actualizado: ${mainHiddenInputField.name} = ${mainHiddenInputField.value}`);
                            }

                            // Mantener funcionalidad de búsqueda
                            const mainVisibleInputField = document.querySelector(visible);
                            if (mainVisibleInputField) {
                                mainVisibleInputField.value = item[property];
                                applyFilterOnChange(); // Aplicar el filtro inmediatamente
                            }
                        }
                    });

                    // Eliminar el contenedor después de la selección
                    if (autocompleteContainer && autocompleteContainer.parentNode) {
                        autocompleteContainer.parentNode.removeChild(autocompleteContainer);
                    }
                    applyFilterOnChange(); // Aplicar el filtro inmediatamente

                    // Limpiar el input visible
                    inputField.value = '';

                    // Ocultar la lista
                    list.style.display = 'none';
                });

                list.appendChild(li);
            }
        });

        // Mostrar lista
        list.style.display = 'block';
        list.style.marginTop = '5px';
        list.style.marginLeft = '-5px';

        // Ajustar altura dinámica del contenedor
        if (autocompleteContainer) {
            autocompleteContainer.style.minHeight = '200px'; // Fijar altura al escribir
            autocompleteContainer.style.overflowY = 'auto'; // Activar scroll si es necesario
        }
    }

    // Mapeo de propiedades según el campo
    function getAutocompleteProperty(field) {

        const fieldToPropertyMap = {
            cliente: 'nombre_fiscal',
            asunto: 'nombre',
            tipo: 'nombre'
        };

        if (!fieldToPropertyMap[field]) {
            console.error(`No se encontró una propiedad para el campo: ${field}`);
            return null; // O lanza un error si quieres forzar la definición
        }

        return fieldToPropertyMap[field];
    }





    function hideAllDropdownLists() {
        const mirroredLists = document.querySelectorAll('[id$="-mirrored"]'); // Identificar todas las listas clonadas
        mirroredLists.forEach(list => {
            list.remove(); // Eliminar clones del DOM
        });
    }

    function updateHiddenField(field, dropdownList) {
        const hiddenField = document.querySelector(`#${field}-ids`); // Buscar el campo oculto relacionado
        const selectedValues = [];

        // Recopilar valores seleccionados
        const checkboxes = dropdownList.querySelectorAll('input[type="checkbox"]:checked');
        checkboxes.forEach(checkbox => {
            // Si el campo es "facturable", convertir 1/0 a true/false, de lo contrario usar el valor original
            const value = field === "facturable" ? (checkbox.value === "1" ? true : false) : checkbox.value;
            selectedValues.push(value);
        });

        // Actualizar el campo oculto con los valores seleccionados
        if (hiddenField) {
            hiddenField.value = selectedValues.join(','); // Guardar valores como lista separada por comas
            console.log(`Campo oculto actualizado (${field}-ids):`, hiddenField.value); // Verifica el valor
        }
    }


    function applyFilterOnChange() {
        console.log("Aplicando filtro automáticamente.");
        loadFilteredTasks(); // Invocar la función existente para actualizar la tabla
    }

});


// Edición rápida desde la tabla

function initializeContextMenu(columnClass, dataKey, options) {
    const tableBody = document.querySelector('table tbody');

    tableBody.addEventListener('contextmenu', function (event) {
        const targetCell = event.target;

        // Verificar si el clic fue en una celda de la columna especificada
        if (targetCell.classList.contains(columnClass)) {
            event.preventDefault(); // Prevenir el menú contextual predeterminado

            // Limpiar cualquier otro select abierto
            const existingSelect = document.querySelector('.dynamic-select');
            if (existingSelect) existingSelect.remove();

            // Crear un menú desplegable (select)
            const currentValue = targetCell.getAttribute(`data-${dataKey}`);
            const taskId = targetCell.getAttribute('data-task-id');

            const select = document.createElement('select');
            select.classList.add('dynamic-select');
            options.forEach(optionValue => {
                const option = document.createElement('option');
                option.value = optionValue;

                // Etiquetas personalizadas por columna
                if (columnClass === 'facturable-cell') {
                    option.textContent = optionValue === '1' ? 'SI' : 'NO';
                } else if (columnClass === 'facturado-cell' || columnClass === 'estado-cell') {
                    option.textContent = optionValue; // Mostrar el valor tal cual (e.g., "PENDIENTE", "SI", "NO", etc.)
                }

                // Seleccionar la opción actual
                if (optionValue === currentValue) {
                    option.selected = true;
                }

                select.appendChild(option);
            });



            // Posicionar el select cerca del cursor
            select.style.position = 'absolute';
            select.style.left = `${event.pageX}px`;
            select.style.top = `${event.pageY}px`;
            document.body.appendChild(select);

            // Manejar el cambio de selección
            select.addEventListener('change', function () {
                const newValue = select.value;
                updateTaskColumn(taskId, newValue, targetCell, select, dataKey);
            });

            // Cerrar el select al hacer clic fuera
            document.addEventListener('click', function closeSelect(e) {
                if (!select.contains(e.target)) {
                    select.remove();
                    document.removeEventListener('click', closeSelect);
                }
            });
            // Cerrar el select al hacer scroll en la tabla
            const scrollContainer = document.querySelector('.table-container'); // Contenedor de scroll
            // Cerrar select al hacer scroll
            const handleScroll = () => {
                if (document.body.contains(select)) {
                    document.body.removeChild(select);
                }
                scrollContainer.removeEventListener('scroll', handleScroll);
            };
            scrollContainer.addEventListener('scroll', handleScroll);
        }
    });
}

function initializeDatePickerContextMenu(columnClass, dataKey) {
    const tableBody = document.querySelector('table tbody');

    tableBody.addEventListener('contextmenu', function (event) {
        const targetCell = event.target;

        // Verificar si el clic fue en una celda de la columna especificada
        if (targetCell.classList.contains(columnClass)) {
            event.preventDefault(); // Prevenir el menú contextual predeterminado

            // Limpiar cualquier otro editor abierto
            const existingPicker = document.querySelector('.dynamic-datepicker');
            if (existingPicker) existingPicker.remove();

            // Obtener la fecha actual del atributo data
            const currentValue = targetCell.getAttribute(`data-${dataKey}`);
            const taskId = targetCell.getAttribute('data-task-id');

            // Crear el input date
            const dateInput = document.createElement('input');
            dateInput.type = 'date';
            dateInput.classList.add('dynamic-datepicker');
            dateInput.value = currentValue || ''; // Establecer la fecha actual si existe

            // Posicionar el input cerca del cursor
            dateInput.style.position = 'absolute';
            dateInput.style.left = `${event.pageX}px`;
            dateInput.style.top = `${event.pageY}px`;
            document.body.appendChild(dateInput);

            // Enfocar el input para abrir el datepicker
            dateInput.focus();

            // Manejar el cambio de fecha
            dateInput.addEventListener('change', function () {
                const newValue = dateInput.value;
                updateTaskColumn(taskId, newValue, targetCell, dateInput, dataKey);
            });

            // Cerrar el input al hacer clic fuera
            const closeDatePicker = (e) => {
                if (!dateInput.contains(e.target)) {
                    dateInput.remove();
                    document.removeEventListener('click', closeDatePicker);
                }
            };
            document.addEventListener('click', closeDatePicker);

            // Cerrar el input al hacer scroll
            const scrollContainer = document.querySelector('.table-container');
            const handleScroll = () => {
                if (document.body.contains(dateInput)) {
                    dateInput.remove();
                }
                scrollContainer.removeEventListener('scroll', handleScroll);
            };
            scrollContainer.addEventListener('scroll', handleScroll);
        }
    });
}


function updateTaskColumn(taskId, newValue, targetCell, select, columnKey) {
    const formData = new FormData();
    formData.append('_method', 'PUT'); // Simular un método PUT
    formData.append(columnKey, newValue);

    fetch(`/tareas/${taskId}`, {
        method: 'POST', // Usamos POST para enviar datos al backend
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    console.error("Error en la respuesta:", err);
                    throw new Error('Error en la respuesta del servidor');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Actualizar la celda con el nuevo valor
                targetCell.setAttribute(`data-${columnKey}`, newValue);
                targetCell.textContent = newValue;

                loadTasks(1, 'fecha_planificacion', 'asc');
                // Mostrar notificación de éxito
                showNotification(`Columna ${columnKey} actualizada correctamente`, 'info');
            } else {
                console.error('Errores de validación:', data.errors);
                showNotification(`Error al actualizar la columna ${columnKey}`, 'error');
            }
        })
        .catch(error => {
            console.error(`Error al actualizar la columna ${columnKey}:`, error);
            showNotification(`Error al actualizar la columna ${columnKey}`, 'error');
        })
        .finally(() => {
            // Remover el select una vez completado
            select.remove();
        });
}


