document.addEventListener('DOMContentLoaded', function () {
    console.log('El script customer-modal.js ha sido cargado correctamente.');


    const modalContent = document.getElementById('customer-detail-modal-content');
    const editCustomerFormContainer = document.getElementById('edit-customer-form-container'); // Cambiado a la nueva ID del contenedor
    const editCustomerForm = document.getElementById('edit-customer-form');

    const btnEditCustomerForm = document.getElementById('btn-edit-customer-form');


    // Asignar Usuarios a un customer (formulario de edición)
    const userSelectEdit = document.getElementById('user-select-edit');
    const userListEdit = document.getElementById('user-list-edit');
    const selectedUsersContainerEdit = document.getElementById('selected-users-edit');
    const userIdsInputEdit = document.getElementById('user-ids-edit');
    let selectedUsersEdit = [];
    let currentFocusEdit = -1;

    if (!modalContent) {
        console.error('Modal content not found!');
        return;
    }

    // Observador de cambios para detectar cuando se carga una nueva tarea en el modal
    const observer = new MutationObserver(() => {
        const deleteButton = document.getElementById('delete-customer-button');
        const editButton = document.getElementById('edit-customer-button');



        const closeEditButton = document.getElementById('close-edit-customer-form');

        // Asignar evento al botón de borrar
        if (deleteButton) {
            deleteButton.addEventListener('click', () => {
                const customerId = deleteButton.getAttribute('data-customer-id');
                const confirmDelete = confirm('¿Estás seguro de que quieres borrar este cliente?');

                if (confirmDelete) {
                    deleteCustomer(customerId);
                }
            });
        }

        // Asignar evento al botón de editar
        if (editButton && editCustomerForm) {
            editButton.addEventListener('click', () => {
                const customerId = deleteButton.getAttribute('data-customer-id'); // Obtener el ID de la tarea

                // Mostrar el formulario de edición
                editCustomerFormContainer.style.display = 'block';
                setTimeout(() => {
                    editCustomerFormContainer.classList.remove('hide');
                    editCustomerFormContainer.classList.add('show');
                }, 10);

                fetch(`/clientes/${customerId}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        const customer = data.customer;
                        const tiposClientes = data.tipoCliente;
                        const clasificaciones = data.clasificaciones;
                        const tributaciones = data.tributaciones;
                        const situaciones = data.situaciones;

                        // Verificar que los elementos existen en el DOM antes de rellenar
                        const nombreFiscalInput = document.querySelector('input[name="nombre_fiscalEdit"]');
                        const nifInput = document.querySelector('input[name="nifEdit"]');
                        const movilInput = document.querySelector('input[name="movilEdit"]');
                        const segundo_telefonoInput = document.querySelector('input[name="segundo_telefonoEdit"]');
                        const persona_contactoInput = document.querySelector('input[name="persona_contactoEdit"]');
                        const fijoInput = document.querySelector('input[name="fijoEdit"]');
                        const emailInput = document.querySelector('input[name="emailEdit"]');
                        const direccionInput = document.querySelector('input[name="direccionEdit"]');
                        const codigoPostalInput = document.querySelector('input[name="codigo_postalEdit"]');
                        const poblacionInput = document.querySelector('input[name="poblacionEdit"]');
                        const datosBancariosTextarea = document.querySelector('textarea[name="datos_bancariosEdit"]');
                        const responsableSelect = document.querySelector('select[name="responsable_idEdit"]');
                        const tipoClienteSelect = document.querySelector('select[name="tipo_clienteEdit"]');
                        const clasificacionSelect = document.querySelector('select[name="clasificacionEdit"]');
                        const tributacionSelect = document.querySelector('select[name="tributacionEdit"]');
                        const situacionSelect = document.querySelector('select[name="situacionEdit"]');
                        const subclaseInput = document.querySelector('input[name="subclaseEdit"]');
                        const puntajeInput = document.querySelector('input[name="puntajeEdit"]');
                        const codigoSageInput = document.querySelector('input[name="codigo_sageEdit"]');
                        const customerIdInput = document.getElementById('customer_id');

                        // Asignar valores de manera segura
                        if (nombreFiscalInput) nombreFiscalInput.value = customer.nombre_fiscal || '';
                        if (nifInput) nifInput.value = customer.nif || '';
                        if (movilInput) movilInput.value = customer.movil || '';
                        if (segundo_telefonoInput) segundo_telefonoInput.value = customer.segundo_telefono || '';
                        if (persona_contactoInput) persona_contactoInput.value = customer.persona_contacto || '';
                        if (fijoInput) fijoInput.value = customer.fijo || '';
                        if (emailInput) emailInput.value = customer.email || '';
                        if (direccionInput) direccionInput.value = customer.direccion || '';
                        if (codigoPostalInput) codigoPostalInput.value = customer.codigo_postal || '';
                        if (poblacionInput) poblacionInput.value = customer.poblacion || '';
                        if (datosBancariosTextarea) datosBancariosTextarea.value = customer.datos_bancarios || '';
                        if (responsableSelect) responsableSelect.value = customer.responsable_id || '';
                        if (subclaseInput) subclaseInput.value = customer.subclase || '';
                        if (puntajeInput) puntajeInput.value = customer.puntaje || '';
                        if (codigoSageInput) codigoSageInput.value = customer.codigo_sage || '';
                        if (customerIdInput) customerIdInput.value = customer.id;

                        // Llenar selects y seleccionar valores predeterminados
                        tipoClienteSelect.innerHTML = `<option value="">Sin Tipo de Cliente</option>`;
                        tiposClientes.forEach(tipo => {
                            tipoClienteSelect.innerHTML += `<option value="${tipo.id}" ${customer.tipo_cliente_id === tipo.id ? 'selected' : ''}>${tipo.nombre}</option>`;
                        });

                        clasificacionSelect.innerHTML = `<option value="">Sin Clasificación</option>`;
                        clasificaciones.forEach(clasificacion => {
                            clasificacionSelect.innerHTML += `<option value="${clasificacion.id}" ${customer.clasificacion_id === clasificacion.id ? 'selected' : ''}>${clasificacion.nombre}</option>`;
                        });

                        tributacionSelect.innerHTML = `<option value="">Sin Tributación</option>`;
                        tributaciones.forEach(tributacion => {
                            tributacionSelect.innerHTML += `<option value="${tributacion.id}" ${customer.tributacion_id === tributacion.id ? 'selected' : ''}>${tributacion.nombre}</option>`;
                        });

                        situacionSelect.innerHTML = `<option value="">Sin Situación</option>`;
                        situaciones.forEach(situacion => {
                            situacionSelect.innerHTML += `<option value="${situacion.id}" ${customer.situacion_id === situacion.id ? 'selected' : ''}>${situacion.nombre}</option>`;
                        });

                        // Cargar y asignar los usuarios seleccionados
                        if (customer.users && customer.users.length > 0) {
                            selectedUsersEdit = customer.users.map(user => ({ id: user.id, name: user.name }));
                            updateSelectedUsersDisplayEdit();
                            updateUserIdsInputEdit();

                            // Marcar los checkboxes de los usuarios preseleccionados y añadirlos al array `selectedUsersEdit`
                            userListEdit.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                                const userId = parseInt(checkbox.value);
                                if (customer.users.some(user => user.id === userId)) {
                                    checkbox.checked = true;
                                    // Asegurarnos de que estos usuarios preseleccionados estén en el array
                                    if (!selectedUsersEdit.some(user => user.id === userId)) {
                                        const userName = checkbox.nextElementSibling.textContent;
                                        selectedUsersEdit.push({ id: userId, name: userName });
                                    }
                                } else {
                                    checkbox.checked = false;
                                }
                            });

                            updateSelectedUsersDisplayEdit(); // Actualizar la visualización de los usuarios
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener los datos del customer:', error);
                    });

            });
        }


        // Asignar evento al botón de cerrar el formulario de edición
        if (closeEditButton && editCustomerFormContainer) {
            closeEditButton.addEventListener('click', () => {
                closeEditCustomerForm(editCustomerFormContainer);
            });
        }
    });

    // Iniciar el observador de cambios en el contenido del modal
    observer.observe(modalContent, { childList: true, subtree: true });

    // Funciones para manejar la selección de usuarios en el formulario de edición

    userSelectEdit.addEventListener('click', toggleUserListEdit);
    userSelectEdit.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleUserListEdit();
        } else if (e.key === 'Escape') {
            userListEdit.style.display = 'none';
        }
    });

    function toggleUserListEdit() {
        if (userListEdit.style.display === 'block') {
            userListEdit.style.display = 'none';
        } else {
            userListEdit.style.display = 'block';
            currentFocusEdit = -1;
            focusNextCheckboxEdit(1);
        }
    }

    // Manejar el cambio en los checkboxes de la lista de usuarios
    userListEdit.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const userId = parseInt(this.value);
            const userName = this.nextElementSibling.textContent;

            if (this.checked) {
                // Agregar usuario a la lista seleccionada si no está ya
                if (!selectedUsersEdit.some(user => user.id === userId)) {
                    selectedUsersEdit.push({ id: userId, name: userName });
                }
            } else {
                // Eliminar usuario de la lista seleccionada
                selectedUsersEdit = selectedUsersEdit.filter(user => user.id !== userId);
            }

            // Actualizar la visualización y los IDs de los usuarios seleccionados
            updateSelectedUsersDisplayEdit();
        });
    });

    // Función para actualizar la visualización de los usuarios seleccionados
    function updateSelectedUsersDisplayEdit() {
        selectedUsersContainerEdit.innerHTML = ''; // Limpiar la visualización actual
        selectedUsersEdit.forEach(user => {
            const span = document.createElement('span');
            span.textContent = user.name;
            span.classList.add('selected-user'); // Puedes agregar estilos CSS si es necesario
            selectedUsersContainerEdit.appendChild(span);
        });
    }

    // Función para actualizar el campo oculto con los IDs de los usuarios seleccionados
    function updateUserIdsInputEdit() {
        userIdsInputEdit.value = selectedUsersEdit.map(user => user.id).join(','); // Convertir a una lista de IDs separados por coma
    }

    // Cerrar la lista de usuarios si se hace clic fuera
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.custom-select') && e.target !== userSelectEdit) {
            userListEdit.style.display = 'none';
        }
    });

    // Navegar con el teclado en la lista de usuarios
    userListEdit.addEventListener('keydown', function (e) {
        const checkboxes = userListEdit.querySelectorAll('input[type="checkbox"]');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            focusNextCheckboxEdit(1);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            focusNextCheckboxEdit(-1);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentFocusEdit >= 0 && currentFocusEdit < checkboxes.length) {
                checkboxes[currentFocusEdit].click(); // Simular un click para seleccionar el usuario
            }
        } else if (e.key === 'Escape') {
            userListEdit.style.display = 'none';
            userSelectEdit.focus();
        }
    });

    // Función para manejar el enfoque de los checkboxes con el teclado
    function focusNextCheckboxEdit(direction) {
        const checkboxes = userListEdit.querySelectorAll('input[type="checkbox"]');
        currentFocusEdit = (currentFocusEdit + direction + checkboxes.length) % checkboxes.length;
        checkboxes[currentFocusEdit].focus();
    }


    // Manejar el envío del formulario de edición
    editCustomerForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevenir el envío normal del formulario

        const customerId = document.getElementById('customer_id').value;

        // Crear un objeto FormData en lugar de un objeto JSON
        const formData = new FormData(editCustomerForm);

        // Agregar manualmente el campo _method para simular una solicitud PUT
        formData.append('_method', 'PUT');

        // Obtener los usuarios seleccionados y añadir cada uno al FormData
        const selectedUsersEdit = Array.from(document.querySelectorAll('#user-list-edit input[type="checkbox"]:checked'))
            .map(checkbox => checkbox.value);

        // Asegurarnos de que se añadan correctamente al FormData como array
        selectedUsersEdit.forEach(userId => {
            formData.append('usersEdit[]', userId);
        });

        fetch(`/clientes/${customerId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
            .then(response => {
                // Verificar si la respuesta es correcta o contiene un error
                if (!response.ok) {
                    return response.json().then(err => {
                        console.error("Error en la respuesta:", err); // Mostrar el error detallado en consola
                        throw new Error('Error en la respuesta del servidor');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Si el customer se edita con éxito, cerrar el modal y mostrar un mensaje
                    showNotification('Cliente actualizado correctamente', 'info');
                    closeEditCustomerForm(editCustomerFormContainer); // Cerrar el formulario de edición

                    // Actualizar solo la fila de la tarea editada
                    updateSingleCustomerRow(data.customer);
                } else {
                    // Mostrar los errores de validación si los hay
                    showNotification('Error al actualizar el customer', 'error');
                    console.error('Errores de validación:', data.errors);
                }
            })
            .catch(error => {
                console.error('Error al actualizar el Cliente:', error);
                showNotification('Error al actualizar el cliente', 'error');
            });
    });

    Echo.channel('customers')
        .listen('.CustomerUpdated', (data) => {
            console.log('Cliente actualizado (capturada por listen):', data);
            updateCustomerRow(data.customer);
        })
        .listenForWhisper('CustomerUpdated', (data) => {
            console.log('Cliente actualizado (capturada por whisper):', data);
        })
        .listen('CustomerDeleted', (data) => {
            console.log('Cliente eliminado (capturada por listen):', data);
            deleteCustomerRow(data.customerId);
        })
        .subscribed(() => {
            console.log('Conectado al canal de clientes');
        });



    function updateCustomerRow(customer) {
        const rowToUpdate = document.querySelector(`tr[data-customer-id="${customer.id}"]`);
        if (rowToUpdate) {
            const tipo_cliente = customer.tipo_cliente ? customer.tipo_cliente.nombre : rowToUpdate.querySelector('td:nth-child(11)').textContent;
            const tributacion = customer.tributacion ? customer.tributacion.nombre : rowToUpdate.querySelector('td:nth-child(12)').textContent;
            const clasificacion = customer.clasificacion ? customer.clasificacion.nombre : rowToUpdate.querySelector('td:nth-child(13)').textContent;
            const situacion = customer.situacion ? customer.situacion.nombre : rowToUpdate.querySelector('td:nth-child(14)').textContent;

            rowToUpdate.innerHTML = `
                <td>${customer.id}</td>
                <td>${customer.nombre_fiscal || 'Sin nombre fiscal'}</td>
                <td>${customer.nif || 'Sin NIF'}</td>
                <td>${customer.movil || 'Sin móvil'}</td>
                <td>${customer.segundo_telefono || 'Sin 2º teléfono'}</td>
                <td>${customer.fijo || 'Sin fijo'}</td>
                <td>${customer.persona_contacto || 'Sin Persona Contacto'}</td>
                <td>${customer.email || 'Sin email'}</td>
                <td>${customer.direccion || 'Sin dirección'}</td>
                <td>${customer.codigo_postal || 'Sin código postal'}</td>
                <td>${customer.poblacion || 'Sin población'}</td>
                <td>${customer.users && customer.users.length > 0 ? customer.users.map(user => user.name).join(', ') : 'Sin responsable'}</td>
                <td>${tipo_cliente}</td>
                <td>${tributacion}</td>
                <td>${clasificacion}</td>
                <td>${situacion}</td>
                <td>${customer.datos_bancarios || 'Sin datos bancarios'}</td>
                <td>${customer.subclase || 'Sin subclase'}</td>
                <td>${customer.puntaje || 'N/A'}</td>
                <td>${customer.codigo_sage || 'N/A'}</td>
                <td style="display: none;">${customer.created_at || 'Sin fecha'}</td>
            `;
        } else {
            console.warn('No se encontró la fila correspondiente a la tarea actualizada');
        }
    }


    function deleteCustomerRow(customerId) {
        const rowToDelete = document.querySelector(`tr[data-customer-id="${customerId}"]`);
        if (rowToDelete) {
            rowToDelete.remove();
        } else {
            console.warn('No se encontró la fila correspondiente al cliente eliminado');
        }
    }


});

// Función para borrar la customer
function deleteCustomer(customerId) {
    console.log("Intentando borrar la customer con ID:", customerId);

    fetch(`/clientes/${customerId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Cliente eliminado correctamente.");
                showNotification("Cliente eliminado correctamente", "error");
                closeCustomerModal();

                const rowToDelete = document.querySelector(`tr[data-customer-id="${customerId}"]`);
                if (rowToDelete) {
                    rowToDelete.remove();
                }
            } else {
                console.error('Error al eliminar la customer:', data.message);
                alert('Error al eliminar el cliente: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error al borrar el cliente:', error);
            alert('Error al borrar el cliente.');
        });
}

// Función para cerrar el formulario de edición
function closeEditCustomerForm(editCustomerFormContainer) {
    if (editCustomerFormContainer) {
        editCustomerFormContainer.classList.remove('show');
        editCustomerFormContainer.classList.add('hide');
        setTimeout(() => {
            editCustomerFormContainer.style.display = 'none';
        }, 400);
    }
}

// Función para cerrar el modal de detalles de la tarea
function closeCustomerModal() {
    const modal = document.getElementById('customer-detail-modal');
    const editCustomerFormContainer = document.getElementById('edit-customer-form-container');

    modal.style.display = 'none';

    // También cerrar el formulario de edición si está abierto
    if (editCustomerFormContainer.classList.contains('show')) {
        closeEditCustomerForm(editCustomerFormContainer);
    }
}



