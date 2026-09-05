import './bootstrap';

import * as Popper from '@popperjs/core';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import sort from '@alpinejs/sort';
import $ from 'jquery';
import PhotoSwipeLightbox from '../../node_modules/photoswipe/dist/photoswipe-lightbox.esm.js';
import PhotoSwipe from '../../node_modules/photoswipe/src/js/photoswipe.js';

import '../../node_modules/photoswipe/src/photoswipe.css';

// jQuery and Popper are still required by the admin layout and by
// Bootstrap's dropdown/modal JS.
window.$ = $;
window.Popper = Popper;
window.Gallery = PhotoSwipeLightbox;

window.Alpine = Alpine;
Alpine.plugin(focus);
Alpine.plugin(sort);
Alpine.start();

// --- Lightbox ------------------------------------------------
if (document.querySelector('#gallery')) {
    new PhotoSwipeLightbox({
        gallerySelector: '#gallery',
        children: 'a',
        pswpModule: PhotoSwipe,
    }).init();
}

// --- Navigation state ----------------------------------------
// The nav turns solid once the hero sentinel leaves the viewport.
// An observer replaces the old scroll handler, so nothing runs
// on every scroll frame.
(() => {
    const nav = document.querySelector('.app-navigation');
    const sentinel = document.querySelector('.nav-sentinel');
    if (!nav || !sentinel) return;

    new IntersectionObserver(
        ([entry]) => nav.classList.toggle('is-stuck', !entry.isIntersecting),
    ).observe(sentinel);
})();

// --- Scroll-snap strip ---------------------------------------
document.querySelectorAll('[data-strip]').forEach((strip) => {
    const track = strip.querySelector('[data-strip-track]');
    const prev = strip.querySelector('[data-strip-prev]');
    const next = strip.querySelector('[data-strip-next]');
    if (!track || !prev || !next) return;

    const step = () => track.querySelector('.strip__item')?.offsetWidth + 16 || track.clientWidth * 0.8;

    const sync = () => {
        const max = track.scrollWidth - track.clientWidth - 2;
        prev.disabled = track.scrollLeft <= 2;
        next.disabled = track.scrollLeft >= max;
    };

    prev.addEventListener('click', () => track.scrollBy({ left: -step() }));
    next.addEventListener('click', () => track.scrollBy({ left: step() }));
    track.addEventListener('scroll', sync, { passive: true });
    window.addEventListener('resize', sync, { passive: true });
    sync();
});

// --- Menu section anchors ------------------------------------
// Highlights the section currently in view in the sticky menu nav.
(() => {
    const links = document.querySelectorAll('[data-menu-anchor]');
    if (!links.length) return;

    const byId = new Map([...links].map((a) => [a.getAttribute('href').slice(1), a]));
    const sections = [...byId.keys()].map((id) => document.getElementById(id)).filter(Boolean);

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                links.forEach((a) => a.classList.remove('is-current'));
                byId.get(entry.target.id)?.classList.add('is-current');
            });
        },
        { rootMargin: '-40% 0px -55% 0px' },
    );

    sections.forEach((section) => observer.observe(section));
})();

import.meta.glob(['../images/**']);
