<div class="users-container">
    <!-- Cabecera con botón de acciones -->
    <div class="users-header">
        <p class="section-description">Crea, edita o elimina usuarios de la base de datos.</p>
        <button class="btn-admin-action primary" id="add-user-button">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 32 32" fill="currentColor">
                <path d="M16 2C12.686 2 10 4.686 10 8s2.686 6 6 6 6-2.686 6-6-2.686-6-6-6zm0 10c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4zM16 16c-4.418 0-8 3.582-8 8 0 1.104.896 2 2 2h12c1.104 0 2-.896 2-2 0-4.418-3.582-8-8-8zm-6 8c0-3.308 2.692-6 6-6s6 2.692 6 6H10zM26 12h-2v2h-2v2h2v2h2v-2h2v-2h-2v-2z"></path>
            </svg>
            Crear Usuario
        </button>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="users-list">
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="users-table-body">
                <!-- Filas dinámicas renderizadas por JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Crear/Editar Usuarios -->
<div class="modal-user hidden" id="user-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">Añadir Usuario</h3>
            <button class="close-modal" id="close-modal">
                <i class="icon icon-close"></i>
            </button>
        </div>
        <form id="user-form">
            <div class="form-group">
                <label for="user-name">Nombre</label>
                <input type="text" id="user-name" name="name" placeholder="Nombre del usuario" required>
            </div>
            <div class="form-group">
                <label for="user-email">Email</label>
                <input type="email" id="user-email" name="email" placeholder="Correo electrónico" required>
            </div>
            <div class="form-group">
                <label for="user-role">Rol</label>
                <select id="user-role" name="role" required>
                    <option value="admin">Administrador</option>
                    <option value="employee">Empleado</option>
                </select>
            </div>
            <div class="form-group">
                <label for="user-password">Contraseña</label>
                <input type="password" id="user-password" name="password" placeholder="Contraseña" minlength="5">
            </div>

            <div class="form-group">
                <label for="outlook-password">App Password de Outlook</label>
                <input type="password" id="outlook-password" name="outlook_password" placeholder="App Password de Outlook">
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn-admin-action primary">Guardar</button>
                <button type="button" class="btn-admin-action secondary cancel-button">Cancelar</button>
            </div>
        </form>
    </div>
</div>