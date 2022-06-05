import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'
Alpine.plugin(focus)
window.Alpine = Alpine;
Alpine.start();

import 'livewire-turbolinks'

const Turbolinks = require("turbolinks");
Turbolinks.start()
