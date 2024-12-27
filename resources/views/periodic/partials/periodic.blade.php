<div class="periodic-container">
    <!-- Cabecera -->
    <div class="periodic-header">
        <p class="section-description">Consulta y edita las tareas periódicas activas.</p>
    </div>

    <!-- Filtros y Tabla -->
    <div class="tasks-container">
        <!-- Filtros -->
        <form id="filters-form">
            <div class="filters-grid">
                <input type="text" id="filter-id" name="id" placeholder="ID">
                <input type="text" id="filter-cliente" name="cliente" placeholder="Cliente">
                <input type="text" id="filter-asunto" name="asunto" placeholder="Asunto">
                <input type="text" id="filter-descripcion" name="descripcion" placeholder="Descripción">
                <select id="filter-asignacion" name="asignacion">
                    <option value="">Todos</option>
                </select>
                <select id="filter-periodicidad" name="periodicidad">
                    <option value="">Todos</option>
                    <option value="SEMANAL">SEMANAL</option>
                    <option value="MENSUAL">MENSUAL</option>
                    <option value="TRIMESTRAL">TRIMESTRAL</option>
                    <option value="ANUAL">ANUAL</option>
                </select>
                <input type="date" id="filter-fecha" name="fecha_inicio_generacion">
                <div class="filter-placeholder"></div>
            </div>
        </form>

        <!-- Tabla -->
        <div class="tasks-list">
            <table class="tasks-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Asunto</th>
                        <th>Descripción</th>
                        <th>Asignación</th>
                        <th>Periodicidad</th>
                        <th>Inicio Generación</th>
                        <th>Próxima Generación</th>
                    </tr>
                </thead>
                <tbody id="tasks-table-body">
                    <!-- Filas dinámicas renderizadas por JS -->
                </tbody>
            </table>
        </div>
    </div>



    <!-- Modal para Editar Tarea -->
    <div class="modal-user hidden" id="edit-task-modal">
    <div class="modal-content">
            <div class="modal-header">
                <h3>Editar Tarea Periódica</h3>
                <button class="close-modal" id="close-edit-modal">&times;</button>
            </div>
            <form id="edit-task-form">
                <div class="form-group">
                    <label for="periodicidad">Periodicidad</label>
                    <select id="periodicidad" name="periodicidad">
                        <option value="SEMANAL">SEMANAL</option>
                        <option value="MENSUAL">MENSUAL</option>
                        <option value="TRIMESTRAL">TRIMESTRAL</option>
                        <option value="ANUAL">ANUAL</option>
                        <option value="NO">NO</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha_inicio_generacion">Fecha Inicio Generación</label>
                    <input type="date" id="fecha_inicio_generacion" name="fecha_inicio_generacion">
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn-admin-action primary">Guardar Cambios</button>
                    <button type="button" class="btn-admin-action secondary" id="cancel-edit-modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

</div>