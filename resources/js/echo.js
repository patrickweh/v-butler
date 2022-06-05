import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: 443,
    wssPort: 443,
    enabledTransports: ['ws'],
    forceTLS: false,
    disableStats: true,
});
