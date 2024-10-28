<div class="customer-detail-modal-header">
    <!-- Título central con el Nombre Fiscal y NIF -->
    <h2 class="customer-detail-title">
        <span class="customer-name">{{ $customer->nombre_fiscal ?? 'Sin Nombre Fiscal' }}</span>
        <span class="customer-nif">{{ $customer->nif ?? 'Sin NIF' }}</span>
    </h2>
  
</div>

<div class="customer-detail-actions">
    <!-- Botones centrados para editar y eliminar el cliente -->
    <button id="edit-customer-button" class="btn-customer-action" data-customer-id="{{ $customer->id }}">Editar</button>
    <button id="delete-customer-button" class="btn-customer-action" data-customer-id="{{ $customer->id }}">Borrar</button>
</div>

<!-- Sección reservada para la futura gestión de subclientes o detalles adicionales -->
<div class="customer-subdetails-section">
    <h3>Detalles Adicionales</h3>
    <p>Aquí se gestionarán detalles adicionales de este cliente. (Próximamente)</p>
</div>
