import './bootstrap';

import * as Popper from '@popperjs/core'
import Glide, {Autoplay, Breakpoints, Controls} from "@glidejs/glide/dist/glide.modular.esm.js";
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import $ from 'jquery';
import PhotoSwipeLightbox from '../../node_modules/photoswipe/dist/photoswipe-lightbox.esm.js';
import PhotoSwipe from '../../node_modules/photoswipe/src/js/photoswipe.js';

import '../../node_modules/photoswipe/src/photoswipe.css';

window.Gallery = PhotoSwipeLightbox
window.$ = $;
window.Popper = Popper;
window.Glide = Glide;
window.GlideControls = {Controls, Autoplay, Breakpoints};

window.Alpine = Alpine;
Alpine.plugin(focus);
Alpine.start();

const gallery = new PhotoSwipeLightbox({
    gallerySelector: '#gallery',
    children: 'a',
    pswpModule: PhotoSwipe,
});


gallery.init();


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

$(window).ready(function () {
    $(window).scroll(function () {
        $(".app-navigation")
            .css("background-color", $(this).scrollTop() > 1 ? "rgba(0,0,0,0.6)" : "transparent")
            .css("transition", "0.5s");
    });
});

import.meta.glob([
    '../images/**',
]);
