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

// --- Hearth embers -------------------------------------------
// The home hero has no photograph, so firelight carries it. Motes rise
// from the bottom edge, drift, flicker and burn out before they reach the
// type. Additive blending is what makes them read as light rather than
// as dots. Sparse on purpose: this is a hearth, not weather.
(() => {
    const canvas = document.querySelector('[data-embers]');
    if (!canvas) return;

    const ctx = canvas.getContext('2d', { alpha: true });
    if (!ctx) return;

    const still = window.matchMedia('(prefers-reduced-motion: reduce)');
    const EMBER = [
        [242, 107, 29],  // $ember
        [255, 138, 61],  // $ember-hi
        [184, 74, 14],   // $ember-lo
    ];

    let width = 0;
    let height = 0;
    let motes = [];
    let frame = null;
    let onScreen = true;
    let last = 0;

    const rand = (min, max) => min + Math.random() * (max - min);

    // 0 at the boundary, 1 once `soft` pixels inside it. Applied to alpha so
    // the field has no edge: motes fade out rather than vanishing at a line.
    const falloff = (value, extent, soft) =>
        Math.max(0, Math.min(1, Math.min(value, extent - value) / soft));

    // `seeded` scatters the first field over the whole hero so the effect
    // is already running when the page paints, instead of climbing in.
    const mote = (seeded = false) => {
        const radius = rand(0.8, 3);

        return {
            x: rand(0, width),
            // A seeded field fills the lower half only: embers come off the
            // fire, so the top of the hero starts clear and stays that way.
            y: seeded ? rand(height * 0.45, height) : height + rand(0, 60),
            radius,
            // Larger motes read as nearer, so they climb faster.
            speed: 7 + radius * 9,
            drift: rand(-0.5, 0.5),
            sway: rand(16, 40),
            phase: rand(0, Math.PI * 2),
            flicker: rand(1.6, 3.4),
            life: seeded ? rand(0, 3) : 0,
            span: rand(6, 12),
            tint: EMBER[(Math.random() * EMBER.length) | 0],
        };
    };

    const resize = () => {
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        const rect = canvas.getBoundingClientRect();

        width = rect.width;
        height = rect.height;
        canvas.width = Math.round(width * dpr);
        canvas.height = Math.round(height * dpr);
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        const count = Math.round(Math.min(150, (width * height) / 8500));
        motes = Array.from({ length: count }, () => mote(true));
    };

    const paint = (elapsed) => {
        ctx.clearRect(0, 0, width, height);
        ctx.globalCompositeOperation = 'lighter';

        for (const m of motes) {
            const t = m.life / m.span;
            // In fast, out slow: an ember brightens as it leaves the fire
            // and dims for most of its climb.
            const fade = t < 0.12 ? t / 0.12 : 1 - (t - 0.12) / 0.88;
            const pulse = 0.72 + 0.28 * Math.sin(m.phase + elapsed * m.flicker);
            const x = m.x + Math.sin(m.phase + m.life * 0.9) * m.sway;

            // The field fades at the sides, and thins toward the top so the
            // motes dissolve into the light rather than crowding the wordmark.
            const edge = falloff(x, width, width * 0.16) * falloff(m.y, height * 2, height * 0.55);
            const alpha = Math.max(0, fade) * pulse * edge;

            if (alpha <= 0.002) continue;
            const [r, g, b] = m.tint;

            // A hot core inside a soft halo. Two stops do the work that a
            // blur filter would, without the per-frame cost.
            const reach = m.radius * 5.5;
            const glow = ctx.createRadialGradient(x, m.y, 0, x, m.y, reach);
            glow.addColorStop(0, `rgba(255, 226, 196, ${alpha})`);
            glow.addColorStop(0.22, `rgba(${r}, ${g}, ${b}, ${alpha * 0.85})`);
            glow.addColorStop(0.55, `rgba(${r}, ${g}, ${b}, ${alpha * 0.22})`);
            glow.addColorStop(1, `rgba(${r}, ${g}, ${b}, 0)`);

            ctx.fillStyle = glow;
            ctx.beginPath();
            ctx.arc(x, m.y, reach, 0, Math.PI * 2);
            ctx.fill();
        }

        ctx.globalCompositeOperation = 'source-over';
    };

    const step = (now) => {
        const delta = Math.min((now - last) / 1000, 0.05);
        last = now;

        for (let i = 0; i < motes.length; i += 1) {
            const m = motes[i];
            m.life += delta;
            m.y -= m.speed * delta;
            m.x += m.drift * delta * 30;

            if (m.life > m.span || m.y < -20) motes[i] = mote();
        }

        paint(now / 1000);
        frame = requestAnimationFrame(step);
    };

    const start = () => {
        if (frame !== null || still.matches) return;
        last = performance.now();
        frame = requestAnimationFrame(step);
    };

    const stop = () => {
        if (frame === null) return;
        cancelAnimationFrame(frame);
        frame = null;
    };

    const sync = () => {
        if (still.matches) {
            stop();
            resize();
            paint(0);   // A single still frame: the firelight without the motion.
            return;
        }

        onScreen && !document.hidden ? start() : stop();
    };

    resize();
    sync();

    // Nothing runs while the hero is scrolled away or the tab is in the
    // background.
    new IntersectionObserver(([entry]) => {
        onScreen = entry.isIntersecting;
        sync();
    }).observe(canvas);

    document.addEventListener('visibilitychange', sync);
    still.addEventListener('change', sync);

    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => { resize(); sync(); }, 150);
    }, { passive: true });
})();

// --- Closed banner offset ------------------------------------
// The banner sits in the flow and scrolls away; the fixed nav follows it
// down to the top. Its height is measured on resize only, never on scroll.
(() => {
    const banner = document.querySelector('.site-banner');
    if (!banner) return;

    let height = banner.offsetHeight;

    const sync = () => {
        const visible = Math.max(0, height - window.scrollY);
        document.documentElement.style.setProperty('--banner-offset', `${visible}px`);
    };

    const remeasure = () => {
        height = banner.offsetHeight;
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
