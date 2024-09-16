<!-- resources/views/layouts/guest.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Cargar estilos personalizados -->
    <link rel="icon" href="{{ asset('images/logo_empresa2.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="antialiased">
        <!-- Este div envuelve el contenido principal -->
            @yield('content')
</body>
</html>
