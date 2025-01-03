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
            <a href="{{ route('tasks.index') }}"
                class="menu-link {{ request()->routeIs('tasks.index') && !request()->query('estado') && !request()->query('asunto') && !request()->query('task_id') && !request()->query('usuario') ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m-7-8h8M7 20h10a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </span>
                {{ __('Tareas') }}
            </a>

            <a href="{{ route('billing.index') }}" class="menu-link {{ request()->routeIs('billing.index') ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <!-- Contorno del Documento -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 3h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z" />

                        <!-- Líneas de Texto en el Documento -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 11h6M8 15h4" />

                        <!-- Marca de Pago -->
                        <circle cx="16" cy="18" r="1" fill="currentColor" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h2v2h-2" />
                    </svg>
                </span>
                {{ __('Facturación') }}
            </a>

            <a href="{{ route('expiration.index') }}" class="menu-link {{ request()->routeIs('expiration.index') ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <!-- Contorno del Calendario -->
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <!-- Líneas de separación del calendario -->
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                        <!-- Reloj en la esquina -->
                        <circle cx="18" cy="15" r="4" />
                        <line x1="18" y1="13" x2="18" y2="15.5" />
                        <line x1="18" y1="15" x2="19.5" y2="16" />
                    </svg>
                </span>
                {{ __('Vencimientos') }}
            </a>

            <a href="{{ route('tasks.index', ['estado' => 'ENESPERA']) }}"
                class="menu-link {{ request()->fullUrlIs('*estado=ENESPERA*') ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <!-- Contorno de la mano -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v8M9 3v8M15 3v8M6 8v8a4 4 0 004 4h4a4 4 0 004-4v-8" />
                    </svg>

                </span>


                {{ __('En Espera') }}
            </a>



            <a href="{{ route('tasks.index', ['asunto' => 'CITA,LLAMADA TELEFONICA']) }}"
                class="menu-link {{ request()->query('asunto') === 'CITA,LLAMADA TELEFONICA' ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 16.92v3a2 2 0 01-2.18 2 19.86 19.86 0 01-8.63-3.1 19.5 19.5 0 01-6-6A19.86 19.86 0 012.08 4.18 2 2 0 014.06 2h3a2 2 0 012 1.72c.13.94.37 1.85.7 2.73a2 2 0 01-.45 2.11l-1.27 1.27a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.88.33 1.79.57 2.73.7a2 2 0 011.72 2z" />
                    </svg>
                </span>
                {{ __('Citas/Llamadas') }}
            </a>






            <hr class="border-gray-700">


            <a href="{{ route('times.index') }}" class="menu-link {{ request()->routeIs('times.index') ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <!-- Círculo exterior del reloj -->
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />

                        <!-- Aguja del reloj -->
                        <line x1="12" y1="6" x2="12" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <line x1="12" y1="12" x2="16" y2="14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />

                        <!-- Punto central del reloj -->
                        <circle cx="12" cy="12" r="1.5" fill="currentColor" />
                    </svg>

                </span>
                {{ __('Control de Tiempos') }}
            </a>
            <hr class="border-gray-700">



            <a href="{{ route('client.index') }}" class="menu-link {{ request()->routeIs('client.index') ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 500 500" fill="currentColor">
                        <path d="M380.666502,305.007405 C380.555864,305.004946 380.445276,305.002661 380.33474,305.000548 C393.961558,305.045201 394.264513,305.309583 380.666502,305.007405 Z M380,305 C434.169626,305 478.279423,348.071249 479.950872,401.835227 C479.983543,402.886146 480,423.941151 480,425 C480,425 457,424.727394 450,425 C450.039053,423.711242 450.036927,402.417908 449.994098,401.122262 C448.940251,369.241544 423.242602,335.960947 380,335 C376.414483,334.920322 372.955938,335.094588 369.626894,335.499024 C368.836084,325.371312 366.785567,315.597172 363.633786,306.332211 C368.956707,305.456198 374.425078,305 380,305 Z M110,285 C121.572318,285 132.685523,286.965697 143.023932,290.581408 C138.508634,299.431795 135.062942,308.918504 132.852997,318.87036 C125.914975,316.552644 118.279045,315.183979 110,315 C66.6376322,314.036392 41.8460786,350.214412 40.0990864,381.480838 C40.0331091,382.661653 40,423.835462 40,425 L10,425 L10.0008529,422.992799 C10.0050211,414.538639 10.024458,382.546321 10.0589468,381.533823 C11.8855278,327.910366 55.9316374,285 110,285 Z M250.33474,245.000548 C262.846343,245.041547 264.125685,245.267781 253.726077,245.070642 C306.391115,246.997315 348.658853,289.652666 349.968706,342.473499 C349.989528,343.313139 350,425 350,425 L320,425 L320.000843,422.720206 C320.005711,409.527717 320.029918,343.533786 320.027299,342.903993 C319.892503,310.493928 294.048934,275.978865 250,275 C206.102031,274.02449 181.236474,311.113802 180.044907,342.637096 C180.014993,343.428469 180,425 180,425 L150,425 L150.00094,420.018554 C150.004489,402.238391 150.018086,342.909495 150.041613,342.087026 C151.582921,288.205792 195.745834,245 250,245 L250.541,245.004 L250.500583,245.003847 C250.445289,245.002704 250.390008,245.001605 250.33474,245.000548 Z M120.33474,285.000548 C133.961558,285.045201 134.264513,285.309583 120.666502,285.007405 C120.555864,285.004946 120.445276,285.002661 120.33474,285.000548 Z M391,165 C424.137085,165 451,191.862915 451,225 C451,258.137085 424.137085,285 391,285 C357.862915,285 331,258.137085 331,225 C331,191.862915 357.862915,165 391,165 Z M110,145 C143.137085,145 170,171.862915 170,205 C170,238.137085 143.137085,265 110,265 C76.862915,265 50,238.137085 50,205 C50,171.862915 76.862915,145 110,145 Z M391,190 C371.670034,190 356,205.670034 356,225 C356,244.329966 371.670034,260 391,260 C410.329966,260 426,244.329966 426,225 C426,205.670034 410.329966,190 391,190 Z M110,170 C90.6700338,170 75,185.670034 75,205 C75,224.329966 90.6700338,240 110,240 C129.329966,240 145,224.329966 145,205 C145,185.670034 129.329966,170 110,170 Z M250,75 C291.421356,75 325,108.578644 325,150 C325,191.421356 291.421356,225 250,225 C208.578644,225 175,191.421356 175,150 C175,108.578644 208.578644,75 250,75 Z M250,100 C222.385763,100 200,122.385763 200,150 C200,177.614237 222.385763,200 250,200 C277.614237,200 300,177.614237 300,150 C300,122.385763 277.614237,100 250,100 Z"></path>
                    </svg>
                </span>
                {{ __('Clientes') }}
            </a>

            <hr class="border-gray-700">

            <a href="{{ route('calendar.index') }}" class="menu-link {{ request()->routeIs('calendar.index') ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <!-- Contorno del Calendario -->
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <!-- Líneas de separación del calendario -->
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                        <!-- Día destacado -->
                        <circle cx="16" cy="16" r="1.5" fill="currentColor" />
                    </svg>
                </span>
                {{ __('Calendario') }}
            </a>

            <!-- Links a las tareas de cada user -->
            @php
            $user = auth()->user();
            @endphp


            @if ($user->hasRole(roles: 'admin') || ($user->hasRole('employee') && $user->id === 2))
            <hr class="border-gray-700">
            @inject('users', 'App\Models\User')

            <div class="user-tasks-container">
                <div class="user-tasks-grid">
                    @php
                    // Obtener los IDs de los usuarios generados en el bucle
                    $userIds = $users::where('id', '!=', auth()->id())->pluck('id')->toArray();
                    @endphp

                    @foreach ($userIds as $userId)
                    <a href="{{ route('tasks.index', ['usuario' => $userId]) }}"
                        class="user-task-link {{ in_array($userId, explode(',', request()->query('usuario', ''))) ? 'active' : '' }}">
                        {{ $users::find($userId)->name }}
                    </a>
                    @endforeach

                    <!-- Enlace para "Todos" -->
                    <a href="{{ route('tasks.index', ['usuario' => implode(',', $userIds)]) }}"
                        class="user-task-link {{ implode(',', $userIds) === request()->query('usuario', '') ? 'active' : '' }}">
                        Todos
                    </a>
                </div>

            </div>

            @endif


        </div>
    </div>

    <!-- Bottom Section (User and Logout) -->
    <div class="user-section">

        <div class="sidebarOptions">

            <div class="sidebarOptionsButtons">

                <!-- Notification Button -->
                <div id="notification-toggle" class="notification-toggle">

                    <button class="notification-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V11a6 6 0 10-12 0v3c0 .386-.146.735-.405 1.005L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span id="notification-counter">0</span>
                    </button>
                    <!-- Notificación flotante -->
                    <!-- Notificación flotante de ejemplo -->
                    <div id="floating-notification" class="floating-notification hidden">
                    </div>

                    <!-- Notification Panel -->
                    <div id="notification-panel" class="notification-panel hidden">
                        <div class="notification-header">
                            <h3>Notificaciones</h3>
                            <button id="clear-notifications" title="Marcar todas como leídas">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon-clear" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m2 0v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6h16z" />
                                </svg>
                            </button>

                        </div>
                        <ul id="notification-list" class="notification-list">

                        </ul>

                        <div id="no-notifications" class="no-notifications hidden">
                            <p>No tienes notificaciones pendientes.</p>
                        </div>
                    </div>
                </div>
                <button onclick="abrirWhatsApp()" class="notification-btn" style="margin-left: -3px;">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 32 32"
                        style="fill:#FFFFFF;">
                        <path fill-rule="evenodd" d="M 24.503906 7.503906 C 22.246094 5.246094 19.246094 4 16.050781 4 C 9.464844 4 4.101563 9.359375 4.101563 15.945313 C 4.097656 18.050781 4.648438 20.105469 5.695313 21.917969 L 4 28.109375 L 10.335938 26.445313 C 12.078125 27.398438 14.046875 27.898438 16.046875 27.902344 L 16.050781 27.902344 C 22.636719 27.902344 27.996094 22.542969 28 15.953125 C 28 12.761719 26.757813 9.761719 24.503906 7.503906 Z M 16.050781 25.882813 L 16.046875 25.882813 C 14.265625 25.882813 12.515625 25.402344 10.992188 24.5 L 10.628906 24.285156 L 6.867188 25.269531 L 7.871094 21.605469 L 7.636719 21.230469 C 6.640625 19.648438 6.117188 17.820313 6.117188 15.945313 C 6.117188 10.472656 10.574219 6.019531 16.054688 6.019531 C 18.707031 6.019531 21.199219 7.054688 23.074219 8.929688 C 24.949219 10.808594 25.980469 13.300781 25.980469 15.953125 C 25.980469 21.429688 21.523438 25.882813 16.050781 25.882813 Z M 21.496094 18.445313 C 21.199219 18.296875 19.730469 17.574219 19.457031 17.476563 C 19.183594 17.375 18.984375 17.328125 18.785156 17.625 C 18.585938 17.925781 18.015625 18.597656 17.839844 18.796875 C 17.667969 18.992188 17.492188 19.019531 17.195313 18.871094 C 16.894531 18.722656 15.933594 18.40625 14.792969 17.386719 C 13.90625 16.597656 13.304688 15.617188 13.132813 15.320313 C 12.957031 15.019531 13.113281 14.859375 13.261719 14.710938 C 13.398438 14.578125 13.5625 14.363281 13.710938 14.1875 C 13.859375 14.015625 13.910156 13.890625 14.011719 13.691406 C 14.109375 13.492188 14.058594 13.316406 13.984375 13.167969 C 13.910156 13.019531 13.3125 11.546875 13.0625 10.949219 C 12.820313 10.367188 12.574219 10.449219 12.390625 10.4375 C 12.21875 10.429688 12.019531 10.429688 11.820313 10.429688 C 11.621094 10.429688 11.296875 10.503906 11.023438 10.804688 C 10.75 11.101563 9.980469 11.824219 9.980469 13.292969 C 9.980469 14.761719 11.050781 16.183594 11.199219 16.382813 C 11.347656 16.578125 13.304688 19.59375 16.300781 20.886719 C 17.011719 21.195313 17.566406 21.378906 18 21.515625 C 18.714844 21.742188 19.367188 21.710938 19.882813 21.636719 C 20.457031 21.550781 21.648438 20.914063 21.898438 20.214844 C 22.144531 19.519531 22.144531 18.921875 22.070313 18.796875 C 21.996094 18.671875 21.796875 18.597656 21.496094 18.445313 Z"></path>
                    </svg>
                </button>

                <button id="open-chat" class="notification-btn" style="margin-left: 10px;display:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 48 48"
                        style="fill:#FFFFFF;">
                        <path d="M38.844,17.559l-7.523-4.343c-0.493-0.284-1.1-0.285-1.594-0.003l-10.245,5.855l0.021-4.018l7.913-4.569	c3.445-1.989,7.938-1.371,10.44,1.722c0.594,0.734,1.04,1.539,1.341,2.382c0.211,0.592,0.772,0.984,1.4,0.984	c1.037,0,1.772-1.03,1.421-2.006c-0.416-1.158-1.033-2.265-1.853-3.275c-2.488-3.065-6.393-4.357-10.151-3.807	c-1.987-2.124-4.699-3.373-7.63-3.473c-4.733-0.161-8.814,2.839-10.525,7.018c-2.842,0.654-5.289,2.378-6.847,4.873	c-3.318,5.313-1.284,12.41,4.142,15.543l7.523,4.343c0.493,0.284,1.1,0.285,1.594,0.003l10.245-5.855l-0.021,4.018l-7.902,4.563	c-3.448,1.991-7.945,1.378-10.451-1.715c-0.591-0.73-1.035-1.53-1.336-2.368c-0.212-0.591-0.772-0.982-1.4-0.982h0	c-1.039,0-1.774,1.033-1.421,2.01c0.326,0.901,0.774,1.771,1.344,2.589c2.43,3.487,6.613,5.039,10.645,4.465	c1.987,2.129,4.7,3.381,7.634,3.483c4.736,0.163,8.82-2.838,10.531-7.02c2.841-0.654,5.288-2.378,6.844-4.872	C46.303,27.788,44.269,20.691,38.844,17.559z M34,33.723c0,4.324-3.313,8.077-7.633,8.269c-1.837,0.082-3.585-0.463-5.024-1.496	c0.274-0.13,0.546-0.266,0.812-0.42l7.521-4.342c0.493-0.285,0.799-0.81,0.802-1.38l0.054-9.883c0.003-0.55-0.441-0.999-0.992-1	c-0.549-0.002-0.995,0.441-0.998,0.99l-0.011,2.172L18.498,32.37l-7.918-4.571c-3.745-2.163-5.339-6.908-3.345-10.745	c0.848-1.633,2.196-2.875,3.812-3.605C11.022,13.753,11,14.058,11,14.367v8.684c0,0.569,0.302,1.095,0.794,1.382l8.73,5.055	c0.475,0.275,1.082,0.113,1.358-0.361c0.277-0.476,0.114-1.085-0.362-1.361L14,23.42v-9.143c0-4.325,3.313-8.077,7.634-8.269	c1.835-0.081,3.582,0.462,5.02,1.494c-0.264,0.127-0.526,0.259-0.782,0.407l-7.548,4.357c-0.494,0.285-0.799,0.81-0.802,1.38	l-0.054,9.797c-0.003,0.55,0.441,0.999,0.992,1c0.549,0.002,0.995-0.441,0.998-0.99l0.011-2.087l4.552-2.603L34,24.58V33.723z M40.765,30.946c-0.848,1.633-2.195,2.875-3.812,3.604C36.978,34.248,37,33.944,37,33.636v-8.687c0-0.569-0.302-1.095-0.794-1.382	l-10.191-5.943l3.487-1.994l7.918,4.571C41.165,22.364,42.759,27.109,40.765,30.946z"></path>
                    </svg>
                </button>

                <!-- Div del chat -->
                <div id="chat-box" class="chat-container" style="display: none;">
                    <div class="chat-header">
                        <h3>Asistente Inteligente</h3>
                        <button id="close-chat" class="close-chat-btn">&times;</button>
                    </div>
                    <div id="chat-messages" class="chat-messages"></div>
                    <div class="chat-input-container">
                        <input type="text" id="chat-input" class="chat-input" placeholder="Escribe aquí..." />
                        <button id="send-message" class="send-message-btn">Enviar</button>
                    </div>
                </div>




            </div>

            @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.index') }}" class="option-item admin-button" title="Panel de Administración">

                <span>Admin</span>
            </a>
            @else
            <a href="{{ route('periodic.index') }}" class="option-item admin-button" title="Tareas Periódicas Activas">

                <span>Periódicas</span>
            </a>
            @endif


        </div>

        <hr class="border-gray-700" style="margin-bottom: 20px; ">
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
    <div id="notification-stack" class="notification-stack"></div>
