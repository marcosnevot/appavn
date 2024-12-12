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
                class="menu-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
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

            <a href="{{ route('calls.index') }}"
                class="menu-link {{ request()->routeIs('calls.index') ? 'active' : '' }}">
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

        </div>
    </div>

    <!-- Bottom Section (User and Logout) -->
    <div class="user-section">


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

        @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('admin.index') }}" class="option-item admin-button" title="Panel de Administración">

            <span>Admin</span>
        </a>
        @else
        <div class="option-item date-display" title="Fecha de hoy">

            <span>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
        </div>
        @endif




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
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const notificationList = document.getElementById('notification-list');
        const notificationCounter = document.getElementById('notification-counter');
        const noNotifications = document.getElementById('no-notifications');
        const clearNotifications = document.getElementById('clear-notifications');
        const floatingNotification = document.getElementById('floating-notification');

        const notificationToggle = document.getElementById('notification-toggle');
        const notificationPanel = document.getElementById('notification-panel');
        console.log(floatingNotification.classList);

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
                floatingNotification.textContent = `${notification.assigned_by} te asignó la tarea: ${notification.task_title || 'Sin asunto'}`;
                floatingNotification.classList.remove('hidden'); // Asegúrate de mostrarla
                floatingNotification.classList.add('show'); // Activa la animación

                // Ocultar después de 3 segundos
                setTimeout(() => {
                    floatingNotification.classList.remove('show');
                    setTimeout(() => {
                        floatingNotification.classList.add('hidden'); // Ocultar completamente
                    }, 500); // Tiempo suficiente para que termine la animación
                }, 3000);



                // Crear un nuevo elemento de notificación con la estructura HTML adecuada
                const notificationItem = document.createElement('li');
                notificationItem.className = 'notification-item';
                notificationItem.innerHTML = `
                                                <p class="notification-content">
                                                    <strong>${notification.assigned_by}</strong> te asignó la tarea: 
                                                            <a href="{{ route('tasks.index') }}" class="notification-link" title="Ver tarea">
                                                                                ${notification.task_title || 'Sin asunto'}
                                                             </a>                                               
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
                if (notificationCounter) {
                    notificationCounter.textContent = parseInt(notificationCounter.textContent || 0) + 1;
                }

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

                // Añadir notificaciones al panel
                notifications.forEach((notification) => {
                    const notificationItem = document.createElement('li');
                    notificationItem.className = 'notification-item';
                    notificationItem.dataset.id = notification.id;
                    notificationItem.innerHTML = `
                    <p class="notification-content">
                        <strong>${notification.data.assigned_by}</strong> te asignó la tarea: 
                        <a href="{{ route('tasks.index') }}" class="notification-link" title="Ver tarea">
                            ${notification.data.task_title || 'Sin asunto'}
                        </a>
                    </p>
                     <div class="notification-footer">
                        <span class="notification-date">${formatNotificationDate(notification.created_at)}</span>
                        <button class="mark-as-read-btn" data-id="${notification.id}" title="Marcar como leída">
                            Borrar
                        </button>
                    </div>
                `;

                    notificationList.appendChild(notificationItem);
                });

                // Actualizar el contador de notificaciones
                notificationCounter.textContent = notifications.length;
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
        bottom: -35px;
        position: absolute;

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
        padding: 2px 8px;
        font-size: 0.75rem;
        transform: translate(-40%, -60%);
        line-height: 1;
        display: inline-block;
        min-width: 20px;
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



    /* Notificación flotante */
    .floating-notification {
        position: absolute;
        top: -120px;
        /* Ajusta para que esté encima del botón */
        left: -100%;
        /* Comienza fuera de la pantalla a la izquierda */
        background-color: #333333;
        /* Azul oscuro profesional */
        color: #FFFFFF;
        /* Texto blanco */
        padding: 12px 18px;
        /* Más espacio para contenido */
        border-radius: 10px;
        /* Bordes más redondeados */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
        /* Sombra más marcada */
        font-size: 14px;
        font-weight: 500;
        /* Peso moderado para un texto profesional */
        line-height: 1.5;
        /* Mejor separación del texto */
        max-width: 280px;
        /* Limitar ancho */
        z-index: 1000;
        /* Asegura que esté encima de otros elementos */
        opacity: 0;
        /* Invisible inicialmente */
        pointer-events: none;
        /* No interactuable */
        transition: transform 0.5s ease-out, opacity 0.5s ease-out;
        /* Suavidad en la animación */
        border: 2px solid #2563eb;
        /* Borde azul para resaltar */
        width: 200px;

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

    /* Cuando se muestra la notificación flotante */
    .floating-notification.show {
        animation: slideInFromLeft 1s ease-out forwards, fadeOut 3s 2.5s ease-in forwards;
        /* Deslizarse y luego desvanecer */
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
        position: relative;
        left: 69%;
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