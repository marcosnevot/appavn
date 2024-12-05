<!-- Contenedor de la tabla con scroll para clientes -->
<div class="table-container" style="max-height: 80vh; width: 100%; overflow-x: auto; overflow-y: auto;">
    <!-- Tabla de clientes -->
    <table class="min-w-full table-auto bg-white dark:bg-gray-800">
        <thead>
            <tr>
                <th data-sort-key="id" style="cursor: pointer;">ID</th>
                <th data-sort-key="nombre_fiscal" style="cursor: pointer;" class="col-cliente">Nombre Fiscal</th>
                <th data-sort-key="nif" style="cursor: pointer;">NIF</th>
                <th data-sort-key="movil" style="cursor: pointer;">Móvil</th>
                <th data-sort-key="segundo_telefono" style="cursor: pointer;">2º Teléfono</th>
                <th data-sort-key="fijo" style="cursor: pointer;">Fijo</th>
                <th data-sort-key="persona_contacto" style="cursor: pointer;">Pers. Contacto</th>
                <th data-sort-key="email" style="cursor: pointer;">Email</th>
                <th data-sort-key="direccion" style="cursor: pointer;">Dirección</th>
                <th data-sort-key="codigo_postal" style="cursor: pointer;">Código Postal</th>
                <th data-sort-key="poblacion" style="cursor: pointer;">Población</th>
                <th data-sort-key="responsable" style="cursor: pointer;">Responsable</th>
                <th data-sort-key="tipo_cliente" style="cursor: pointer;">Tipo de Cliente</th>
                <th data-sort-key="tributacion" style="cursor: pointer;">Tributación</th>
                <th data-sort-key="clasificacion" style="cursor: pointer;">Clasificación</th>
                <th data-sort-key="situacion" style="cursor: pointer;">Situación</th>
                <th data-sort-key="datos_bancarios" style="cursor: pointer;">Datos Bancarios</th>
                <th data-sort-key="subclase" style="cursor: pointer;">Subclase</th>
                <th data-sort-key="puntaje" style="cursor: pointer;">Puntaje</th>
                <th data-sort-key="codigo_sage" style="cursor: pointer;">Código Sage</th>
                <th data-sort-key="created_at" style="cursor: pointer; display: none;">Fecha de Creación</th>
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