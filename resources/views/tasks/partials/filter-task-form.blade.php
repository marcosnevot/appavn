<input type="hidden" id="user-session-id" value="{{ auth()->user()->id }}">

<!-- Formulario para filtrar tareas -->
<div id="filter-task-form" class="task-form hide">
    <h3 class="form-title">Filtrar Tareas</h3>
    <!-- Formulario para filtrar tareas -->
    <form method="POST" id="filter-task-form-content" enctype="multipart/form-data">
        @csrf
        <!-- Fila 1: Cliente, Asunto, Tipo, Subtipo, Estado -->
        <div class="form-row">

            <div class="form-group row1">
                <label for="filter-cliente-input">Cliente:</label>
                <div class="autocomplete">
                    <input type="text" id="filter-cliente-input" class="autocomplete-input" placeholder="Buscar cliente..." autocomplete="off">
                    <input type="hidden" name="filter_cliente_ids" id="filter-cliente-ids">
                    <div id="filter-cliente-selected" class="selected-items-container"></div>
                    <ul id="filter-cliente-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group row1">
                <label for="filter-asunto-input">Asunto:</label>
                <div class="autocomplete">
                    <input type="text" id="filter-asunto-input" class="autocomplete-input" placeholder="Buscar asunto..." autocomplete="off">
                    <input type="hidden" name="filter_asunto_ids" id="filter-asunto-ids">
                    <div id="filter-asunto-selected" class="selected-items-container"></div>
                    <ul id="filter-asunto-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group row1">
                <label for="filter-tipo-input">Tipo de Tarea:</label>
                <div class="autocomplete">
                    <input type="text" id="filter-tipo-input" class="autocomplete-input" placeholder="Buscar tipo..." autocomplete="off">
                    <input type="hidden" name="filter_tipo_ids" id="filter-tipo-ids">
                    <div id="filter-tipo-selected" class="selected-items-container"></div>
                    <ul id="filter-tipo-list" class="autocomplete-list" aria-selected="true"></ul>
                </div>
            </div>




        </div>

        <!-- Fila 2: Asignado a, Archivo, Facturable, Facturado -->
        <div class="form-row">
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


            <div class="form-group narrow" style="display: none;">
                <label for="filter-archivo">Archivo:</label>
                <input type="text" name="filter_archivo" id="filter-archivo">
            </div>

            <!-- Facturable -->
            <div class="form-group grow">
                <label for="filter-facturable">Facturable:</label>
                <div class="custom-select" id="filter-facturable-select">
                    <div id="filter-selected-facturables" class="selected-facturables">
                    </div>
                    <div id="filter-facturable-list" class="dropdown-list" style="display: none;">
                        <ul>
                            <li>
                                <input class="user-checkbox" type="checkbox" id="filter-facturable-1" value="1">
                                <label for="filter-facturable-1">SI</label>
                            </li>
                            <li>
                                <input class="user-checkbox" type="checkbox" id="filter-facturable-0" value="0">
                                <label for="filter-facturable-0">NO</label>
                            </li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="filter_facturable" id="filter-facturable-ids"> <!-- Campo oculto -->
            </div>


            <!-- Facturado -->
            <div class="form-group grow">
                <label for="filter-facturado">Facturado:</label>
                <div class="custom-select" id="filter-facturado-select">
                    <div id="filter-selected-facturados" class="selected-items">
                    </div>
                    <div id="filter-facturado-list" class="dropdown-list" style="display: none;">
                        <ul>
                            <li><input class="user-checkbox" type="checkbox" id="filter-facturado-si" value="SI"><label for="filter-facturado-si">SI</label></li>
                            <li><input class="user-checkbox" type="checkbox" id="filter-facturado-no" value="NO"><label for="filter-facturado-no">NO</label></li>
                            <li><input class="user-checkbox" type="checkbox" id="filter-facturado-nunca" value="NUNCA"><label for="filter-facturado-nunca">Nunca</label></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="filter_facturado" id="filter-facturado-ids">
            </div>


            <!-- Subtipo -->
            <div class="form-group grow">
                <label for="filter-subtipo">Subtipo:</label>
                <div class="custom-select" id="filter-subtipo-select">
                    <div id="filter-selected-subtipos" class="selected-items"></div>
                    <div id="filter-subtipo-list" class="dropdown-list" style="display: none;">
                        <ul>
                            <li><input class="user-checkbox" type="checkbox" id="filter-subtipo-ordinaria" value="ORDINARIA"><label for="filter-subtipo-ordinaria">Ordinaria</label></li>
                            <li><input class="user-checkbox" type="checkbox" id="filter-subtipo-extraordinaria" value="EXTRAORDINARIA"><label for="filter-subtipo-extraordinaria">Extraordinaria</label></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="filter_subtipo" id="filter-subtipo-ids">
            </div>

            <div class="form-group grow">
                <label for="filter-estado">Estado:</label>
                <div class="custom-select" id="filter-estado-select">
                    <div id="filter-selected-estados" class="selected-estados">
                        <!-- Aquí se mostrarán las selecciones -->
                    </div>
                    <div id="filter-estado-list" class="dropdown-list" style="display: none;">
                        <ul>
                            <li>
                                <input class="user-checkbox" type="checkbox" id="filter-estado-planificada" value="PLANIFICADA">
                                <label for="filter-estado-planificada">Planificada</label>
                            </li>
                            <li>
                                <input class="user-checkbox" type="checkbox" id="filter-estado-pendiente" value="PENDIENTE">
                                <label for="filter-estado-pendiente">Pendiente</label>
                            </li>
                            <li>
                                <input class="user-checkbox" type="checkbox" id="filter-estado-enespera" value="ENESPERA">
                                <label for="filter-estado-enespera">En Espera</label>
                            </li>
                            <li>
                                <input class="user-checkbox" type="checkbox" id="filter-estado-completada" value="COMPLETADA">
                                <label for="filter-estado-completada">Completada</label>
                            </li>
                            <li>
                                <input class="user-checkbox" type="checkbox" id="filter-estado-recurrente/trimestre" value="RECURRENTE/TRIMESTRE">
                                <label for="filter-estado-recurrente/trimestre">RECURRENTE/TRIMESTRE</label>
                            </li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="filter_estado" id="filter-estado-ids"> <!-- Campo oculto -->
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

            <!-- Campo para filtrar por Descripción -->
            <div class="form-group grow">
                <label for="filter-descripcion-input">Descripción:</label>
                <input type="text" id="filter-descripcion-input" name="filter_descripcion" placeholder="Buscar descripción..." class="form-control">
            </div>

            <!-- Campo para filtrar por Observaciones -->
            <div class="form-group grow">
                <label for="filter-observaciones-input">Observaciones:</label>
                <input type="text" id="filter-observaciones-input" name="filter_observaciones" placeholder="Buscar observaciones..." class="form-control">
            </div>
        </div>

        <!-- Fila 4: Fechas, Tiempo Previsto, Tiempo Real -->
        <div class="form-row" style="margin-bottom:30px">
            <div class="form-group grow">
                <label for="filter-fecha-planificacion">Fecha de Planificación:</label>
                <input type="text"
                    id="filter-fecha-planificacion"
                    name="filter_fecha_planificacion"
                    class="form-control"
                    readonly
                    placeholder="Seleccionar rango de fechas">
            </div>

            <div class="form-group grow">
                <label for="filter-fecha-inicio">Fecha de Inicio:</label>
                <input type="date" name="filter_fecha_inicio" id="filter-fecha-inicio">
            </div>

            <div class="form-group grow">
                <label for="filter-fecha-vencimiento">Fecha de Vencimiento:</label>
                <input type="date" name="filter_fecha_vencimiento" id="filter-fecha-vencimiento">
            </div>

            <div class="form-group grow" style="display:none">
                <label for="filter-fecha-imputacion">Fecha de Imputación:</label>
                <input type="date" name="filter_fecha_imputacion" id="filter-fecha-imputacion">
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

        <!-- Botones del formulario -->
        <div class="form-buttons">
            <button type="button" id="apply-filter-button" class="btn-submit">Aplicar Filtros</button>
            <button type="button" id="clear-filter-button" class="btn-clear">Limpiar</button> <!-- Botón Limpiar -->
            <button type="button" id="cancel-filter-button" class="btn-close">Cancelar</button>
        </div>
    </form>
</div>