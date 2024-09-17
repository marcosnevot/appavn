<!-- Formulario de nueva tarea en un menú desplegable -->
<div id="task-form" class="task-form hide">
    <h3 class="form-title">Nueva Tarea</h3>
    <!-- Formulario para añadir tareas -->
    <form method="POST" id="add-task-form" enctype="multipart/form-data">
        @csrf
        <!-- Fila 1: Cliente, Asunto, Tipo, Subtipo, Estado -->
        <div class="form-row">
            <div class="form-group wide">
                <label for="cliente_id">Cliente:</label>
                <div class="autocomplete">
                    <input type="text" id="cliente-input" class="autocomplete-input" placeholder="Buscar cliente..." autocomplete="off" required>
                    <input type="hidden" name="cliente_id" id="cliente-id-input"> <!-- Campo oculto para el id del cliente -->
                    <ul id="cliente-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group wide">
                <label for="asunto_id">Asunto:</label>
                <div class="autocomplete">
                    <input type="text" id="asunto-input" class="autocomplete-input" placeholder="Buscar o crear asunto..." autocomplete="off" required>
                    <input type="hidden" name="asunto_id" id="asunto-id-input"> <!-- Campo oculto para el id del asunto -->
                    <ul id="asunto-list" class="autocomplete-list"></ul>
                </div>
            </div>


            <div class="form-group medium">
                <label for="tipo_id">Tipo de Tarea:</label>
                <select name="tipo_id" id="tipo_id">
                    @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group medium">
                <label for="subtipo">Subtipo:</label>
                <select name="subtipo" id="subtipo">
                    <option value="ORDINARIA">Ordinaria</option>
                    <option value="EXTRAORDINARIA">Extraordinaria</option>
                </select>
            </div>

            <div class="form-group narrow">
                <label for="estado">Estado:</label>
                <select name="estado" id="estado">
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="ENPROGRESO">En Progreso</option>
                    <option value="COMPLETADA">Completada</option>
                </select>
            </div>
        </div>

        <!-- Fila 2: Asignado a, Archivo, Descripción, Observaciones -->
        <div class="form-row">
            <div class="form-group narrow">
                <label for="users">Asignado a:</label>
                <select name="users[]" id="users" multiple>
                    @foreach($usuarios as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group narrow">
                <label for="archivo">Archivo:</label>
                <input type="text" name="archivo" id="archivo">
            </div>

            <div class="form-group wide">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" rows="2"></textarea>
            </div>

            <div class="form-group wide">
                <label for="observaciones">Observaciones:</label>
                <textarea name="observaciones" id="observaciones" rows="2"></textarea>
            </div>
        </div>

        <!-- Fila 3: Facturable, Facturado, Precio, Suplido, Coste -->
        <div class="form-row">
            <div class="form-group">
                <label for="facturable">Facturable:</label>
                <input type="checkbox" name="facturable" id="facturable" value="1">
            </div>

            <div class="form-group">
                <label for="facturado">Facturado:</label>
                <input type="text" name="facturado" id="facturado">
            </div>

            <div class="form-group">
                <label for="precio">Precio (€):</label>
                <input type="number" step="0.01" name="precio" id="precio">
            </div>

            <div class="form-group">
                <label for="suplido">Suplido (€):</label>
                <input type="number" step="0.01" name="suplido" id="suplido">
            </div>

            <div class="form-group">
                <label for="coste">Coste (€):</label>
                <input type="number" step="0.01" name="coste" id="coste">
            </div>
        </div>

        <!-- Fila 4: Fecha Inicio, Vencimiento, Imputación, Tiempo Previsto, Tiempo Real -->
        <div class="form-row">
            <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ now()->format('Y-m-d') }}">
            </div>

            <div class="form-group">
                <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
                <input type="date" name="fecha_vencimiento" id="fecha_vencimiento">
            </div>

            <div class="form-group">
                <label for="fecha_imputacion">Fecha de Imputación:</label>
                <input type="date" name="fecha_imputacion" id="fecha_imputacion">
            </div>

            <div class="form-group">
                <label for="tiempo_previsto">Tiempo Previsto (Horas):</label>
                <input type="number" step="0.25" name="tiempo_previsto" id="tiempo_previsto">
            </div>

            <div class="form-group">
                <label for="tiempo_real">Tiempo Real (Horas):</label>
                <input type="number" step="0.25" name="tiempo_real" id="tiempo_real">
            </div>
        </div>

        <!-- Botones del formulario -->
        <div class="form-buttons">
            <button type="submit" class="btn-submit">Añadir Tarea</button>
            <button type="button" id="close-task-form" class="btn-close">Cerrar</button>
        </div>
    </form>
</div>

<!-- Modal de confirmación para crear un asunto nuevo -->
<div id="confirm-modal" class="modal">
    <div class="modal-content">
        <p id="modal-message" class="modal-message"></p>
        <div class="modal-actions">
            <button id="confirm-modal-yes" class="btn btn-confirm">Sí, crear</button>
            <button id="confirm-modal-no" class="btn btn-cancel">No, cancelar</button>
        </div>
    </div>
</div>

