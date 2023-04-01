import Echo from 'laravel-echo'

import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster:import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname, // <-- important if you dont build the js file on the prod server
    forceTLS: false,
    wsPort: 80,
    wssPort: 443, // <-- this ensures that nginx will receive the request
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});
