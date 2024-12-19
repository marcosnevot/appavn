@extends('layouts.app')

@section('content')
<div class="header-clientes">
    <h2 class="title">Clientes</h2>

    <div class="actions2">
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
                <option value="nombre">Nombre</option>
                <option value="tipo_cliente">Tipo de Cliente</option>
                <option value="situacion">Situación</option>
            </select>
        </div>

        <!-- Botón de Enviar Correo -->
        <button id="open-mail-modal" class="btn-excel">
        <img src="{{ asset('images/outlook-icon.png') }}" alt="Outlook">

        </button>


        <button id="export-customers-button" class="btn-excel">
            <img src="{{ asset('images/excel-icon.png') }}" alt="Excel">
        </button>
        <!-- Botón de Filtrar Cliente -->
        <button id="filter-customer-button" class="btn-new-customer">Filtrar</button>

        <!-- Botón de Nuevo Cliente -->
        <button id="new-customer-button" class="btn-new-customer">Nuevo Cliente</button>
    </div>
</div>

<!-- Contenedor de la notificación unificada -->
<div id="notification" class="notification">
    <div class="notification-content">
        <span class="notification-icon">✔️</span>
        <span class="notification-message">Mensaje de la notificación</span>
    </div>
    <div class="notification-timer"></div>
</div>

<!-- Contenedor de la tabla de clientes -->
@include('customers.partials.customer-table')

<!-- Pasamos los datos de clientes como un atributo data -->
<div id="clasificaciones-data" data-clasificaciones='@json($clasificaciones)'></div>
<div id="tipos-data" data-tipos='@json($tiposClientes)'></div>
<div id="situaciones-data" data-situaciones='@json($situaciones)'></div>
<div id="tributaciones-data" data-tributaciones='@json($tributaciones)'></div>
<div id="usuarios-data" data-usuarios='@json($usuarios)'></div>
<div id="clientes-data" data-clientes='@json($clientes)'></div>

<!-- Incluir la interfaz de email -->
@include('customers.partials.customer-mail')

<!-- Incluir el formulario de nuevo cliente -->
@include('customers.partials.customer-form')

<!-- Incluir el formulario de filtrar clientes -->
@include('customers.partials.filter-customer-form')

<!-- Formulario de edición de la tarea -->
@include('customers.partials.edit-customer-form')

@endsection

@section('scripts')

<script src="{{ asset('js/commonCustomers.js') }}"></script>
<script src="{{ asset('js/customers/common-customers.js') }}"></script>
<script src="{{ asset('js/customers/add-customers.js') }}"></script>
<script src="{{ asset('js/customers/customers.js') }}"></script>
<script src="{{ asset('js/customers/filter-customers.js') }}"></script>
<script src="{{ asset('js/customers/customer-modal.js') }}"></script>
<script src="{{ asset('js/customers/customer-mail.js') }}"></script>


@endsection