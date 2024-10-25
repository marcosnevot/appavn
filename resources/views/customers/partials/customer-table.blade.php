<!-- Contenedor de la tabla con scroll para clientes -->
<div class="table-container" style="max-height: 80vh; width: 100%; overflow-x: auto; overflow-y: auto;">
    <!-- Tabla de clientes -->
    <table class="min-w-full table-auto bg-white dark:bg-gray-800">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Fiscal</th>
                <th>NIF</th>
                <th>Móvil</th>
                <th>Fijo</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>Código Postal</th>
                <th>Población</th>
                <th>Responsable</th>
                <th>Tipo de Cliente</th>
                <th>Tributación</th>
                <th>Clasificación</th>
                <th>Situación</th>
                <th>Datos Bancarios</th>
                <th>Subclase</th>
                <th>Puntaje</th>
                <th>Código Sage</th>
                <th style="display: none;">Fecha de Creación</th> <!-- Columna oculta para created_at -->
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se rellenarán los clientes dinámicamente mediante JS -->
        </tbody>
    </table>
</div>

<!-- Contenedor para la paginación de clientes -->
<div class="pagination-container" id="pagination-controls">
    <ul id="pagination" class="pagination">
        <!-- Los botones de paginación serán generados dinámicamente por JS -->
    </ul>
</div>

<!-- Modal para los detalles del cliente -->
<div id="customer-detail-modal" class="customer-detail-modal" style="display: none;">
    <div class="customer-detail-modal-content" id="customer-detail-modal-content">
        <!-- El contenido del modal de clientes será insertado aquí -->
    </div>
    <button id="close-customer-detail-modal" class="btn-close-customer-detail-modal">Cerrar</button>
</div>
