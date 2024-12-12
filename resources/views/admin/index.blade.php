@extends('layouts.app')

@section('content')
<div class="header-admin">
    <h2 class="title">
        Panel de Administrador
    </h2>
</div>
<input type="hidden" id="user-session-id" value="{{ Auth::user()->id }}">

<!-- Contenedor Principal -->
<div class="admin-panel">
    <!-- Sección: Modificar Datos -->
    @include('admin.partials.modify-data')
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/modify-data.js') }}"></script>
@endsection
