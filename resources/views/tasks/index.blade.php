@extends('layouts.app')

@section('content')
<div class="header-tareas">
    <h2 class="title">Tareas</h2>

    <div class="actions">
        <!-- Panel informativo de los filtros aplicados -->
        <div id="filter-info-panel" class="filter-info-panel hide">
            <div id="filter-info-content" class="filter-info-content">
                <!-- Aquí se mostrarán los filtros aplicados -->
            </div>
        </div>


        <!-- Desplegable de Ordenar por -->
        <div class="sort-container">
            <label for="sort-select" class="sort-label">Ordenar por:</label>
            <select id="sort-select" class="sort-select">
                <option value="fecha_creacion" selected>Fecha de Creación</option>
                <option value="cliente">Cliente</option>
                <option value="asunto">Asunto</option>
                <option value="estado">Estado</option>
            </select>
        </div>

        <!-- Botón de Filtrar Tarea -->
        <button id="filter-task-button" class="btn-new-task">Filtrar</button>

        <!-- Botón de Nueva Tarea -->
        <button id="new-task-button" class="btn-new-task">Nueva Tarea</button>
    </div>
</div>

<!-- Contenedor de la notificación de éxito -->
<div id="success-notification" class="notification">
    <div class="notification-content">
        <span class="notification-icon">✔️</span>
        <span class="notification-message">Tarea creada exitosamente</span>
    </div>
    <div class="notification-timer"></div> <!-- Barra de tiempo -->
</div>

<!-- Contenedor de la tabla de tareas -->
@include('tasks.partials.task-table')

<!-- Pasamos los datos de clientes, asuntos y tipos como un atributo data -->
<div id="clientes-data" data-clientes='@json($clientes)'></div>
<div id="asuntos-data" data-asuntos='@json($asuntos)'></div>
<div id="tipos-data" data-tipos='@json($tipos)'></div>
<div id="usuarios-data" data-usuarios='@json($usuarios)'></div>

<!-- Incluir el formulario de nueva tarea -->
@include('tasks.partials.task-form')

<!-- Incluir el formulario de filtrar tareas -->
@include('tasks.partials.filter-task-form')

@endsection

@section('scripts')

<script src="{{ asset('js/tasks.js') }}"></script>
<script src="{{ asset('js/filter-tasks.js') }}"></script>


@endsection