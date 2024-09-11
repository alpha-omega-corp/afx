import './bootstrap';

import Glide, {Autoplay, Breakpoints, Controls} from "@glidejs/glide/dist/glide.modular.esm.js";
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

window.$ = $;
window.Glide = Glide;
window.GlideControls = {Controls, Autoplay, Breakpoints};

window.Alpine = Alpine;
Alpine.plugin(focus);
Alpine.start();
