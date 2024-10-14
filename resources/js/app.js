import './bootstrap';

import * as Popper from '@popperjs/core'
import Glide, {Autoplay, Breakpoints, Controls} from "@glidejs/glide/dist/glide.modular.esm.js";
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import $ from "jquery";

import lightGallery from 'lightgallery';
window.lightGallery = lightGallery;

window.$ = $;
window.Popper = Popper;
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

$(window).ready(function () {
    $(window).scroll(function () {
        $(".app-navigation")
            .css("background-color", $(this).scrollTop() > 1 ? "rgba(0,0,0,0.6)" : "transparent")
            .css("transition", "0.5s");
    });
});

lightGallery(document.getElementById('lightgallery'), {
    speed: 500,
    thumbnail: true,
});


import.meta.glob([
    '../images/**',
]);
