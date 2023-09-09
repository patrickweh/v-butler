import Echo from 'laravel-echo'

import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: document.head.querySelector('meta[name="pusher-key"]').content,
    cluster: document.head.querySelector('meta[name="pusher-cluster"]').content,
    wsHost: window.location.hostname, // <-- important if you dont build the js file on the prod server
    wsPort: 80, // <-- this ensures that nginx will receive the request
    wssPort: 443, // <-- this ensures that nginx will receive the request
    forceTLS: window.location.protocol === 'https:',
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});
