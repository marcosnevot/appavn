<meta name="user-id" content="{{ auth()->user()->id }}">

<!-- Formulario de nueva tarea en un menú desplegable -->
<div id="task-form" class="task-form hide">
    <h3 class="form-title">Nueva Tarea</h3>
    <!-- Formulario para añadir tareas -->
    <form method="POST" id="add-task-form" enctype="multipart/form-data">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <div class="planificacion-container">
                    <div id="planificacion-buttons" class="planificacion-buttons-container">
                        <!-- Botones de planificación dinámicos aparecerán aquí -->
                    </div>
                    <!-- Campo oculto para almacenar la fecha seleccionada -->
                    <input type="date" id="planificacion" name="planificacion" class="planificacion-date-picker">

                </div>
            </div>

        </div>

        <!-- Fila 1: Cliente, Asunto, Tipo, Subtipo, Estado -->
        <div class="form-row">
            <div class="form-group wide">
                <label for="cliente_id">Cliente:
                    <span id="cliente-info" style="display: none; font-weight: normal; font-size: 14px;">
                        <!-- Aquí se mostrará el email y teléfono -->
                    </span>
                </label>
                <div class="autocomplete">
                    <input type="text" id="cliente-input" class="autocomplete-input" placeholder="Buscar o crear cliente..." autocomplete="off" required>
                    <input type="hidden" name="cliente_id" id="cliente-id-input"> <!-- Campo oculto para el id del cliente -->
                    <ul id="cliente-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group wide">
                <label for="asunto_id">Asunto:</label>
                <div class="autocomplete">
                    <input type="text" id="asunto-input" class="autocomplete-input" placeholder="Buscar o crear asunto..." autocomplete="off">
                    <input type="hidden" name="asunto_id" id="asunto-id-input"> <!-- Campo oculto para el id del asunto -->
                    <ul id="asunto-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group medium">
                <label for="tipo_id">Tipo de Tarea:</label>
                <div class="autocomplete">
                    <input type="text" id="tipo-input" class="autocomplete-input" placeholder="Buscar o crear tipo..." autocomplete="off">
                    <input type="hidden" name="tipo_id" id="tipo-id-input"> <!-- Campo oculto para el id del tipo -->
                    <ul id="tipo-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group narrow">
                <label for="facturable">Facturable:</label>
                <input type="checkbox" name="facturable" id="facturable" value="1" checked>
            </div>

            <div class="form-group grow">
                <label for="facturado">Facturado:</label>
                <select name="facturado" id="facturado">
                    <option value="NO">NO</option>
                    <option value="SI">SI</option>
                    <option value="NUNCA">NUNCA</option>
                </select>
            </div>



        </div>

        <!-- Fila 2: Asignado a, Archivo, Facturable, Facturado  -->
        <div class="form-row" style="display: none;">


            <div class="form-group grow">
                <label for="subtipo">Subtipo:</label>
                <select name="subtipo" id="subtipo">
                    <option value="ORDINARIA">Ordinaria</option>
                    <option value="EXTRAORDINARIA">Extraordinaria</option>
                </select>
            </div>





            <div class="form-group narrow" style="display:none">
                <label for="archivo">Archivo:</label>
                <input type="text" name="archivo" id="archivo">
            </div>

            <div class="form-group time">
                <label for="tiempo_previsto">Horas previstas:</label>
                <input type="number" step="0.25" name="tiempo_previsto" id="tiempo_previsto">
            </div>

            <div class="form-group time">
                <label for="tiempo_real">Horas reales:</label>
                <input type="number" step="0.25" name="tiempo_real" id="tiempo_real">
            </div>


        </div>

        <!-- Fila 3: Descripción, Observaciones Precio, Suplido, Coste -->
        <div class="form-row">

            <div class="form-group wide">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" rows="2" s></textarea>
            </div>

            <div class="form-group wide">
                <label for="observaciones">Observaciones:</label>
                <textarea name="observaciones" id="observaciones" rows="2"></textarea>
            </div>

            <div class="form-group" style="display:none">
                <label for="precio">Precio (€):</label>
                <input type="number" step="0.01" name="precio" id="precio">
            </div>

            <div class="form-group" style="display:none">
                <label for="suplido">Suplido (€):</label>
                <input type="number" step="0.01" name="suplido" id="suplido">
            </div>

            <div class="form-group" style="display:none">
                <label for="coste">Coste (€):</label>
                <input type="number" step="0.01" name="coste" id="coste">
            </div>
        </div>

        <!-- Fila 4: Fecha Inicio, Vencimiento, Imputación, Tiempo Previsto, Tiempo Real -->
        <div class="form-row" style="margin-bottom:30px">

            <div class="form-group grow">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ now()->format('Y-m-d') }}">
            </div>

            <div class="form-group grow">
                <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
                <input type="date" name="fecha_vencimiento" id="fecha_vencimiento">
            </div>


            <div class="form-group grow">
                <label for="estado">Estado:</label>
                <select name="estado" id="estado">
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="PLANIFICADA">Planificada</option>
                    <option value="ENESPERA">En Espera</option>
                    <option value="COMPLETADA">Completada</option>
                    <option value="RECURRENTE/TRIMESTRE">RECURRENTE/TRIMESTRE</option>
                </select>
            </div>

            <div class="form-group">
                <label for="user-select">Asignado a:</label>
                <div class="custom-select" name="user-select" tabindex="0" id="user-select">
                    <div id="selected-users" class="selected-users">
                        <!-- Aquí se añadirán los usuarios seleccionados -->
                    </div>
                    <div id="user-list" class="dropdown-list" style="display: none;">
                        <ul>
                            @foreach($usuarios as $user)
                            <li>
                                <input class="user-checkbox" type="checkbox" id="user-{{ $user->id }}" value="{{ $user->id }}">
                                <label for="user-{{ $user->id }}">{{ $user->name }}</label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="users" id="user-ids"> <!-- Campo oculto para los IDs de usuarios seleccionados -->
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
            <label for="cliente-nif">NIF:</label>
            <input type="text" id="cliente-nif" placeholder="NIF del cliente">

            <label for="cliente-email">Email:</label>
            <input type="email" id="cliente-email" placeholder="Email del cliente">

            <label for="cliente-telefono">Teléfono:</label>
            <input type="text" id="cliente-telefono" placeholder="Teléfono del cliente">
        </div>
        <div class="modal-actions">
            <button id="confirm-modal-yes" class="btn btn-confirm">Sí, crear</button>
            <button id="confirm-modal-no" class="btn btn-cancel">No, cancelar</button>
        </div>
    </div>
</div>