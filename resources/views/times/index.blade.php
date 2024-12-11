@extends('layouts.app')

@section('content')
<div class="header-tareas">
    <h2 style="width: auto; min-width:220px;max-width:600px;" class="title">Tiempos de <span id="selected-user-names">todos</span></h2>

    <div class="actions">


        <div id="quick-filters-container" style="display:flex">

            <!-- Contenedor de Filtro Rápido de Asignación de Usuarios -->
            <div class="form-group grow" style="margin-bottom: 0px;">
                <div class="quick-filter-asignacion" style="width:150px;">
                    <div class="custom-select" name="quick-filter-user-select" tabindex="0" id="quick-filter-user-select">
                        <div id="quick-filter-selected-users" class="selected-users">
                            <!-- Aquí se añadirán los usuarios seleccionados para el filtro rápido -->
                        </div>
                        <div id="quick-filter-user-list" class="dropdown-list" style="display: none;">
                            <ul>
                                @foreach($usuarios as $user)
                                <li>
                                    <input class="user-checkbox" type="checkbox" id="quick-filter-user-{{ $user->id }}" value="{{ $user->id }}">
                                    <label for="quick-filter-user-{{ $user->id }}">{{ $user->name }}</label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" name="quick_filter_users" id="quick-filter-user-ids"> <!-- Campo oculto para IDs seleccionados -->
                </div>
            </div>

            <div class="form-group grow">
                <input type="text"
                    name="quick_fecha_planificacion"
                    id="quick-fecha-planificacion"
                    placeholder="Seleccionar rango de fechas"
                    readonly
                    class="form-control">
            </div>

        </div>

        <span class="actions-space"></span>

        <span class="actions-space"></span>


        <!-- Panel de horas -->
        <div class="hours-summary">
            <p><strong>Total horas previstas:</strong> <span id="total-tiempo-previsto">0</span> h</p>
            <p><strong>Total horas reales:</strong> <span id="total-tiempo-real">0</span> h</p>
        </div>


        <!-- Panel informativo de los filtros aplicados -->
        <div id="filter-info-panel" class="filter-info-panel hide">
            <div id="filter-info-content" class="filter-info-content">
                <!-- Aquí se mostrarán los filtros aplicados -->
            </div>
        </div>


        <!-- Desplegable de Ordenar por -->
        <div class="sort-container" style="display: none;">
            <label for="sort-select" class="sort-label">Ordenar por:</label>
            <select id="sort-select" class="sort-select">
                <option value="fecha_creacion" selected>Fecha de Creación</option>
                <option value="cliente">Cliente</option>
                <option value="asunto">Asunto</option>
                <option value="estado">Estado</option>
            </select>
        </div>
        <button id="export-tasks-button" class="btn-excel">
            <img src="{{ asset('images/excel-icon.png') }}" alt="Excel">
        </button>
        <!-- Botón de Filtrar Tarea -->
        <button id="filter-task-button" class="btn-new-task">Filtrar</button>

        <!-- Botón de Nueva Tarea -->
        <button id="new-task-button" class="btn-new-task" style="display: none;">Nueva Tarea</button>


    </div>
</div>

<!-- Contenedor de la notificación unificada -->
<div id="notification" class="notification">
    <div class="notification-content">
        <span class="notification-icon">✔️</span> <!-- Cambia el icono según el tipo -->
        <span class="notification-message">Mensaje de la notificación</span>
    </div>
    <div class="notification-timer"></div> <!-- Barra de tiempo -->
</div>


<!-- Contenedor de la tabla de tareas -->
@include('times.partials.task-table')

<!-- Pasamos los datos de clientes, asuntos y tipos como un atributo data -->
<div id="clientes-data" data-clientes='@json($clientes)'></div>
<div id="asuntos-data" data-asuntos='@json($asuntos)'></div>
<div id="tipos-data" data-tipos='@json($tipos)'></div>
<div id="usuarios-data" data-usuarios='@json($usuarios)'></div>

<!-- Incluir el formulario de nueva tarea -->
@include('tasks.partials.task-form')

<!-- Incluir el formulario de filtrar tareas -->
@include('times.partials.filter-task-form')

<!-- Formulario de edición de la tarea -->
@include('tasks.partials.edit-task-form')

@endsection

@section('scripts')

<script src="{{ asset('js/times/common-times.js') }}"></script>
<script src="{{ asset('js/times/add-times.js') }}"></script>
<script src="{{ asset('js/times/filter-times.js') }}"></script>
<script src="{{ asset('js/times/times.js') }}"></script>
<script src="{{ asset('js/times/times-modal.js') }}"></script>


@endsection