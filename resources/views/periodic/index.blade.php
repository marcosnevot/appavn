@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="header-tareas" style="margin-bottom:20px">
    <h2 class="title" style="padding-top: 20px;">
        Tareas Periódicas Activas
    </h2>
</div>
<input type="hidden" id="user-session-id" value="{{ Auth::user()->id }}">
<div id="notificationAdmin-container" style="position: fixed; top: 20px; right: 20px; z-index: 1000;"></div>

<!-- Contenedor Principal -->
<div class="admin-panel">

    <!-- Tarjeta: Gestión de Tareas Periódicas -->

    @include('periodic.partials.periodic')


</div>

@endsection

@section('scripts')
<script>
    function showSuccessNotification(message) {
        Toastify({
            text: message,
            duration: 3000, // Duración en milisegundos
            close: true, // Muestra un botón de cierre
            gravity: "top", // Posición: "top" o "bottom"
            position: "right", // Posición: "left", "center", "right"
            backgroundColor: "#4CAF50", // Color de fondo (verde para éxito)
        }).showToast();
    }

    function showErrorNotification(message) {
        Toastify({
            text: message,
            duration: 5000, // Más tiempo para errores
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#F44336", // Color de fondo (rojo para errores)
        }).showToast();
    }
</script>

<script src="{{ asset('js/admin/periodic.js') }}"></script>

@endsection