<!-- Formulario de edición de tarea -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="edit-task-form-container" class="task-form hide" style="z-index: 3001;">
    <h3 class="form-title">Editar Tarea</h3>

    <!-- Formulario para editar la tarea -->
    <form method="POST" id="edit-task-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="task_id" id="task_id" value="">
        <input type="hidden" name="_method" value="PUT"> <!-- Asegúrate de incluir este campo -->

        <!-- Fila 1: Cliente, Asunto, Tipo, Subtipo, Estado -->
        <div class="form-row">
            <div class="form-group wide">
                <label for="cliente_idEdit">Cliente:
                    <span id="cliente-info" style="display: none; font-weight: normal; font-size: 14px;">
                        <!-- Aquí se mostrará el email y teléfono -->
                    </span>
                </label>
                <div class="autocomplete">
                    <input type="text" id="cliente-inputEdit" class="autocomplete-input" placeholder="Buscar o crear cliente..." autocomplete="off" required>
                    <input type="hidden" name="cliente_idEdit" id="cliente-id-inputEdit"> <!-- Campo oculto para el id del cliente -->
                    <ul id="cliente-listEdit" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group wide">
                <label for="asunto_idEdit">Asunto:</label>
                <div class="autocomplete">
                    <input type="text" id="asunto-inputEdit" class="autocomplete-input" placeholder="Buscar o crear asunto..." autocomplete="off" required>
                    <input type="hidden" name="asunto_idEdit" id="asunto-id-inputEdit"> <!-- Campo oculto para el id del asunto -->
                    <ul id="asunto-listEdit" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group medium">
                <label for="tipo_idEdit">Tipo de Tarea:</label>
                <div class="autocomplete">
                    <input type="text" id="tipo-inputEdit" class="autocomplete-input" placeholder="Buscar o crear tipo..." autocomplete="off" required>
                    <input type="hidden" name="tipo_idEdit" id="tipo-id-inputEdit"> <!-- Campo oculto para el id del tipo -->
                    <ul id="tipo-listEdit" class="autocomplete-list"></ul>
                </div>
            </div>


        </div>

        <!-- Fila 1: Subtipo, Estado -->
        <div class="form-row ">
            <div class="form-group narrow">
                <label for="facturable">Facturable:</label>
                <input type="checkbox" name="facturableEdit" id="facturable" value="1">
            </div>

            <div class="form-group grow">
                <label for="facturado">Facturado:</label>
                <select name="facturadoEdit" id="facturado">
                    <option value="NO">NO</option>
                    <option value="SI">SI</option>
                    <option value="NUNCA">NUNCA</option>
                </select>
            </div>

            <div class="form-group grow">
                <label for="subtipo">Subtipo:</label>
                <select name="subtipoEdit" id="subtipo">
                    <option value="ORDINARIA">Ordinaria</option>
                    <option value="EXTRAORDINARIA">Extraordinaria</option>
                </select>
            </div>

            <div class="form-group grow">
                <label for="estado">Estado:</label>
                <select name="estadoEdit" id="estado">
                    <option value="PLANIFICADA">Planificada</option>
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="ENESPERA">En Espera</option>
                    <option value="COMPLETADA">Completada</option>
                    <option value="RECURRENTE/TRIMESTRE">RECURRENTE/TRIMESTRE</option>
                </select>
            </div>

            <div class="form-group narrow" id="duplicar-container">
                <label for="duplicar">Duplicar:</label>
                <input type="checkbox" name="duplicar" id="duplicar" value="1">
            </div>

            <div class="form-group grow">
                <label for="user-select-edit">Asignado a:</label>
                <div class="custom-select" name="user-select-edit" tabindex="0" id="user-select-edit">
                    <div id="selected-users-edit" class="selected-users">
                        <!-- Aquí se añadirán los usuarios seleccionados para la edición -->
                    </div>
                    <div id="user-list-edit" class="dropdown-list" style="display: none;">
                        <ul>
                            @foreach($usuarios as $user)
                            <li>
                                <input class="user-checkbox" type="checkbox" id="user-edit-{{ $user->id }}" name="usersEdit[]" value="{{ $user->id }}">
                                <label for="user-edit-{{ $user->id }}">{{ $user->name }}</label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="usersEdit" id="user-ids-edit">
            </div>


        </div>

        <!-- Fila 2: Asignado a, Archivo, Descripción, Observaciones -->
        <div class="form-row">



            <div class="form-group narrow" style="display: none;">
                <label for="archivo">Archivo:</label>
                <input type="text" name="archivoEdit" id="archivo">
            </div>

            <div class="form-group wide">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcionEdit" id="descripcion" rows="2"></textarea>
            </div>

            <div class="form-group wide">
                <label for="observaciones">Observaciones:</label>
                <textarea name="observacionesEdit" id="observaciones" rows="2"></textarea>
            </div>
        </div>

        <!-- Fila 3: Facturable, Facturado, Precio, Suplido, Coste -->
        <div class="form-row" style="display: none;">


            <div class="form-group" style="display: none;">
                <label for="precio">Precio (€):</label>
                <input type="number" step="0.01" name="precioEdit" id="precio">
            </div>

            <div class="form-group" style="display: none;">
                <label for="suplido">Suplido (€):</label>
                <input type="number" step="0.01" name="suplidoEdit" id="suplido">
            </div>

            <div class="form-group" style="display: none;">
                <label for="coste">Coste (€):</label>
                <input type="number" step="0.01" name="costeEdit" id="coste">
            </div>




        </div>

        <!-- Fila 4: Fecha Inicio, Vencimiento, Imputación, Tiempo Previsto, Tiempo Real -->
        <div class="form-row">
            <div class="form-group grow">
                <label for="fecha_planificacion">Planificación:</label>
                <input type="date" name="fecha_planificacionEdit" id="fecha_planificacion">
            </div>

            <div class="form-group grow">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" name="fecha_inicioEdit" id="fecha_inicio">
            </div>

            <div class="form-group grow">
                <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
                <input type="date" name="fecha_vencimientoEdit" id="fecha_vencimiento">
            </div>

            <div class="form-group grow">
                <label for="fecha_imputacionEdit">Fecha de Imputación:</label>
                <input type="date" name="fecha_imputacionEdit" id="fecha_imputacion">
            </div>

            <div class="form-group time">
                <label for="tiempo_previsto">Horas previstas:</label>
                <input type="number" step="0.25" name="tiempo_previstoEdit" id="tiempo_previsto">
            </div>

            <div class="form-group time">
                <label for="tiempo_real">Horas reales:</label>
                <input type="number" step="0.25" name="tiempo_realEdit" id="tiempo_real">
            </div>
        </div>

        <!-- Botones del formulario -->
        <div class="form-buttons">
            <button type="submit" class="btn-submit" id="btn-edit-task-form">Guardar Cambios</button>
            <button type="button" id="close-edit-task-form" class="btn-close">Cerrar</button>
        </div>
    </form>
</div>


<!-- Modal de confirmación para crear un asunto nuevo -->
<div id="confirm-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <!-- Mensajes de confirmación para asunto y tipo -->
        <p id="modal-message" class="modal-message">
            <span id="modal-cliente-message"></span><br>
            <span id="modal-asunto-message"></span><br>
            <span id="modal-tipo-message"></span>
        </p>
        <!-- Campos adicionales para el nuevo cliente -->
        <div id="new-client-details" style="display: none;">
            <label for="cliente-nifEdit">NIF:</label>
            <input type="text" id="cliente-nifEdit" placeholder="NIF del cliente">

            <label for="cliente-emailEdit">Email:</label>
            <input type="email" id="cliente-emailEdit" placeholder="Email del cliente">

            <label for="cliente-telefonoEdit">Teléfono:</label>
            <input type="text" id="cliente-telefonoEdit" placeholder="Teléfono del cliente">
        </div>
        <div class="modal-actions">
            <button id="confirm-modal-yes" class="btn btn-confirm">Sí, crear</button>
            <button id="confirm-modal-no" class="btn btn-cancel">No, cancelar</button>
        </div>
    </div>
</div>