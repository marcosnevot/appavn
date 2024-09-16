<!-- resources/views/layouts/navigation.blade.php -->
<nav class="sidebar bg-gray-900 text-white w-88 h-screen flex flex-col justify-between py-6 px-4">
    <!-- Top Section -->
    <div class="space-y-6">
        <!-- Logo -->
        <div class="logo-container">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo_empresa.png') }}" alt="Logo de la Empresa" class="logo-img">
            </a>
        </div>

        <!-- Divider -->
        <hr class="border-gray-700">

        <!-- Navigation Links -->
        <div class="menu-links">
            <a href="{{ route('tasks.index') }}" class="menu-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m-7-8h8M7 20h10a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </span>
                {{ __('Tareas') }}
            </a>
            <a href="{{ route('clients.index') }}" class="menu-link {{ request()->routeIs('clients.index') ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 500 500" fill="currentColor">
                        <path d="M380.666502,305.007405 C380.555864,305.004946 380.445276,305.002661 380.33474,305.000548 C393.961558,305.045201 394.264513,305.309583 380.666502,305.007405 Z M380,305 C434.169626,305 478.279423,348.071249 479.950872,401.835227 C479.983543,402.886146 480,423.941151 480,425 C480,425 457,424.727394 450,425 C450.039053,423.711242 450.036927,402.417908 449.994098,401.122262 C448.940251,369.241544 423.242602,335.960947 380,335 C376.414483,334.920322 372.955938,335.094588 369.626894,335.499024 C368.836084,325.371312 366.785567,315.597172 363.633786,306.332211 C368.956707,305.456198 374.425078,305 380,305 Z M110,285 C121.572318,285 132.685523,286.965697 143.023932,290.581408 C138.508634,299.431795 135.062942,308.918504 132.852997,318.87036 C125.914975,316.552644 118.279045,315.183979 110,315 C66.6376322,314.036392 41.8460786,350.214412 40.0990864,381.480838 C40.0331091,382.661653 40,423.835462 40,425 L10,425 L10.0008529,422.992799 C10.0050211,414.538639 10.024458,382.546321 10.0589468,381.533823 C11.8855278,327.910366 55.9316374,285 110,285 Z M250.33474,245.000548 C262.846343,245.041547 264.125685,245.267781 253.726077,245.070642 C306.391115,246.997315 348.658853,289.652666 349.968706,342.473499 C349.989528,343.313139 350,425 350,425 L320,425 L320.000843,422.720206 C320.005711,409.527717 320.029918,343.533786 320.027299,342.903993 C319.892503,310.493928 294.048934,275.978865 250,275 C206.102031,274.02449 181.236474,311.113802 180.044907,342.637096 C180.014993,343.428469 180,425 180,425 L150,425 L150.00094,420.018554 C150.004489,402.238391 150.018086,342.909495 150.041613,342.087026 C151.582921,288.205792 195.745834,245 250,245 L250.541,245.004 L250.500583,245.003847 C250.445289,245.002704 250.390008,245.001605 250.33474,245.000548 Z M120.33474,285.000548 C133.961558,285.045201 134.264513,285.309583 120.666502,285.007405 C120.555864,285.004946 120.445276,285.002661 120.33474,285.000548 Z M391,165 C424.137085,165 451,191.862915 451,225 C451,258.137085 424.137085,285 391,285 C357.862915,285 331,258.137085 331,225 C331,191.862915 357.862915,165 391,165 Z M110,145 C143.137085,145 170,171.862915 170,205 C170,238.137085 143.137085,265 110,265 C76.862915,265 50,238.137085 50,205 C50,171.862915 76.862915,145 110,145 Z M391,190 C371.670034,190 356,205.670034 356,225 C356,244.329966 371.670034,260 391,260 C410.329966,260 426,244.329966 426,225 C426,205.670034 410.329966,190 391,190 Z M110,170 C90.6700338,170 75,185.670034 75,205 C75,224.329966 90.6700338,240 110,240 C129.329966,240 145,224.329966 145,205 C145,185.670034 129.329966,170 110,170 Z M250,75 C291.421356,75 325,108.578644 325,150 C325,191.421356 291.421356,225 250,225 C208.578644,225 175,191.421356 175,150 C175,108.578644 208.578644,75 250,75 Z M250,100 C222.385763,100 200,122.385763 200,150 C200,177.614237 222.385763,200 250,200 C277.614237,200 300,177.614237 300,150 C300,122.385763 277.614237,100 250,100 Z"></path>
                    </svg>
                </span>
                {{ __('Clientes') }}
            </a>


        </div>
    </div>

    <!-- Bottom Section (User and Logout) -->
    <div class="user-section">
        <div class="user-info">
            <div class="user-avatar">
                <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div>
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-email">{{ Auth::user()->email }}</div>
            </div>
        </div>

        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-link logout">
            <span class="menu-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H5a3 3 0 01-3-3V5a3 3 0 013-3h5a3 3 0 013 3v1" />
                </svg>
            </span>
            {{ __('Cerrar sesión') }}
        </a>


        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</nav>

<!-- Custom CSS for the sidebar -->
<style>
    /* Sidebar Layout */
    .sidebar {
        background-color: #1E1E1E;

    }

    /* Logo */
    .logo-container {
        text-align: center;
        margin-bottom: 2rem;
    }

    .logo-img {
        max-height: 40px;
        /* Reducimos la altura del logo */
        width: auto;
    }

    /* Menu Links */
    .menu-links {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        /* Espacio entre los enlaces */
    }

    .menu-link {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-radius: 8px;
        color: #CCCCCC;
        font-size: 1rem;
        text-decoration: none;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .menu-link:hover {
        background-color: #333333;
        color: #FFFFFF;
    }

    .menu-link.active {
        background-color: #4A4A4A;
        /* Color de fondo para la opción activa */
        color: #FFFFFF;
    }

    .menu-icon {
        margin-right: 10px;
    }

    /* Estilo general para los iconos en el menú */
    .menu-icon svg {
        width: 20px;
        height: 20px;
        stroke: #CCCCCC;
        /* Color gris claro */
        transition: stroke 0.3s ease;
    }

    .menu-link:hover .menu-icon svg {
        stroke: #FFFFFF;
        /* Cambia a blanco cuando se hace hover */
    }


    /* User Section */
    .user-section {
        margin-top: auto;
    }

    .user-info {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        gap: 1rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #4A4A4A;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #FFFFFF;
    }

    .user-name {
        font-size: 1rem;
        color: #FFFFFF;
    }

    .user-email {
        font-size: 0.85rem;
        color: #AAAAAA;
    }

    /* Logout Link */
    .logout {
        color: #CCCCCC;
    }

    .logout:hover {
        background-color: #333333;
        color: #FFFFFF;
    }
</style>