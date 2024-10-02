<!-- Contenedor de la tabla con scroll -->
<div class="table-container" style="max-height: 80vh; width: 100%; overflow-x: auto; overflow-y: auto;">
    <!-- Tabla de tareas -->
    <table class="min-w-full table-auto bg-white dark:bg-gray-800">
        <thead>
            <tr>
                <th>ID</th>
                <th>Asunto</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Subtipo</th>
                <th>Estado</th>
                <th>Asignado a</th>
                <th>Descripción</th>
                <th>Observaciones</th>
                <th>Archivo</th>
                <th>Facturable</th>
                <th>Facturado</th>
                <th>Precio</th>
                <th>Suplido</th>
                <th>Coste</th>
                <th>Fecha Inicio</th>
                <th>Fecha Vencimiento</th>
                <th>Fecha Imputación</th>
                <th>Tiempo Previsto</th>
                <th>Tiempo Real</th>
                <th style="display: none;">Fecha de Creación</th> <!-- Columna oculta para created_at -->
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se rellenarán las tareas dinámicamente mediante JS -->
        </tbody>
    </table>
</div>
<div class="pagination-container" id="pagination-controls">
    <ul id="pagination" class="pagination">
        <!-- Pagination buttons will be dynamically generated by JS -->
    </ul>
</div>

<!-- Modal para las tareas detalladas -->
<div id="task-detail-modal" class="task-detail-modal" style="display: none;">
    <div class="task-detail-modal-content" id="task-detail-modal-content">
        <!-- El contenido del modal será insertado aquí -->
    </div>
    <button id="close-task-detail-modal" class="btn-close-task-detail-modal">Cerrar</button>
</div>

