@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="header-tareas" style="margin-bottom:20px">
    <h2 class="title" style="padding-top: 20px;">
        Panel de Administrador
    </h2>
</div>
<input type="hidden" id="user-session-id" value="{{ Auth::user()->id }}">

<!-- Contenedor Principal -->
<div class="admin-panel">
    <!-- Tarjeta 1: Modificar Datos -->
    <div class="admin-card modify-data">
        <h3>Modificar Datos</h3>
        <!-- Contenido de la sección -->
        @include('admin.partials.modify-data')
    </div>

    <!-- Placeholder para futuras secciones -->
    <div class="admin-card">
        <h3>Futura Sección</h3>
        <p>Contenido o funcionalidades adicionales.</p>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/admin/modify-data.js') }}"></script>
@endsection