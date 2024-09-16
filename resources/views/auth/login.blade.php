<!-- resources/views/auth/login.blade.php -->

@extends('layouts.guest')

@section('content')
<style>
    /* Aseguramos que el fondo y el formulario estén completamente centrados */
    body {
        background-color: #1E1E1E;
        /* Fondo oscuro */
        font-family: 'Nunito', sans-serif;
        height: 100vh;
        display: flex;
        align-items: center;
        /* Centrado vertical */
        justify-content: center;
        /* Centrado horizontal */
        margin: 0;
        flex-direction: column;
    }

    .login-container {
        width: 100%;
        max-width: 400px;
        padding: 3rem 2rem;
        /* Padding uniforme en todos los lados */
        background-color: #2C2C2C;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        color: #fff;
        text-align: center;
    }


    .logo {
        text-align: center;
        margin-bottom: 3rem;
        /* Más margen debajo del logo */
    }

    .logo img {
        max-width: 310px;
        height: auto;
    }

    .login-header {
        font-size: 1.8rem;
        /* Texto más grande */
        font-weight: bold;
        margin-bottom: 1.5rem;
        color: #ffffff;
    }

    .input-label {
        text-align: left;
        color: #CCCCCC;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .input-field {
        background-color: #333333;
        color: #FFFFFF;
        border: 1px solid #555555;
        border-radius: 8px;
        padding: 0.85rem;
        /* Asegura suficiente padding interno */
        width: calc(100% - 1.5rem);
        /* Ajusta el ancho para que haya margen dentro del contenedor */
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }


    .input-field:focus {
        border-color: #AAAAAA;
        outline: none;
    }

    .remember-me {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin-bottom: 1.5rem;
        /* Espacio suficiente debajo */
    }

    .remember-me span {
        color: #FFFFFF;
        margin-left: 0.5rem;
    }

    .login-btn {
        background-color: #FFFFFF;
        color: #000;
        width: 100%;
        padding: 0.85rem;
        border-radius: 8px;
        /* Botón más redondeado */
        text-align: center;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease, color 0.3s ease;
        margin-top: 1.5rem;
        /* Separación clara del botón */
    }

    .login-btn:hover {
        background-color: #4A4A4A;
        color: #FFFFFF;
    }

    .forgot-password {
        text-align: right;
        margin-top: 1rem;
    }

    .forgot-password a {
        color: #FFFFFF;
        text-decoration: underline;
        font-size: 0.9rem;
    }

    .forgot-password a:hover {
        color: #AAAAAA;
    }
</style>



<div class="login-container">

<div class="logo">
    <img src="{{ asset('images/logo_empresa.png') }}" alt="Logo de la Empresa">
</div>


    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Nombre de Usuario -->
        <div>
            <x-input-label for="name" class="input-label" :value="__('Nombre de Usuario')" />
            <x-text-input id="name" class="input-field block mt-1" type="text" name="name" :value="old('name') ?? Cookie::get('remember_name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Password (Contraseña) -->
        <div>
            <x-input-label for="password" class="input-label" :value="__('Contraseña')" />
            <x-text-input id="password" class="input-field block mt-1" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="remember-me">
            <input id="remember_me" type="checkbox" name="remember" {{ old('remember') || Cookie::get('remember_me') ? 'checked' : '' }}>
            <span>{{ __('Recordarme') }}</span>
        </div>


        <!-- Botón de Iniciar Sesión -->
        <div>
            <button class="login-btn">
                {{ __('Entrar') }}
            </button>
        </div>

        <!-- Enlace para recordar contraseña 
        <div class="forgot-password">
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                {{ __('¿Olvidaste tu contraseña?') }}
            </a>
            @endif
        </div> -->
    </form>
</div>
@endsection