document.addEventListener('DOMContentLoaded', () => {
    const userModal = document.getElementById('user-modal');
    const userForm = document.getElementById('user-form');
    const addUserButton = document.getElementById('add-user-button');
    const cancelButton = document.querySelector('.cancel-button');
    const usersTableBody = document.getElementById('users-table-body');

    let editingUserId = null;

    // URL del endpoint de usuarios
    const API_USERS_ENDPOINT = '/api/users';

    // Función para abrir el modal
    function openModal(title, user = null) {
        document.getElementById('modal-title').textContent = title;
        userModal.classList.remove('hidden');
        const roleSelect = document.getElementById('user-role');

        if (user) {
            document.getElementById('user-name').value = user.name;
            document.getElementById('user-email').value = user.email;
            document.getElementById('user-role').value = user.role;
            document.getElementById('outlook-password').value = ''; // No prellenar por seguridad
            editingUserId = user.id;

            // Si el usuario tiene id 1, aplica readonly
            if (user.id === 1) {
                roleSelect.setAttribute('readonly', true);
                roleSelect.style.pointerEvents = 'none'; // Desactiva clics en el selector
                roleSelect.style.backgroundColor = '#f3f3f3'; // Opcional: Cambia el color de fondo
                roleSelect.style.color = '#6b7280'; // Cambia el color del texto para indicar deshabilitación
            } else {
                roleSelect.removeAttribute('readonly');
                roleSelect.style.pointerEvents = 'auto'; // Reactiva clics
                roleSelect.style.backgroundColor = ''; // Restablece el color de fondo
                roleSelect.style.color = ''; // Restablece el color del texto
            }
        } else {
            userForm.reset();
            editingUserId = null;
            roleSelect.removeAttribute('readonly'); // Asegúrate de habilitarlo para nuevos usuarios
            roleSelect.style.pointerEvents = 'auto';
            roleSelect.style.backgroundColor = '';
            roleSelect.style.color = '';
        }
    }

    // Función para cerrar el modal
    function closeModal() {
        userModal.classList.add('hidden');
        userForm.reset();
        editingUserId = null;
    }

    // Función para renderizar usuarios
    function renderUsers(users) {
        usersTableBody.innerHTML = '';
        users.forEach(user => {
            const displayRole = user.role === 'employee' ? 'Empleado' : user.role === 'admin' ? 'Administrador' : user.role;

            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${displayRole}</td>
                <td>
                    <button class="btn-refresh edit-user" data-id="${user.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 26 26">
                            <path d="M 20.09375 0.25 C 19.5 0.246094 18.917969 0.457031 18.46875 0.90625 L 17.46875 1.9375 L 24.0625 8.5625 L 25.0625 7.53125 C 25.964844 6.628906 25.972656 5.164063 25.0625 4.25 L 21.75 0.9375 C 21.292969 0.480469 20.6875 0.253906 20.09375 0.25 Z M 16.34375 2.84375 L 14.78125 4.34375 L 21.65625 11.21875 L 23.25 9.75 Z M 13.78125 5.4375 L 2.96875 16.15625 C 2.71875 16.285156 2.539063 16.511719 2.46875 16.78125 L 0.15625 24.625 C 0.0507813 24.96875 0.144531 25.347656 0.398438 25.601563 C 0.652344 25.855469 1.03125 25.949219 1.375 25.84375 L 9.21875 23.53125 C 9.582031 23.476563 9.882813 23.222656 10 22.875 L 20.65625 12.3125 L 19.1875 10.84375 L 8.25 21.8125 L 3.84375 23.09375 L 2.90625 22.15625 L 4.25 17.5625 L 15.09375 6.75 Z M 16.15625 7.84375 L 5.1875 18.84375 L 6.78125 19.1875 L 7 20.65625 L 18 9.6875 Z"></path>
                        </svg>
                    </button>
                    <button class="btn-refresh delete-user" data-id="${user.id}" ${user.role === 'admin' ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 30 30">
                            <path d="M 14.984375 2.4863281 A 1.0001 1.0001 0 0 0 14 3.5 L 14 4 L 8.5 4 A 1.0001 1.0001 0 0 0 7.4863281 5 L 6 5 A 1.0001 1.0001 0 1 0 6 7 L 24 7 A 1.0001 1.0001 0 1 0 24 5 L 22.513672 5 A 1.0001 1.0001 0 0 0 21.5 4 L 16 4 L 16 3.5 A 1.0001 1.0001 0 0 0 14.984375 2.4863281 z M 6 9 L 7.7929688 24.234375 C 7.9109687 25.241375 8.7633438 26 9.7773438 26 L 20.222656 26 C 21.236656 26 22.088031 25.241375 22.207031 24.234375 L 24 9 L 6 9 z"></path>
                        </svg>
                    </button>
                </td>
            `;

            // Añadir eventos a los botones
            row.querySelector('.edit-user').addEventListener('click', () => openModal('Editar Usuario', user));
            row.querySelector('.delete-user').addEventListener('click', () => deleteUser(user.id));

            usersTableBody.appendChild(row);
        });
    }

    // Función para cargar usuarios desde el backend
    async function loadUsers() {
        try {
            console.log('Cargando usuarios desde /api/users');
            const response = await fetch(API_USERS_ENDPOINT, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error(`Error al cargar usuarios: ${response.statusText}`);
            }

            const users = await response.json();
            renderUsers(users);
        } catch (error) {
            console.error('Error al cargar usuarios:', error.message);
        }
    }

    // Función para eliminar usuario
    async function deleteUser(userId) {
        const confirmDelete = confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.');
        if (!confirmDelete) {
            return; // Si el usuario cancela, no continúa
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Obtén el token CSRF

            const response = await fetch(`${API_USERS_ENDPOINT}/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, // Agrega el token CSRF

                },
            });

            if (!response.ok) {
                throw new Error(`Error al eliminar usuario: ${response.statusText}`);
            }

            showSuccessNotification('Usuario eliminado con éxito.');
            loadUsers(); // Recargar usuarios
        } catch (error) {
            showErrorNotification('Error al eliminar el usuario. Inténtalo de nuevo.');
            console.error('Error al eliminar usuario:', error.message);
        }
    }

    // Evento para abrir el modal de añadir usuario
    addUserButton.addEventListener('click', () => openModal('Añadir Usuario'));

    // Evento para cerrar el modal
    cancelButton.addEventListener('click', closeModal);

    // Evento para guardar el usuario
    userForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(userForm);
        const userData = Object.fromEntries(formData);

        // Si estamos añadiendo un nuevo usuario, asegurémonos de incluir la contraseña

        // Validación de contraseña
        if (!editingUserId && (!userData.password || userData.password.length < 5)) {
            alert('La contraseña es obligatoria y debe tener al menos 5 caracteres.');
            return;
        }

        // Confirmación solo al crear un usuario
        if (!editingUserId) {
            const confirmCreate = confirm('¿Estás seguro de que deseas crear este usuario?');
            if (!confirmCreate) {
                return; // Cancela el envío si no se confirma
            }
        }

        // Si el campo de contraseña está vacío al editar, no lo envía
        if (editingUserId && !userData.password) {
            delete userData.password; // No envía contraseña vacía al backend
        }

        // Si el campo de App Password está vacío, no lo envía
        if (editingUserId && !userData.outlook_password) {
            delete userData.outlook_password; // No envía app password vacío al backend
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Obtén el token CSRF
            console.log('Datos enviados:', userData);

            const response = await fetch(editingUserId ? `${API_USERS_ENDPOINT}/${editingUserId}` : API_USERS_ENDPOINT, {
                method: editingUserId ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, // Agrega el token CSRF
                },
                body: JSON.stringify(userData),
            });

            if (!response.ok) {
                throw new Error(`Error al guardar usuario: ${response.statusText}`);
            }

            showSuccessNotification(editingUserId ? 'Usuario actualizado con éxito.' : 'Usuario creado con éxito.');
            closeModal();
            loadUsers(); // Recargar usuarios
        } catch (error) {
            showErrorNotification('Error al guardar el usuario. Inténtalo de nuevo.');
            console.error('Error al guardar usuario:', error.message);
        }
    });

    // Cargar usuarios al inicio
    loadUsers();
});


function showSuccessNotification(message) {
    Toastify({
        text: message,
        duration: 3000, // Duración en milisegundos
        close: true, // Muestra un botón de cierre
        gravity: "top", // Posición: "top" o "bottom"
        position: "right", // Posición: "left", "center", "right"
        backgroundColor: "#4CAF50", // Color de fondo (verde para éxito)
    }).showToast();
}

function showErrorNotification(message) {
    Toastify({
        text: message,
        duration: 5000, // Más tiempo para errores
        close: true,
        gravity: "top",
        position: "right",
        backgroundColor: "#F44336", // Color de fondo (rojo para errores)
    }).showToast();
}
