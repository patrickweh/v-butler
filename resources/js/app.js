import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'
import * as Turbo from "@hotwired/turbo";
import 'livewire-turbolinks';
import './features/dark-mode'
import {livewire_hot_reload} from 'virtual:livewire-hot-reload'

Alpine.plugin(focus)

window.Alpine = Alpine;
Alpine.start();

export default Turbo;

livewire_hot_reload();
