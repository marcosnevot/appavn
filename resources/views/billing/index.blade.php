@extends('layouts.app')

@section('content')
<div class="header-tareas">
<h2 style="width: auto; min-width:220px; max-width:600px;" class="title">
        Facturación de <span id="selected-user-names">todos</span>
    </h2>
    <div class="actions">

        <!-- Contenedor de Filtro Rápido de Planificación -->
        <div class="quick-filter-planificacion">
            <div id="planificacion-filter-buttons" class="planificacion-buttons-container">
                <!-- Aquí aparecerán los botones de planificación dinámica -->
            </div>
        </div>


        <span class="actions-space"></span>

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
        <button id="new-task-button" class="btn-new-task">Nueva Tarea</button>


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
@include('billing.partials.task-table')

<!-- Pasamos los datos de clientes, asuntos y tipos como un atributo data -->
<div id="clientes-data" data-clientes='@json($clientes)'></div>
<div id="asuntos-data" data-asuntos='@json($asuntos)'></div>
<div id="tipos-data" data-tipos='@json($tipos)'></div>
<div id="usuarios-data" data-usuarios='@json($usuarios)'></div>

<!-- Incluir el formulario de nueva tarea -->
@include('billing.partials.task-form')

<!-- Incluir el formulario de filtrar tareas -->
@include('billing.partials.filter-task-form')

<!-- Formulario de edición de la tarea -->
@include('tasks.partials.edit-task-form')

@endsection

@section('scripts')

<script>
    // Variable para indicar que estamos en la vista de facturación
    const isBillingView = true;
</script>

<script src="{{ asset(path: 'js/common.js') }}"></script>
<script src="{{ asset('js/billing/common-billing.js') }}"></script>
<script src="{{ asset('js/billing/billing.js') }}"></script>
<script src="{{ asset('js/billing/add-billing.js') }}"></script>
<script src="{{ asset('js/billing/filter-billing.js') }}"></script>
<script src="{{ asset('js/billing/billing-modal.js') }}"></script>


@endsection