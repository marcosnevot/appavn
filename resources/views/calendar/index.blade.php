@extends('layouts.app')

@section('content')
<div class="header-tareas">
    <h2 style="width: auto; min-width:220px; max-width:600px; margin-bottom:10px;" class="title">
        Calendario
    </h2>
    <input type="hidden" id="user-session-id" value="{{ Auth::user()->id }}">

    <div class="actions">
    </div>
</div>

<div id="calendar"></div> <!-- Contenedor del calendario -->




@endsection

@section('styles')
<!-- Estilos para FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('calendar.css') }}">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Scripts de FullCalendar -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
<script src="{{ asset('js/calendar/calendar.js') }}"></script>
@endsection