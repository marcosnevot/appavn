<!-- Formulario de edición de tarea -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="edit-task-form-container" class="task-form hide">
    <h3 class="form-title">Editar Tarea</h3>

    <!-- Formulario para editar la tarea -->
    <form method="POST" id="edit-task-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="task_id" id="task_id" value="">
        <input type="hidden" name="_method" value="PUT"> <!-- Asegúrate de incluir este campo -->

        <!-- Fila 1: Subtipo, Estado -->
        <div class="form-row">
            <div class="form-group medium">
                <label for="subtipo">Subtipo:</label>
                <select name="subtipoEdit" id="subtipo">
                    <option value="ORDINARIA">Ordinaria</option>
                    <option value="EXTRAORDINARIA">Extraordinaria</option>
                </select>
            </div>

            <div class="form-group narrow">
                <label for="estado">Estado:</label>
                <select name="estadoEdit" id="estado">
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="ENPROGRESO">En Progreso</option>
                    <option value="COMPLETADA">Completada</option>
                </select>
            </div>
        </div>

        <!-- Fila 2: Asignado a, Archivo, Descripción, Observaciones -->
        <div class="form-row">
            <div class="form-group narrow">
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


            <div class="form-group narrow">
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
        <div class="form-row">
            <div class="form-group">
                <label for="facturable">Facturable:</label>
                <input type="checkbox" name="facturableEdit" id="facturable" value="1">
            </div>

            <div class="form-group">
                <label for="facturado">Facturado:</label>
                <input type="text" name="facturadoEdit" id="facturado">
            </div>

            <div class="form-group">
                <label for="precio">Precio (€):</label>
                <input type="number" step="0.01" name="precioEdit" id="precio">
            </div>

            <div class="form-group">
                <label for="suplido">Suplido (€):</label>
                <input type="number" step="0.01" name="suplidoEdit" id="suplido">
            </div>

            <div class="form-group">
                <label for="coste">Coste (€):</label>
                <input type="number" step="0.01" name="costeEdit" id="coste">
            </div>
        </div>

        <!-- Fila 4: Fecha Inicio, Vencimiento, Imputación, Tiempo Previsto, Tiempo Real -->
        <div class="form-row">
            <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" name="fecha_inicioEdit" id="fecha_inicio">
            </div>

            <div class="form-group">
                <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
                <input type="date" name="fecha_vencimientoEdit" id="fecha_vencimiento">
            </div>

            <div class="form-group">
                <label for="fecha_imputacionEdit">Fecha de Imputación:</label>
                <input type="date" name="fecha_imputacionEdit" id="fecha_imputacion">
            </div>

            <div class="form-group">
                <label for="tiempo_previsto">Tiempo Previsto (Horas):</label>
                <input type="number" step="0.25" name="tiempo_previstoEdit" id="tiempo_previsto">
            </div>

            <div class="form-group">
                <label for="tiempo_real">Tiempo Real (Horas):</label>
                <input type="number" step="0.25" name="tiempo_realEdit" id="tiempo_real">
            </div>
        </div>
        <input type="hidden" name="cliente_idEdit" id="cliente_id">


        <!-- Botones del formulario -->
        <div class="form-buttons">
            <button type="submit" class="btn-submit" id="btn-edit-task-form">Guardar Cambios</button>
            <button type="button" id="close-edit-task-form" class="btn-close">Cerrar</button>
        </div>
    </form>
</div>