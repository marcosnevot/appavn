<input type="hidden" id="user-session-id" value="{{ auth()->user()->id }}">

<!-- Formulario para filtrar tareas -->
<div id="filter-task-form" class="task-form hide">
    <h3 class="form-title">Filtrar Tareas</h3>
    <!-- Formulario para filtrar tareas -->
    <form method="POST" id="filter-task-form-content" enctype="multipart/form-data">
        @csrf
        <!-- Fila 1: Cliente, Asunto, Tipo, Subtipo, Estado -->
        <div class="form-row">
            <div class="form-group wide">
                <label for="filter-cliente-input">Cliente:</label>
                <div class="autocomplete">
                    <input type="text" id="filter-cliente-input" class="autocomplete-input" placeholder="Buscar cliente..." autocomplete="off">
                    <input type="hidden" name="filter_cliente_id" id="filter-cliente-id-input">
                    <ul id="filter-cliente-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group wide">
                <label for="filter-asunto-input">Asunto:</label>
                <div class="autocomplete">
                    <input type="text" id="filter-asunto-input" class="autocomplete-input" placeholder="Buscar asunto..." autocomplete="off">
                    <input type="hidden" name="filter_asunto_id" id="filter-asunto-id-input">
                    <ul id="filter-asunto-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group medium">
                <label for="filter-tipo-input">Tipo de Tarea:</label>
                <div class="autocomplete">
                    <input type="text" id="filter-tipo-input" class="autocomplete-input" placeholder="Buscar tipo..." autocomplete="off">
                    <input type="hidden" name="filter_tipo_id" id="filter-tipo-id-input">
                    <ul id="filter-tipo-list" class="autocomplete-list"></ul>
                </div>
            </div>


        </div>

        <!-- Fila 2: Asignado a, Archivo, Facturable, Facturado -->
        <div class="form-row" style="display: none;">
            


            <div class="form-group narrow" style="display: none;">
                <label for="filter-archivo">Archivo:</label>
                <input type="text" name="filter_archivo" id="filter-archivo">
            </div>

            <div class="form-group grow">
                <label for="filter-facturable">Facturable:</label>
                <select name="filter_facturable" id="filter-facturable">
                    <option value="">Cualquiera</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="form-group grow">
                <label for="filter-facturado">Facturado:</label>
                <select name="filter_facturado" id="filter-facturado">
                    <option value="">Cualquiera</option>
                    <option value="NO">No</option>
                    <option value="SI">Sí</option>
                    <option value="NUNCA">Nunca</option>
                </select>
            </div>


            <div class="form-group grow">
                <label for="filter-subtipo">Subtipo:</label>
                <select name="filter_subtipo" id="filter-subtipo">
                    <option value="">Cualquiera</option>
                    <option value="ORDINARIA">Ordinaria</option>
                    <option value="EXTRAORDINARIA">Extraordinaria</option>
                </select>
            </div>

            <div class="form-group grow">
                <label for="filter-estado">Estado:</label>
                <select name="filter_estado" id="filter-estado">
                    <option value="">Cualquiera</option>
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="ENPROGRESO">En Espera</option>
                    <option value="COMPLETADA">Completada</option>
                </select>
            </div>


            <div class="form-group time">
                <label for="filter-tiempo-previsto">Horas Previstas:</label>
                <input type="number" step="0.25" name="filter_tiempo_previsto" id="filter-tiempo-previsto">
            </div>

            <div class="form-group time">
                <label for="filter-tiempo-real">Horas Reales:</label>
                <input type="number" step="0.25" name="filter_tiempo_real" id="filter-tiempo-real">
            </div>
        </div>

        <!-- Fila 3: Precio, Suplido, Coste -->
        <div class="form-row">
            <div class="form-group" style="display: none;">
                <label for="filter-precio">Precio (€):</label>
                <input type="number" step="0.01" name="filter_precio" id="filter-precio">
            </div>

            <div class="form-group" style="display: none;">
                <label for="filter-suplido">Suplido (€):</label>
                <input type="number" step="0.01" name="filter_suplido" id="filter-suplido">
            </div>

            <div class="form-group" style="display: none;">
                <label for="filter-coste">Coste (€):</label>
                <input type="number" step="0.01" name="filter_coste" id="filter-coste">
            </div>
        </div>

        <!-- Fila 4: Fechas, Tiempo Previsto, Tiempo Real -->
        <div class="form-row" style="margin-bottom:30px">
            <div class="form-group grow">
                <label for="filter-fecha-inicio">Fecha de Inicio:</label>
                <input type="date" name="filter_fecha_inicio" id="filter-fecha-inicio">
            </div>

            <div class="form-group grow">
                <label for="filter-fecha-vencimiento">Fecha de Vencimiento:</label>
                <input type="date" name="filter_fecha_vencimiento" id="filter-fecha-vencimiento">
            </div>

            <div class="form-group grow">
                <label for="filter-fecha-imputacion">Fecha de Imputación:</label>
                <input type="date" name="filter_fecha_imputacion" id="filter-fecha-imputacion">
            </div>

            <div class="form-group grow">
                <label for="filter-user-select">Asignado a:</label>
                <div class="custom-select" name="filter-user-select" tabindex="0" id="filter-user-select">
                    <div id="filter-selected-users" class="selected-users">
                        <!-- Aquí se añadirán los usuarios seleccionados para el filtro -->
                    </div>
                    <div id="filter-user-list" class="dropdown-list" style="display: none;">
                        <ul>
                            <!-- Debes cargar dinámicamente los usuarios disponibles -->
                            @foreach($usuarios as $user)
                            <li>
                                <input class="user-checkbox" type="checkbox" id="filter-user-{{ $user->id }}" value="{{ $user->id }}">
                                <label for="filter-user-{{ $user->id }}">{{ $user->name }}</label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="filter_users" id="filter-user-ids"> <!-- Campo oculto para los IDs de usuarios seleccionados -->
            </div>


        </div>

        <!-- Botones del formulario -->
        <div class="form-buttons">
            <button type="button" id="apply-filter-button" class="btn-submit">Aplicar Filtros</button>
            <button type="button" id="clear-filter-button" class="btn-clear">Limpiar</button> <!-- Botón Limpiar -->
            <button type="button" id="cancel-filter-button" class="btn-close">Cancelar</button>
        </div>
    </form>
</div>