</nav>

<script>
    function abrirWhatsApp() {
        window.open('https://web.whatsapp.com', '_blank', 'width=800,height=600');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const notificationList = document.getElementById('notification-list');
        const notificationCounter = document.getElementById('notification-counter');
        const noNotifications = document.getElementById('no-notifications');
        const clearNotifications = document.getElementById('clear-notifications');
        const floatingNotification = document.getElementById('floating-notification');

        const notificationToggle = document.getElementById('notification-toggle');
        const notificationPanel = document.getElementById('notification-panel');
        console.log(floatingNotification.classList);


        // ChatGPT
        /* document.getElementById('open-chat').addEventListener('click', () => {
            document.getElementById('chat-box').style.display = 'flex';
        }); */

        document.getElementById('close-chat').addEventListener('click', () => {
            document.getElementById('chat-box').style.display = 'none';
        });

        document.getElementById('send-message').addEventListener('click', async () => {
            const message = document.getElementById('chat-input').value.trim();
            if (!message) return;

            const chatMessages = document.getElementById('chat-messages');
            chatMessages.innerHTML += `<div class="user-message">${message}</div>`;

            const response = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    message
                }),
            });

            const data = await response.json();
            chatMessages.innerHTML += `<div class="ai-response">${data.response}</div>`;

            chatMessages.scrollTop = chatMessages.scrollHeight; // Desplazar hacia abajo
            document.getElementById('chat-input').value = ''; // Limpiar input
        });



        const updateNotificationCounter = (count) => {
            if (count > 0) {
                notificationCounter.textContent = count;
                notificationCounter.style.display = 'inline-block'; // Muestra el contador
            } else {
                notificationCounter.textContent = '0';
                notificationCounter.style.display = 'none'; // Oculta el contador
            }
        };


        const sessionUserId = document.getElementById('user-session-id').value;

        // Obtener el contenedor de la pila de notificaciones
        const notificationStack = document.getElementById('notification-stack');
        // Función para verificar si el contenido sobrepasa el max-height
        function updateGradient() {
            if (notificationStack.scrollHeight > notificationStack.clientHeight) {
                notificationStack.classList.add('with-gradient'); // Activa el degradado
            } else {
                notificationStack.classList.remove('with-gradient'); // Desactiva el degradado
            }
        }

        // Verificar inicialmente y cada vez que se agrega contenido
        updateGradient();
        // Función para mostrar notificaciones flotantes
        function showFloatingNotification(notification) {
            // Crear el elemento de la notificación flotante
            const floatingNotification = document.createElement('div');
            floatingNotification.classList.add('floating-notification');

            // Verificar si tiene el campo `reminder`
            if (notification.reminder) {
                floatingNotification.textContent = `${notification.reminder}: ${notification.task_title || 'Sin asunto'}`;
                floatingNotification.classList.add('reminder');
            } else {
                floatingNotification.innerHTML = `<strong>${notification.assigned_by}</strong> te asignó la tarea: ${notification.task_title || 'Sin asunto'}`;
                floatingNotification.classList.add('assigned');
            }

            // Agregar la notificación al contenedor
            notificationStack.appendChild(floatingNotification);
            updateGradient();
            // Forzar reflujo para que la animación funcione
            void floatingNotification.offsetWidth;

            // Usar timeout para forzar la animación
            setTimeout(() => {
                floatingNotification.classList.add('show'); // Clase que activa la animación de entrada
            }, 50); // Pequeño retraso para permitir que el DOM procese el nuevo elemento

            // Ocultar la notificación después de 5 segundos
            setTimeout(() => {
                floatingNotification.classList.add('fade-out'); // Agregar clase de salida
                setTimeout(() => {
                    floatingNotification.remove(); // Eliminar del DOM
                }, 500); // Esperar a que termine la animación
            }, 5000); // Tiempo visible

            // Desplazar automáticamente hacia la última notificación
            notificationStack.scrollTo({
                top: notificationStack.scrollHeight,
                behavior: 'smooth',
            });
        }


        window.Echo.private(`App.Models.User.${sessionUserId}`)
            .notification((notification) => {
                console.log(notification);

                // Obtener el panel de notificaciones y el contador
                const notificationList = document.getElementById('notification-list');
                const notificationCounter = document.getElementById('notification-counter');
                const noNotifications = document.getElementById('no-notifications');

                // Si ya hay más de 30 notificaciones, elimina la más antigua
                if (notificationList.children.length >= 30) {
                    notificationList.removeChild(notificationList.lastChild);
                }

                // Verificar si la notificación ya existe en la lista
                if (document.querySelector(`.notification-item[data-id="${notification.task_id}"]`)) {
                    console.log(`Notificación ${notification.task_id} ya existe.`);
                    return; // Evita añadir duplicados
                }

                // Mostrar la notificación flotante
                showFloatingNotification(notification);



                const clientName = notification.client || 'Sin cliente';

                // Crear un nuevo elemento de notificación con la estructura HTML adecuada
                const notificationItem = document.createElement('li');
                notificationItem.className = 'notification-item';
                notificationItem.dataset.id = notification.id; // Usar un identificador único
                // Verificar si la notificación contiene el atributo 'reminder'
                if (notification.reminder) {
                    // Mostrar el reminder si está presente
                    notificationItem.classList.add('reminderPanel');
                }
                // Verificar si la notificación contiene el atributo 'reminder'
                const notificationMessage = notification.reminder ?
                    `${notification.reminder}` // Mostrar el recordatorio
                    :
                    `<strong>${notification.assigned_by}</strong> te asignó la tarea:`;
                notificationItem.innerHTML = `
                <p class="notification-content">
                            ${notificationMessage} 
                            <a href="/tareas?task_id=${notification.task_id}" 
                                data-task-id="${notification.task_id}" 
                                class="notification-link" 
                                title="Ver tarea">
                                ${notification.task_title || 'Sin asunto'}
                            </a>
                            <br>de ${notification.client || 'Sin cliente'}<br><p style="color:#ababab;">${notification.description || ' '}</p>
                                           
                </p>
                <div class="notification-footer">
                    <span class="notification-date">${formatNotificationDate(notification.created_at)}</span>
                    <button class="mark-as-read-btn" data-id="${notification.id}" title="Marcar como leída">
                        Borrar
                    </button>
                </div>
                `;

                // Añadir la notificación al inicio de la lista
                notificationList.prepend(notificationItem);

                // Actualizar el contador de notificaciones
                const currentCount = parseInt(notificationCounter.textContent || 0);
                updateNotificationCounter(currentCount + 1);

                // Asegurarse de ocultar el mensaje "No tienes notificaciones pendientes"
                if (!noNotifications.classList.contains('hidden')) {
                    noNotifications.classList.add('hidden');
                }
            });


        if (!notificationToggle) {
            console.error('No se encontró el elemento con ID "notification-toggle".');
            return;
        }

        if (!notificationPanel) {
            console.error('No se encontró el elemento con ID "notification-panel".');
            return;
        }

        // Alternar la visibilidad del panel
        notificationToggle.addEventListener('click', (event) => {
            console.log('Botón de notificaciones pulsado');
            event.stopPropagation(); // Prevenir propagación del evento

            if (notificationPanel.classList.contains('hidden')) {
                notificationPanel.classList.remove('hidden');
                notificationPanel.classList.add('active');
            } else {
                notificationPanel.classList.remove('active');
                notificationPanel.classList.add('hidden');
            }
        });

        // Prevenir que el clic dentro del panel cierre el panel
        notificationPanel.addEventListener('click', (event) => {
            event.stopPropagation(); // Evitar que el clic cierre el panel
        });


        // Ocultar el panel si se hace clic fuera de él
        document.addEventListener('click', (event) => {
            if (!notificationPanel.contains(event.target) && !notificationToggle.contains(event.target)) {
                notificationPanel.classList.remove('active');
                notificationPanel.classList.add('hidden');
            }
        });
        // Fetch notifications from the server
        fetch('/notifications')
            .then((response) => response.json())
            .then((notifications) => {
                console.log(notifications); // Inspecciona el orden aquí

                // Obtener elementos del DOM
                const notificationList = document.getElementById('notification-list');
                const notificationCounter = document.getElementById('notification-counter');
                const noNotifications = document.getElementById('no-notifications');

                // Limpiar la lista de notificaciones existentes
                notificationList.innerHTML = '';


                if (notifications.length === 0) {
                    noNotifications.classList.remove('hidden');
                    notificationCounter.style.display = 'none'; // Oculta el contador
                } else {
                    noNotifications.classList.add('hidden');
                    notificationCounter.style.display = 'inline-block'; // Muestra el contador
                }

                notifications.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

                // Añadir notificaciones al panel
                notifications.forEach((notification) => {
                    const notificationItem = document.createElement('li');
                    notificationItem.className = 'notification-item';
                    notificationItem.dataset.id = notification.id;
                    // Verificar si la notificación contiene el atributo 'reminder'
                    if (notification.data.reminder) {
                        // Mostrar el reminder si está presente
                        notificationItem.classList.add('reminderPanel');
                    }
                    const notificationMessage = notification.data.reminder ?
                        `${notification.data.reminder}` // Mostrar el recordatorio
                        :
                        `<strong>${notification.data.assigned_by}</strong> te asignó la tarea:`;
                    notificationItem.innerHTML = ` 
                    <p class="notification-content">
                         ${notificationMessage} 
                        <a href="/tareas?task_id=${notification.data.task_id}" 
                        data-task-id="${notification.data.task_id}" 
                        class="notification-link" 
                        title="Ver tarea">
                            ${notification.data.task_title || 'Sin asunto'}
                        </a><br>de ${notification.data.client || 'Sin cliente'}<br><p style="color:#ababab;">${notification.data.description || ' '}</p>

                    </p>
                     <div class="notification-footer">
                        <span class="notification-date">${formatNotificationDate(notification.created_at)}</span>
                        <button class="mark-as-read-btn" data-id="${notification.id}" title="Marcar como leída">
                            Borrar
                        </button>
                    </div>
                `;

                    // Añadir la notificación al panel
                    notificationList.prepend(notificationItem);

                    // Actualizar el contador de notificaciones
                    const currentCount = parseInt(notificationCounter.textContent || 0);
                    updateNotificationCounter(currentCount + 1);

                    // Asegurarse de ocultar el mensaje "No tienes notificaciones pendientes"
                    if (!noNotifications.classList.contains('hidden')) {
                        noNotifications.classList.add('hidden');
                    }
                });
            })
            .catch((error) => {
                console.error('Error al cargar las notificaciones:', error);
            });


        // Handle clearing notifications
        clearNotifications.addEventListener('click', () => {
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            }).then(() => {
                notificationList.innerHTML = '';
                notificationCounter.textContent = '0';
                noNotifications.classList.remove('hidden');
            });
        });

        // Mark individual notification as read
        notificationList.addEventListener('click', (e) => {
            if (e.target.classList.contains('mark-as-read-btn')) {
                const notificationId = e.target.getAttribute('data-id');
                const notificationItem = e.target.closest('.notification-item'); // Encuentra el contenedor completo

                fetch(`/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                    .then(() => {
                        if (notificationItem) {
                            notificationItem.remove(); // Elimina toda la notificación
                        }

                        // Actualizar el contador
                        const remaining = notificationList.querySelectorAll('.notification-item').length;
                        notificationCounter.textContent = remaining;

                        // Mostrar "No tienes notificaciones pendientes" si no hay más
                        if (remaining === 0) {
                            noNotifications.classList.remove('hidden');
                        }
                    })
                    .catch((error) => {
                        console.error('Error al marcar la notificación como leída:', error);
                    });
            }
        });

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('notification-link')) {
                e.preventDefault(); // Evita la navegación predeterminada
                const taskId = e.target.dataset.taskId; // Obtén el ID de la tarea
                console.log('Tarea seleccionada con ID:', taskId);

                // Opcional: Redirigir manualmente
                window.location.href = `/tareas?task_id=${taskId}`;
            }
        });



        // Función para formatear la fecha de la notificación
        function formatNotificationDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();

            // Diferencias en milisegundos
            const msInDay = 24 * 60 * 60 * 1000;

            // Calcular diferencia de días
            const daysDiff = Math.floor((now - date) / msInDay);

            // Verificar si la fecha es de hoy
            if (
                date.getDate() === now.getDate() &&
                date.getMonth() === now.getMonth() &&
                date.getFullYear() === now.getFullYear()
            ) {
                return `${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
            }

            // Verificar si la fecha es de ayer
            if (daysDiff === 1) {
                return `Ayer ${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
            }

            // Verificar si la fecha es de esta semana
            const dayOfWeek = date.getDay();
            const weekStart = new Date(now);
            weekStart.setDate(now.getDate() - now.getDay() + 1); // Comienzo de la semana (lunes)

            if (date >= weekStart) {
                const daysOfWeek = ["Dom.", "Lun.", "Mar.", "Mié.", "Jue.", "Vie.", "Sáb."];
                return `${daysOfWeek[dayOfWeek]} ${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
            }

            // Para fechas anteriores a esta semana, mostrar fecha completa
            return date.toLocaleDateString([], {
                day: '2-digit',
                month: 'short', // Mes abreviado (e.g., "Nov.")
                year: 'numeric'
            });
        }

    });
</script>

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
        gap: 0.7rem;
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

    .sidebarOptions {
        display: flex;
        justify-content: space-between;
    }

    .sidebarOptionsButtons {
        display: flex;
        justify-content: center;
    }

    /* Notification Toggle Button */
    .notification-toggle {
        position: relative;
    }

    .notification-btn {
        background: none;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        color: #FFFFFF;
        font-size: 1rem;
        justify-content: center;
        height: 49px;

    }

    .notification-btn svg {
        width: 24px;
        height: 24px;
        transition: transform 0.3s ease;
    }

    .notification-btn span {
        background-color: #FF3E3E;
        color: #FFFFFF;
        border-radius: 50%;
        padding: 2px 5px;
        font-size: 0.60rem;
        transform: translate(-60%, -40%);
        line-height: 1;
        display: inline-block;
        min-width: 10px;
        text-align: center;
        z-index: 1001;
    }

    .notification-panel {
        position: absolute;
        bottom: 100%;
        /* Aparece justo encima del botón */
        left: 110px;
        /* Centrar el panel respecto al botón */
        transform: translate(-50%, 20px);
        /* Posición inicial más abajo */
        background-color: #2A2A2A;
        border-radius: 8px;
        padding: 15px;
        max-width: 220px;
        width: 220px;
        box-sizing: border-box;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        opacity: 0;
        /* Invisible inicialmente */
        z-index: 1000;
        /* Asegura que esté encima de otros elementos */
        pointer-events: none;
        /* Deshabilita interacciones cuando está oculto */
    }

    /* Keyframes para la animación de aparición */
    @keyframes slideIn {
        0% {
            transform: translate(-50%, 20px);
            /* Comienza más abajo */
            opacity: 0;
            /* Invisible */
        }

        100% {
            transform: translate(-50%, 0);
            /* Posición final */
            opacity: 1;
            /* Totalmente visible */
        }
    }

    @keyframes slideOut {
        0% {
            transform: translate(-50%, 0);
            /* Posición actual */
            opacity: 1;
            /* Visible */
        }

        100% {
            transform: translate(-50%, 20px);
            /* Se desliza hacia abajo */
            opacity: 0;
            /* Invisible */
        }
    }

    /* Clase activa: animación de entrada */
    .notification-panel.active {
        animation: slideIn 0.3s ease-out forwards;
        /* Aplicamos la animación */
        pointer-events: auto;
        /* Permitir interacciones */
    }

    /* Clase oculta: animación de salida */
    .notification-panel.hidden {
        animation: slideOut 0.3s ease-in forwards;
        /* Aplicamos la animación */
        pointer-events: none;
        /* Prevenir interacciones */
    }



    /* Lista de notificaciones */
    .notification-list {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 200px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #4A4A4A #2A2A2A;
    }

    .notification-list::-webkit-scrollbar {
        width: 8px;
    }

    .notification-list::-webkit-scrollbar-thumb {
        background: #4A4A4A;
        border-radius: 4px;
    }

    /* Estilo adicional para las notificaciones */
    .notification-item {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background-color: #333333;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 8px;
        color: #FFFFFF;
        transition: background-color 0.3s ease, transform 0.3s ease;
        font-size: 0.85rem;
        /* Tamaño reducido para adaptarlo al ancho */
        line-height: 1.4;
        /* Espaciado más compacto */
    }

    .notification-item:hover {
        background-color: #444444;
        transform: translateY(-2px);
    }

    /* Párrafo de la notificación */
    .notification-item p {
        margin: 0;
        white-space: normal;
        /* Permitir que el texto haga salto de línea */
        overflow: hidden;
        text-overflow: ellipsis;
        /* Añadir puntos suspensivos si es necesario */
        word-break: break-word;
        /* Cortar palabras largas si exceden el ancho */
    }

    /* Botón de acción para marcar como leído */
    .notification-item button {
        align-self: flex-start;
        /* Alinear a la izquierda */
        background: none;
        border: none;
        color: #AAAAAA;
        font-size: 0.8rem;
        cursor: pointer;
        margin-top: 5px;
        transition: color 0.3s ease;
    }

    .notification-item button:hover {
        color: #FFFFFF;
    }

    .notification-link {
        color: cyan;
    }

    /* Empty State for Notifications */
    .no-notifications {
        text-align: center;
        color: #AAAAAA;
        font-size: 0.9rem;
    }

    /* Clear Notifications Button */
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .notification-header h3 {
        font-size: 1rem;
        color: #FFFFFF;
        margin: 0;
    }

    .notification-header button {
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
    }

    .notification-header button .icon-clear {
        width: 20px;
        height: 20px;
        stroke: #CCCCCC;
        transition: stroke 0.3s ease;
    }

    .notification-header button:hover .icon-clear {
        stroke: #FFFFFF;
    }

    .notification-footer {
        display: flex;
        justify-content: space-between;
        /* Alinea los elementos a los extremos */
        align-items: center;
        /* Alinea los elementos en la parte inferior */

    }

    .mark-as-read-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #2563eb;
        /* Azul sutil */
        padding: 0px;
        font-size: 12px;
        transition: color 0.3s ease, background-color 0.3s ease;
    }


    .mark-as-read-btn:hover {
        color: #ffffff;
    }

    .icon-clear {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        stroke-width: 2;
    }

    .notification-date {
        display: block;
        font-size: 0.85rem;
        color: #6b7280;
        /* Gris claro */
        margin-top: 5px;
        padding: 0px;
        text-align: left;

    }

    .notification-content {
        font-size: 13px;
    }



    .notification-stack {
        position: fixed;
        bottom: 22vh;
        left: 20px;
        overflow-y: hidden;
        /* Oculta la barra de desplazamiento visible */
        height: 30vh;
        max-height: 30vh;
        display: flex;
        flex-direction: column;
        gap: 10px;
        /* Espaciado entre notificaciones */
        z-index: 9999;
        flex-direction: column-reverse;
        /* Invertir el orden */
    }

    .notification-stack.with-gradient {
        /* Efecto de difuminado en el borde superior */
        mask-image: linear-gradient(to bottom, transparent, black 10%);
        -webkit-mask-image: linear-gradient(to bottom, transparent, black 10%);
        mask-composite: exclude;
        -webkit-mask-composite: destination-in;
    }

    /* Estilo adicional para forzar scroll funcional pero invisible */
    .notification-stack::-webkit-scrollbar {
        display: none;
        /* Oculta el scroll en navegadores basados en Webkit */
    }

    .notification-stack {
        scrollbar-width: none;
        /* Oculta el scroll en Firefox */
    }

    .floating-notification {
        background-color: #333333;
        color: #FFFFFF;
        padding: 12px 18px;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
        font-size: 14px;
        font-weight: 500;
        line-height: 1.5;
        max-width: 280px;
        z-index: 1000;
        opacity: 1;
        transform: translateX(-100%);
        /* Fuera de la pantalla a la izquierda */
        /* Fuera de pantalla hacia la izquierda */
        transition: transform 0.5s ease-out, opacity 0.5s ease-out;
        width: 200px;
    }

    .floating-notification.reminder {
        border: 2px solid yellow;
    }

    .floating-notification.assigned {
        border: 2px solid #2563eb;
    }

    /* Notificación visible (aparece progresivamente desde la izquierda) */
    .floating-notification.show {
        opacity: 1;
        /* Totalmente visible */
        transform: translateX(0);
        /* Posición final */
    }

    /* Animación de salida */
    .floating-notification.fade-out {
        opacity: 0;
        transform: translateX(-100%);
        /* Se mueve hacia la izquierda para desaparecer */
        /* Se desliza hacia abajo */
    }



    .reminderPanel {
        border: 0.1px solid yellow !important;
    }




    .floating-notification a {
        color: #93C5FD;
        /* Azul claro profesional */
        text-decoration: none;
        /* Quitar subrayado */
        font-weight: 500;
        /* Peso medio para enlace */
    }

    .floating-notification a:hover {
        color: #FFFFFF;
        /* Cambiar a blanco al pasar el cursor */
        text-decoration: underline;
        /* Subrayado para accesibilidad */
    }




    .options {
        justify-content: space-between;
    }

    /* Botón de opciones */
    .admin-button {
        display: flex;
        align-items: center;
        justify-content: center;
        /* Centra el contenido */
        gap: 8px;
        padding: 8px 13px;
        font-size: 14px;
        font-weight: 500;
        color: white;
        /* Gris oscuro */
        background-color: rgba(229, 231, 235, 0.1);
        /* Color más claro con transparencia */
        border: 1px solid rgba(209, 213, 219, 0.7);
        /* Gris claro con transparencia */
        border-radius: 6px;
        text-decoration: none;
        transition: background-color 0.3s ease, box-shadow 0.2s ease, transform 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        /* Sombra más sutil */
        margin-bottom: 10px;

        width: 70px;
        cursor: pointer;
        /* Indica que es interactivo */
    }

    .admin-button:hover {
        background-color: rgba(229, 231, 235, 0.3);
        /* Fondo ligeramente más oscuro al pasar el ratón */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        /* Incrementa la sombra */
    }

    .admin-button:active {
        background-color: rgba(229, 231, 235, 0.5);
        /* Fondo más oscuro al hacer clic */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Reduce la sombra */
        transform: scale(0.95);
        /* Hace el botón ligeramente más pequeño */
    }

    .admin-button .icon-admin {
        width: 20px;
        height: 20px;
        stroke: #6b7280;
        transition: stroke 0.3s ease;
    }

    .admin-button:hover .icon-admin {
        stroke: #374151;
    }

    .option-item.date-display {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        /* Espaciado entre el ícono y el texto */
        padding: 10px 15px;
        border-radius: 8px;
        border: 1px solid #374151;
        color: #374151;
        font-size: 0.9rem;
        text-align: center;
        width: 100px;
        left: 55%;
        position: relative;
        /* Asegurar que no se desborde */
        box-sizing: border-box;
        transition: background-color 0.3s ease;
        margin-bottom: 10px;

    }

    .option-item.date-display:hover {
        background-color: #444444;
    }



    .option-item.date-display span {
        font-weight: 500;
        color: #FFFFFF;
    }


    /* tareas por Usuario */

    .user-tasks-container {
        border-radius: 4px;
        position: fixed;
        overflow: auto;
        display: flex;
        /* Añadido para centrar el grid */
        justify-content: center;
        /* Centra horizontalmente el grid */
        bottom: 23%;
        left: 1%;
        max-width: 500px;
    }


    .user-tasks-grid {
        display: grid;
        /* Usa grid para la disposición */
        grid-template-columns: repeat(3, 1fr);
        /* Define dos columnas iguales */
        grid-gap: 5px;
        /* Espaciado entre los elementos */
        max-height: 180px;
        /* Altura máxima del contenedor */
        max-width: 220px;
        /* Ancho máximo del contenedor */
        overflow-y: auto;
        /* Permite desplazamiento vertical si el contenido excede la altura */
        justify-content: center;
        /* Centra las columnas horizontalmente */
        align-content: start;
        /* Alinea los elementos al inicio vertical */
        padding: 5px;

    }

    .user-task-link {
        display: flex;
        background-color: #4A4A4A;
        justify-content: center;
        align-items: center;
        padding: 3px 4px;
        border: 1px solid #ccc;
        border-radius: 6px;
        text-decoration: none;
        color: #fff;
        font-size: 14px;
        text-align: center;
        transition: all 0.2s ease-in-out;
    }

    .user-task-link:hover {
        background-color: #333333;
        color: #FFFFFF;
    }

    .user-task-link.active {
        background-color: #333333;
        color: #fff;
        font-weight: bold;
    }

    /* ChatGPT */
    /* Estilos generales del chat */
    .chat-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 400px;
        height: 500px;
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        animation: slideUp 0.3s ease-in-out;
        z-index: 3000 ;
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Encabezado del chat */
    .chat-header {
        background-color: #2563eb;
        color: #ffffff;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: "Inter", sans-serif;
        font-weight: bold;
        font-size: 16px;
    }

    .close-chat-btn {
        background: none;
        border: none;
        color: #ffffff;
        font-size: 20px;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close-chat-btn:hover {
        color: #f87171;
    }

    /* Mensajes del chat */
    .chat-messages {
        flex-grow: 1;
        padding: 15px;
        overflow-y: auto;
        background-color: #f9fafb;
        font-family: "Inter", sans-serif;
        font-size: 14px;
        color: #374151;
    }

    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background-color: #9ca3af;
        border-radius: 4px;
    }

    .chat-messages::-webkit-scrollbar-thumb:hover {
        background-color: #6b7280;
    }

    .user-message,
    .ai-response {
        margin-bottom: 10px;
        padding: 10px 15px;
        border-radius: 8px;
        max-width: 75%;
        word-wrap: break-word;
    }

    .user-message {
        background-color: #dbeafe;
        color: #1e40af;
        align-self: flex-end;
    }

    .ai-response {
        background-color: #e5e7eb;
        color: #374151;
        align-self: flex-start;
    }

    /* Input y botón de envío */
    .chat-input-container {
        display: flex;
        padding: 10px;
        background-color: #f3f4f6;
        border-top: 1px solid #d1d5db;
    }

    .chat-input {
        flex-grow: 1;
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-family: "Inter", sans-serif;
        font-size: 14px;
        color: #374151;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .chat-input:focus {
        border-color: #2563eb;
    }

    .send-message-btn {
        margin-left: 10px;
        padding: 10px 20px;
        background-color: #2563eb;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-family: "Inter", sans-serif;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .send-message-btn:hover {
        background-color: #1d4ed8;
    }





    /* Animación para deslizarse desde la izquierda */
    @keyframes slideInFromLeft {
        0% {
            left: -100%;
            opacity: 0;
        }

        100% {
            left: 50%;
            /* Centrarse horizontalmente respecto al contenedor */
            transform: translateX(-60%);
            opacity: 1;
            /* Visible */
        }
    }

    /* Animación para desvanecerse */
    @keyframes fadeOut {
        0% {
            opacity: 1;
        }

        100% {
            opacity: 0;
        }
    }
</style>