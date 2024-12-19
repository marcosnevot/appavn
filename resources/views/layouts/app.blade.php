<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Alás, Vigil y Nevot Asesores') }}</title>
    <link rel="icon" href="{{ asset('images/logo_empresa2.png') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />



    <!-- Scripts -->
    @vite(['resources/css/panel.css','resources/js/websockets.js','resources/css/admin.css','resources/css/tareas.css','resources/css/detailed-task.css','resources/css/customer-mail.css','resources/css/customers.css','resources/css/detailed-customer.css', 'resources/css/app.css', 'resources/js/app.js'])
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
        <!-- Sidebar (Navbar) con ancho fijo -->
        @include('layouts.navigation')

        <!-- Contenedor principal con flex-grow para ocupar el resto del espacio -->
        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main class="flex-1 py-6 panel" style="height: 100vh;">
            <div class="w-full mx-auto px-4">
                @yield('content')
            </div>
        </main>
    </div>
    @yield('scripts')
</body>


</html>