import Echo from 'laravel-echo'

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    enabledTransports: ['ws', 'wss'],
    forceTLS: false,
    disableStats: true,
});
