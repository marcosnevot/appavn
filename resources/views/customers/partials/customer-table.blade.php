<!-- Contenedor de la tabla con scroll para clientes -->
<div class="table-container" style="max-height: 80vh; width: 100%; overflow-x: auto; overflow-y: auto;">
    <!-- Tabla de clientes -->
    <table class="min-w-full table-auto bg-white dark:bg-gray-800">
        <thead>
            <tr>
                <th data-sort-key="id" style="cursor: pointer;">ID</th>
                <th data-sort-key="nombre_fiscal" data-field="nombrefiscal" style="cursor: pointer;" class="col-cliente">Nombre Fiscal</th>
                <th data-sort-key="nif" data-field="nif" style="cursor: pointer;">NIF</th>
                <th data-sort-key="movil" data-field="filter_movil" style="cursor: pointer;">Móvil</th>
                <th data-sort-key="segundo_telefono" data-field="filter_segundo_telefono" style="cursor: pointer;">2º Teléfono</th>
                <th data-sort-key="fijo" data-field="filter_fijo" style="cursor: pointer;">Fijo</th>
                <th data-sort-key="persona_contacto" data-field="filter_persona_contacto" style="cursor: pointer;">Pers. Contacto</th>
                <th data-sort-key="email" data-field="filter_email" style="cursor: pointer;">Email</th>
                <th data-sort-key="direccion" data-field="filter_direccion" style="cursor: pointer;">Dirección</th>
                <th data-sort-key="codigo_postal" data-field="filter_codigo_postal" style="cursor: pointer;">Código Postal</th>
                <th data-sort-key="poblacion" data-field="filter_poblacion" style="cursor: pointer;">Población</th>
                <th data-sort-key="responsable" data-field="filter-responsable" style="cursor: pointer;">Responsable</th>
                <th data-sort-key="tipo_cliente" data-field="tipocliente" style="cursor: pointer;">Tipo de Cliente</th>
                <th data-sort-key="tributacion" data-field="tributacion" style="cursor: pointer;">Tributación</th>
                <th data-sort-key="clasificacion" data-field="clasificacion" style="cursor: pointer;">Clasificación</th>
                <th data-sort-key="situacion" data-field="situacion" style="cursor: pointer;">Situación</th>
                <th data-sort-key="datos_bancarios" data-field="filter_datos_bancarios" style="cursor: pointer;">Datos Bancarios</th>
                <th data-sort-key="subclase" data-field="filter_subclase" style="cursor: pointer;">Subclase</th>
                <th data-sort-key="puntaje" data-field="filter_puntaje" style="cursor: pointer;">Puntaje</th>
                <th data-sort-key="codigo_sage" data-field="filter_codigo_sage" style="cursor: pointer;">Código Sage</th>
                <th data-sort-key="created_at" data-field="filter_created_at" style="cursor: pointer; display: none;">Fecha de Creación</th>
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