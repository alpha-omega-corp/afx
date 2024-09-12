import './bootstrap';

import Glide, {Autoplay, Breakpoints, Controls} from "@glidejs/glide/dist/glide.modular.esm.js";
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

window.Glide = Glide;
window.GlideControls = {Controls, Autoplay, Breakpoints};

window.Alpine = Alpine;
Alpine.plugin(focus);
Alpine.start();

animation('animation-grow');

// **
function animation(className) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add(`${className}-active`);
            } else {
                entry.target.classList.remove(`${className}-active`);
            }
        });
    });

    document.querySelectorAll(`.${className}`).forEach((element) => observer.observe(element));
}
