import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'local',  // Esto debe coincidir con tu archivo .env
    wsHost: window.location.hostname,  // Usar el nombre del host actual
    wsPort: 6001,  // Puerto por defecto de WebSockets en Laravel Websockets
    wssPort: 6001, 
    forceTLS: true,  // Forzar la conexión TLS, lo que usará wss://
    disableStats: true,
    encrypted: true,  // Encriptación activada, útil para Websockets seguros (wss://)
    cluster: 'mt1',
    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute("content"), // Incluye el token CSRF
    authEndpoint: "/broadcasting/auth", // Endpoint de autenticación de Laravel
    auth: {
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
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
