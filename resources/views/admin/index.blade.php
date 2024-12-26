@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="header-tareas" style="margin-bottom:20px">
    <h2 class="title" style="padding-top: 20px;">
        Panel de Administrador
    </h2>
</div>
<input type="hidden" id="user-session-id" value="{{ Auth::user()->id }}">
<div id="notificationAdmin-container" style="position: fixed; top: 20px; right: 20px; z-index: 1000;"></div>

<!-- Contenedor Principal -->
<div class="admin-panel">
    <!-- Tarjeta 1: Modificar Datos -->
    <div class="admin-card modify-data">
        <div class="toggle-section" data-toggle-section>
            <div class="section-header" data-toggle-header>
                <h3>Modificar Datos</h3>
                <button class="toggle-button" aria-expanded="false" data-toggle-button>
                    <span class="icon-toggle">▼</span>
                </button>
            </div>
            <div class="section-content" data-toggle-content class="modify-data-container">
                @include('admin.partials.modify-data')
            </div>
        </div>
    </div>

    <!-- Tarjeta: Gestión de Usuarios -->
    <div class="admin-card manage-users">
        <div class="toggle-section" data-toggle-section>
            <div class="section-header" data-toggle-header>
                <h3>Gestión de Usuarios</h3>
                <button class="toggle-button" aria-expanded="false" data-toggle-button>
                    <span class="icon-toggle">▼</span>
                </button>
            </div>
            <div class="section-content" data-toggle-content class="manage-users-container">
                @include('admin.partials.manage-users')
            </div>
        </div>
    </div>


    <!-- Tarjeta: Gestión de Tareas Periódicas -->
    <div class="admin-card periodic-tasks">
        <div class="toggle-section" data-toggle-section>
            <div class="section-header" data-toggle-header>
                <h3>Tareas Periódicas Activas</h3>
                <button class="toggle-button" aria-expanded="false" data-toggle-button>
                    <span class="icon-toggle">▼</span>
                </button>
            </div>
            <div class="section-content" data-toggle-content class="periodic-tasks-container">
                @include('admin.partials.periodic')
            </div>
        </div>
    </div>




    <!-- Placeholder para futuras secciones -->
    <div class="admin-card">
        <div class="toggle-section" data-toggle-section>
            <div class="section-header" data-toggle-header>
                <h3>Futura Sección</h3>
                <button class="toggle-button" aria-expanded="false" data-toggle-button>
                    <span class="icon-toggle">▼</span>
                </button>
            </div>
            <div class="section-content" data-toggle-content class="modify-data-container">
                <p>Contenido o funcionalidades adicionales.</p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/admin/modify-data.js') }}"></script>
<script src="{{ asset('js/admin/manage-users.js') }}"></script>
<script src="{{ asset('js/admin/periodic.js') }}"></script>

@endsection