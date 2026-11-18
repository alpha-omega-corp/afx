import './bootstrap';

import * as Popper from '@popperjs/core';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import sort from '@alpinejs/sort';
import PhotoSwipeLightbox from '../../node_modules/photoswipe/dist/photoswipe-lightbox.esm.js';
import PhotoSwipe from '../../node_modules/photoswipe/src/js/photoswipe.js';
import { registerAdmin } from './admin';

import '../../node_modules/photoswipe/src/photoswipe.css';

// Popper backs Bootstrap's dropdown and tooltip positioning.
window.Popper = Popper;
window.Gallery = PhotoSwipeLightbox;

window.Alpine = Alpine;
Alpine.plugin(focus);
Alpine.plugin(sort);

// Admin components must be registered before Alpine boots; they are inert
// on guest pages, which never use them.
registerAdmin();

Alpine.start();

// --- Lightbox ------------------------------------------------
// Dimensions come from the markup, measured when the photograph was
// uploaded. Anything uploaded before that is measured here as a fallback,
// which is why this runs before the lightbox is initialised.
if (document.querySelector('#gallery')) {
    document.querySelectorAll('#gallery a:not([data-pswp-width])').forEach((link) => {
        const img = link.querySelector('img');
        if (!img) return;

        const set = () => {
            if (!img.naturalWidth) return;
            link.setAttribute('data-pswp-width', img.naturalWidth);
            link.setAttribute('data-pswp-height', img.naturalHeight);
        };

        img.complete ? set() : img.addEventListener('load', set, { once: true });
    });

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

// --- Scroll reveal -------------------------------------------
// The home cards arrive as they come into view. Content is visible by
// default; this only hides it once we know the observer will show it
// again, so a failed script or an old browser costs nobody the page.
(() => {
    const targets = document.querySelectorAll('[data-reveal]');
    if (!targets.length) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (!('IntersectionObserver' in window)) return;

    document.documentElement.classList.add('has-reveal');

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-in');
                observer.unobserve(entry.target);   // Arrives once, not on every pass.
            });
        },
        { rootMargin: '0px 0px -12% 0px', threshold: 0.15 },
    );

    targets.forEach((target) => observer.observe(target));
})();

// --- Hero parallax -------------------------------------------
// The hero photograph fills the first screen and scrolls away slower than
// the page: once the page passes the top of the hero, the picture slides
// down inside it by a fraction of the distance. Its top edge stays above
// the screen's top the whole way, so it needs no overscan and is never
// enlarged past its frame. Works only while the hero is on screen, once a
// frame at most; still for anyone who asked for less motion.
(() => {
    const layers = document.querySelectorAll('[data-parallax]');
    if (!layers.length) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (!('IntersectionObserver' in window)) return;

    const RATE = 0.4;   // the picture keeps 40% of the page's travel back
    const visible = new Set();
    let frame = null;

    const update = () => {
        frame = null;
        visible.forEach((el) => {
            // How far the page has scrolled past the top of the hero.
            const passed = Math.max(0, -el.getBoundingClientRect().top);
            el.style.setProperty('--parallax', `${(passed * RATE).toFixed(1)}px`);
        });
    };

    const schedule = () => { if (!frame) frame = requestAnimationFrame(update); };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            entry.isIntersecting ? visible.add(entry.target) : visible.delete(entry.target);
        });
        schedule();
    });

    layers.forEach((el) => {
        el.classList.add('is-parallax');
        observer.observe(el);
    });

    window.addEventListener('scroll', schedule, { passive: true });
    window.addEventListener('resize', schedule, { passive: true });
})();

// --- Bars above the navigation ------------------------------
// The closed banner and the top bar sit in the flow and scroll away; the
// fixed nav follows them up to the top. Their height is measured on resize
// only, never on scroll.
(() => {
    const bars = [...document.querySelectorAll('.site-banner, .top-bar')];
    if (!bars.length) return;

    const measure = () => bars.reduce((sum, bar) => sum + bar.offsetHeight, 0);
    let height = measure();

    const sync = () => {
        const visible = Math.max(0, height - window.scrollY);
        document.documentElement.style.setProperty('--banner-offset', `${visible}px`);
    };

    const remeasure = () => {
        height = measure();
        sync();
    };

    sync();
    window.addEventListener('scroll', sync, { passive: true });
    window.addEventListener('resize', remeasure, { passive: true });
})();

// --- Scroll-snap strip ---------------------------------------
document.querySelectorAll('[data-strip]').forEach((strip) => {
    const track = strip.querySelector('[data-strip-track]');
    const prev = strip.querySelector('[data-strip-prev]');
    const next = strip.querySelector('[data-strip-next]');
    if (!track || !prev || !next) return;

    // One photograph plus one gap, measured rather than assumed: the gap
    // is not the same number at every width.
    const step = () => {
        const items = track.querySelectorAll('.strip__item');
        if (items.length > 1) return items[1].offsetLeft - items[0].offsetLeft;
        return items[0]?.offsetWidth || track.clientWidth * 0.8;
    };

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

// --- Directions ----------------------------------------------
// The link ships with the Google Maps directions URL, which every browser
// can open and which Android hands to the Maps app. On an Apple device the
// equivalent Apple Maps URL opens Maps itself, so the href is swapped there
// — and only there, so a visitor without JS still gets a working link.
(() => {
    const links = document.querySelectorAll('[data-directions][data-apple]');
    if (!links.length) return;

    // iPadOS reports itself as a Mac, which is why the touch check is here.
    const apple = /iPhone|iPad|iPod/.test(navigator.userAgent)
        || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1)
        || /Mac/.test(navigator.userAgent);

    if (apple) links.forEach((link) => { link.href = link.dataset.apple; });
})();

// --- Menu section anchors ------------------------------------
// Highlights the section currently in view in the sticky menu nav.
(() => {
    const links = document.querySelectorAll('[data-menu-anchor]');
    if (!links.length) return;

    const track = document.querySelector('.menu-anchors__track');
    const still = window.matchMedia('(prefers-reduced-motion: reduce)');

    // The rail is wider than a phone. Its fades say which way there is more
    // to see, so they have to be cleared at whichever end has been reached.
    const edges = () => {
        if (!track) return;
        const max = track.scrollWidth - track.clientWidth;
        track.classList.toggle('is-start', track.scrollLeft <= 2);
        track.classList.toggle('is-end', track.scrollLeft >= max - 2);
    };

    if (track) {
        track.addEventListener('scroll', edges, { passive: true });
        window.addEventListener('resize', edges, { passive: true });
        edges();
    }

    const byId = new Map([...links].map((a) => [a.getAttribute('href').slice(1), a]));
    const sections = [...byId.keys()].map((id) => document.getElementById(id)).filter(Boolean);

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                links.forEach((a) => a.classList.remove('is-current'));

                const link = byId.get(entry.target.id);
                if (!link) return;

                link.classList.add('is-current');

                // On a phone the current section is usually off the end of the
                // rail; without this the highlight marks something the guest
                // cannot see. Scrolled by hand rather than with
                // scrollIntoView, which walks every scroll ancestor — touching
                // the document scroller here cancels the smooth jump that a
                // tap on one of these links just started.
                if (!track) return;

                const rail = track.getBoundingClientRect();
                const box = link.getBoundingClientRect();

                track.scrollBy({
                    left: (box.left + box.width / 2) - (rail.left + rail.width / 2),
                    behavior: still.matches ? 'auto' : 'smooth',
                });
            });
        },
        { rootMargin: '-40% 0px -55% 0px' },
    );

    sections.forEach((section) => observer.observe(section));
})();

import.meta.glob(['../images/**']);
