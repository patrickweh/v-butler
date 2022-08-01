import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'
import * as Turbo from "@hotwired/turbo";
import 'livewire-turbolinks';
import './features/dark-mode'

Alpine.plugin(focus)

window.Alpine = Alpine;
Alpine.start();

export default Turbo;
