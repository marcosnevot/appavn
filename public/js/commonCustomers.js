
document.addEventListener('DOMContentLoaded', function () {

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

            // Detectar el contenedor de autocompletar
            const fieldIdMap = {
                tipocliente: 'filter-tipocliente-input',
                clasificacion: 'filter-clasificacion-input',
                situacion: 'filter-situacion-input',
                tributacion: 'filter-tributacion-input',
                nombrefiscal: 'filter-nombrefiscal-input',
                nif: 'filter-nif-input'
            };
            const fieldInputId = fieldIdMap[field] || `filter-${field}-input`; // Usar mapa o fallback dinámico
            const autocompleteContainer = document.querySelector(`#${fieldInputId}`)?.closest('.autocomplete');
            const dropdownList = document.querySelector(`#${field}-list`);
            const inputField = document.querySelector(`#filter-customer-form [name="${field}"]`);

            // Validación específica para nombre_fiscal y nif
            if (field === 'filter-nombrefiscal' || field === 'filter-nif') {
                const dataList = getAutocompleteData(field); // Usa clientesData
                if (autocompleteContainer) {
                    showAutocompleteField(header, autocompleteContainer, dataList, field);
                    return;
                }
            }

            // Si no es nombre_fiscal ni nif, seguir con la lógica habitual
            if (autocompleteContainer) {
                const dataList = getAutocompleteData(field);
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
        if (field === 'tributacion') return tributacionesData; // tributacion
        if (field === 'clasificacion') return clasificacionesData;   // clasificacion
        if (field === 'situacion') return situacionesData;   // situacion
        if (field === 'tipocliente') return tiposData;       // Tipos
        if (field === 'nombrefiscal') {
            return clientesData.map(cliente => ({ nombre_fiscal: cliente.nombre_fiscal }));
        }
        if (field === 'nif') {
            return clientesData.map(cliente => ({ nif: cliente.nif }));
        }
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


            // Calcular posición centrada
            const centerX = rect.left + window.scrollX + rect.width / 2; // Centro horizontal del encabezado
            const inputCloneWidth = finalWidth; // Ancho del `inputClone`

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
                    !header.contains(event.target)
                ) {
                    if (document.body.contains(inputClone)) {
                        inputClone.classList.remove('show');
                        setTimeout(() => {
                            document.body.removeChild(inputClone);
                        }, 100);
                    }
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
            clasificacion: { hidden: '#filter-clasificacion-ids', visible: '#filter-clasificacion-input', selected: '#filter-clasificacion-selected' },
            tributacion: { hidden: '#filter-tributacion-ids', visible: '#filter-tributacion-input', selected: '#filter-tributacion-selected' },
            situacion: { hidden: '#filter-situacion-ids', visible: '#filter-situacion-input', selected: '#filter-situacion-selected' },
            tipocliente: { hidden: '#filter-tipocliente-ids', visible: '#filter-tipocliente-input', selected: '#filter-tipocliente-selected' },
            nombrefiscal: { hidden: '#filter-nombrefiscal-ids', visible: '#filter-nombrefiscal-input', selected: '#filter-nombrefiscal-selected' },
            nif: { hidden: '#filter-nif-ids', visible: '#filter-nif-input', selected: '#filter-nif-selected' }
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
                        clasificacion: { hidden: '#filter-clasificacion-ids', visible: '#filter-clasificacion-input', selected: '#filter-clasificacion-selected' },
                        tributacion: { hidden: '#filter-tributacion-ids', visible: '#filter-tributacion-input', selected: '#filter-tributacion-selected' },
                        situacion: { hidden: '#filter-situacion-ids', visible: '#filter-situacion-input', selected: '#filter-situacion-selected' },
                        tipocliente: { hidden: '#filter-tipocliente-ids', visible: '#filter-tipocliente-input', selected: '#filter-tipocliente-selected' },
                        nombrefiscal: { hidden: '#filter-nombrefiscal-ids', visible: '#filter-nombrefiscal-input', selected: '#filter-nombrefiscal-selected' },
                        nif: { hidden: '#filter-nif-ids', visible: '#filter-nif-input', selected: '#filter-nif-selected' }
                    };

                    // Identificar el campo correspondiente
                    Object.keys(fieldMapping).forEach(field => {
                        if (inputField.id.includes(field)) {
                            const { hidden, visible, selected } = fieldMapping[field];
                            console.log(`Campo detectado para: ${field}`); // Verificar detección del campo

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

                            // Actualizar el campo oculto con el valor seleccionado
                            const mainHiddenInputField = document.querySelector(hidden);
                            if (mainHiddenInputField) {
                                if (field === 'nombrefiscal' || field === 'nif') {
                                    // Asignar el valor de texto en lugar del ID
                                    mainHiddenInputField.value = item[property];
                                } else {
                                    mainHiddenInputField.value = item.id || '';
                                }
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
            clasificacion: 'nombre',
            tributacion: 'nombre',
            situacion: 'nombre',
            tipocliente: 'nombre',
            nombrefiscal: 'nombre_fiscal', // Propiedad específica para clientes
            nif: 'nif' // Propiedad específica para clientes
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
        loadFilteredCustomers(); // Invocar la función existente para actualizar la tabla
    }

});
