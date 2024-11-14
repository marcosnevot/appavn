import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'local',  // Esto debe coincidir con tu archivo .env
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    encrypted: false,  // No es necesario para local
    cluster: 'mt1',
    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute("content"), // Incluye el token CSRF
    authEndpoint: "/broadcasting/auth", // Este es el endpoint predeterminado de Laravel
    auth: {
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    },
});

window.Echo.connector.pusher.connection.bind('state_change', function (states) {
    console.log(states);
});

window.Echo.connector.pusher.connection.bind('disconnected', function () {
    console.log('WebSocket desconectado.');
});

window.Echo.connector.pusher.connection.bind('connected', function () {
    console.log('Successfully reconnected to WebSocket.');
});


