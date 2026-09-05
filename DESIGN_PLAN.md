# Auberge de Founex — Design refresh plan

Status: plan approved for execution. Work through the phases in order; each phase ends with a build and a visual check before the next starts.

## 1. Brief

- Refresh the guest-facing site of **Auberge de Founex** (restaurant, bar and hotel, Grand'Rue 31, 1297 Founex, Vaud, between Geneva and Nyon on La Côte).
- Palette: **orange and black**. Layout: **sleek and modern**. Pages must not feel crowded, but every page must feel like the same site.
- Inspiration keyword: *auberge* — an inn. Warmth, hearth, wood, a table waiting for you, a room upstairs. Modern reading of that: dark warm surfaces, one saturated ember orange used as a material rather than a highlight, big serif headlines, generous air, photography doing the talking.
- Scope: guest pages only (`/`, `/la-carte`, `/restaurant`, `/hotel`, `/contact`, FR + EN). Admin pages, database schema and controllers stay as they are, except for small view-data additions listed below.

## 2. Current state (what we are replacing)

Stack: Laravel 13, Blade, Bootstrap 5.3 (SCSS), Alpine 3, jQuery, Glide carousel, PhotoSwipe, Vite 5. Fonts Inter + Playfair Display are already self-hosted (`resources/sass/_bootstrap.scss`).

Problems the refresh must fix:

- **Palette drifts.** Primary is a pale peach (`#efa765`), black is purple-tinted (`#1d1922`), the logo is Bootstrap `$yellow`, carousel arrows and tabs use `$blue`, links underline in `$info` blue, menu titles use `$orange-200`, secondary is violet. Nothing reads as "orange and black".
- **Every page is a parallax stack.** `x-page` wraps each page in a 100vh (or 75vh) `background-attachment: fixed` hero, then a second 600px fixed-attachment image before the footer, and the menu page adds one more per menu section. Fixed attachment is ignored on iOS, the images are inline `background-image` (bad LCP), and the pages feel heavy and repetitive.
- **Motion is scattered.** Every `h1` blurs and scales in via an IntersectionObserver that re-fires on every scroll in and out; the nav background is animated by a jQuery scroll handler. No `prefers-reduced-motion` handling.
- **Chrome is noisy.** Admin login button in the guest nav, flag PNGs for the locale switch, two-line "Auberge De / Founex" logo in yellow, a 512px clipart icon inside the contact form, `text-transform: capitalize` on French menu descriptions.
- **Missing basics for an inn.** No opening hours anywhere, no social links outside the contact page, no copyright line, contact form has no success message (`ContactController@store` redirects with nothing to display).
- **Untranslated copy.** Contact form intro sentence is hardcoded French.
- **Repo hygiene.** 22 compiled `.css`/`.css.map` files are tracked under `resources/sass/` (including a broken `app.css` that only contains a Sass error). Remove and ignore them.

## 3. Design system

### 3.1 Colour tokens

Warm black, one ember orange. The orange is a surface as well as an accent: it gets a full-bleed band and rules, not only button fills.

| Token | Hex | Role |
|---|---|---|
| `$ink` | `#141210` | Page background. Warm black (soot, not blue-black). |
| `$ink-2` | `#1E1B18` | Raised surfaces: nav when scrolled, form panel, menu section cards. |
| `$line` | `#2E2A25` | Hairlines and dividers. |
| `$ember` | `#F26B1D` | Brand orange. Buttons, rules, active nav, the "visit" band. |
| `$ember-hi` | `#FF8A3D` | Hover / focus ring. |
| `$ember-lo` | `#B84A0E` | Pressed state, small text on `$ink` where `$ember` is too loud. |
| `$paper` | `#F3EDE4` | Primary text on dark. Warm off-white. |
| `$paper-2` | `#A39B90` | Secondary text, descriptions, prices. |

Contrast checks (WCAG): `$ember` on `$ink` ≈ 6.1:1 (AA for all text). `$paper` on `$ink` ≈ 15:1. `$ink` on `$ember` ≈ 6.1:1, so **text on orange surfaces is black, never white** (white on `$ember` is only ≈ 3:1).

Bootstrap mapping in `_bootstrap.scss`: `$primary: $ember`, `$dark: $ink`, `$light: $ink-2`, `$gray: $paper-2`, `$body-bg: $ink`, `$body-color: $paper`, `$link-color: $ember`, `$link-hover-color: $ember-hi`, `$border-color: $line`, `$border-radius: 4px` (buttons, inputs), `$border-radius-lg: 8px` (panels, tiles). Delete `$secondary` violet and `$info` blue from the theme map after confirming the admin SCSS does not use them (`grep -rn '\$secondary\|\$info' resources/sass`). Replace the four stray uses of `$blue`, `$yellow`, `$orange-200`, `$info` with tokens.

### 3.2 Typography

Keep the two self-hosted faces; change how they are used.

- **Playfair Display** — headlines only (`h1`, `h2`, menu item names, the wordmark). Weight 400 for display sizes, 600 for small headings. Tight tracking (`-0.01em`) at display sizes.
- **Inter** — everything else. `font-feature-settings: "tnum"` on prices.

Scale (fluid, one place in `_tokens.scss`):

| Step | Size | Use |
|---|---|---|
| display | `clamp(2.75rem, 6vw, 5.5rem)` / 1.02 | Page `h1` |
| h2 | `clamp(1.875rem, 3vw, 2.75rem)` / 1.1 | Section titles |
| h3 | `1.375rem` / 1.25 | Menu item, footer headings |
| lead | `1.25rem` / 1.55 | Page intro paragraph |
| body | `1.0625rem` / 1.6 | Everything |
| small | `0.875rem` / 1.5 | Nav, meta, prices' currency |

Rules: headlines left-aligned (centered only in the home hero), sentence case everywhere (no `text-transform: uppercase` on nav or section titles, no `capitalize` on content), prose measure capped at `68ch`, no single-word colour accents inside headlines, no all-caps eyebrow labels.

### 3.3 Layout

- One container: `max-width: 1200px`, side padding `clamp(1.25rem, 4vw, 3rem)`.
- Vertical rhythm: sections separated by `--section-gap: clamp(4rem, 9vw, 7.5rem)`. Nothing else sets top/bottom margins on sections, which removes the current padding fights between `.app-section`, `.home-link`, `.app-page__description`.
- Grid: 12 columns on desktop via Bootstrap `.row`; most content uses 2 columns (text 5 / image 7, or 7 / 5) or 3 tiles. Never more than 3 items in a row.
- Hero: **one** image per page, `<img>` with `object-fit: cover` (not a background), height `72vh` on home, `52vh` on other pages, `min-height: 420px`. Dark gradient from bottom only (`linear-gradient(to top, $ink 0%, rgba($ink,.35) 55%, transparent)`), lockup sits in the lower-left of the container. No parallax, no fixed attachment, no second image before the footer.
- Corners: `4px` on controls, `8px` on image tiles and panels. No shadows on dark; separation comes from `$ink-2` surfaces and `$line` hairlines.

### 3.4 Components

- **Button primary**: `$ember` fill, `$ink` text, Inter 600, padding `.9rem 1.5rem`, radius 4px. Hover `$ember-hi`. Focus: 2px `$ember-hi` ring offset 2px.
- **Button secondary**: transparent, 1px `$paper` border, `$paper` text. Hover: border and text `$ember`.
- **Rule**: a 3px × 56px `$ember` bar under `h2` (single use of decoration; it is the brand mark on inner pages).
- **Image tile**: 4:3, radius 8px, title in Playfair overlaid bottom-left over a bottom gradient; hover lifts the gradient opacity, no scale transform.
- **Visit band**: full-bleed `$ember` background, `$ink` text; three columns: address + map link, hours, phone/email. This band appears once per page above the footer and replaces the current parallax footer image.
- **Inputs**: `$ink-2` fill, 1px `$line` border, `$paper` text, focus border `$ember` (no glow). Keep Bootstrap floating labels; labels `$paper-2`.
- **Nav**: fixed, 72px tall, transparent over the hero, `$ink-2` at 92% opacity once the hero is scrolled past (IntersectionObserver on a sentinel, no jQuery). Left: wordmark "Auberge de Founex" in Playfair `$paper`, one line. Right: 5 links in Inter small, active link has a 2px `$ember` underline. Far right: text locale toggle `FR` / `EN`. Mobile: hamburger opens a full-height `$ink` panel with links at `h2` size.

### 3.5 Motion

- One page-load moment: hero lockup fades and rises 12px over 600ms. Nothing else animates on scroll. Delete `.animation-grow` and the observer in `resources/js/app.js`.
- Transitions on hover/focus only (`150ms`, colour and border only).
- All motion inside `@media (prefers-reduced-motion: no-preference)`.

## 4. Page by page

### 4.1 Home `/`

```
┌────────────────────────────────────────────────────────────┐
│ Auberge de Founex          Accueil  La carte  Restaurant … │  nav (transparent)
│                                                            │
│   [hero photo, 72vh]                                       │
│                                                            │
│   Auberge de Founex                    (Playfair display)  │
│   {page.locale.title used as tagline, lead size}           │
│   [Réserver une table]  [Voir la carte]                    │
└────────────────────────────────────────────────────────────┘
│                                                            │
│  {page.locale.content, 68ch}       │ [afx-building.png]    │  intro, 5/7 split
│                                                            │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐                    │
│  │Restaurant│ │ La carte │ │  Hôtel   │   3 image tiles    │  "doors"
│  └──────────┘ └──────────┘ └──────────┘                    │
│                                                            │
│  Délicatesses  ▬                                           │
│  [img][img][img][img] →   horizontal scroll-snap strip     │
│                                                            │
│ ▓▓▓ Visit band (orange): address · hours · phone ▓▓▓       │
│ footer                                                     │
```

- Hero: `h1` becomes the wordmark "Auberge de Founex"; the DB title moves to the lead line under it. Two buttons: WhatsApp reservation (primary) and menu (secondary).
- Intro: text left, `afx-building.png` right (already in `resources/images`).
- "Doors": three tiles linking to Restaurant, La carte, Hôtel using each page's own `image`. `GuestController@index` passes the three `Page` models (one extra query with `whereIn`).
- Délicatesses: replace Glide with a CSS `scroll-snap-type: x mandatory` strip of 4:3 tiles, `overflow-x: auto`, two small prev/next buttons that call `scrollBy`. Removes `@glidejs/glide` from the guest bundle.
- Delete the two "home-link" icon boxes (the doors replace them).

### 4.2 La carte `/la-carte`

```
│ [hero 52vh]  La carte                                       │
│ {intro 68ch}                                                │
│ Entrées   Plats   Desserts   Boissons       sticky anchors  │
│ ─────────────────────────────────────────────────────────── │
│ Entrées ▬                                                   │
│  Tartare de bœuf ............................. CHF 24.00    │
│  description in $paper-2                                    │
│  Salade de chèvre chaud ...................... CHF 18.00    │
│ Plats ▬                                                     │
```

- Remove the per-section parallax banner and `afx-menu.jpg`.
- Sticky section nav under the hero (`position: sticky; top: 72px`), anchor links `#section-{id}`, active link `$ember`.
- Items: single column at `max-width: 760px`, name (Playfair h3) left and price (Inter tnum) right on one line, description under the name. Hairline `$line` between items. No `capitalize`. Currency in small `$paper-2`, amount in `$paper`.
- Empty description renders nothing (no empty `<p>`).

### 4.3 Restaurant `/restaurant` and Hôtel `/hotel`

- Same hero and intro as the menu page.
- Gallery: replace `column-count` masonry with a CSS grid, 3 columns desktop / 2 tablet / 1 mobile, tiles 4:3 with `object-fit: cover`, `loading="lazy"`, `width`/`height` attributes from `naturalWidth` no longer needed because PhotoSwipe reads `data-pswp-width/height` — set them server-side if dimensions are stored, otherwise keep the small Alpine `gallery()` init. Keep PhotoSwipe.
- Hotel hero keeps the WhatsApp booking button (primary). Restaurant hero gets "Réserver une table" too, same button, same target.

### 4.4 Contact `/contact`

```
│ [hero 52vh]  Contact                                        │
│ {intro 68ch}                                                │
│ Adresse / Téléphone / E-mail / Horaires / Réseaux  │ [form] │  5/7 split
│                                                    │        │
```

- Left column: address (map link), phone, email, opening hours, Facebook and Instagram as inline SVG icons (replace the PNGs). Left-aligned, hairlines between groups.
- Right column: form on `$ink-2` panel, radius 8px. Remove `contact.png`. Move the hardcoded French sentence to `resources/lang/{fr,en}/form.php` as `form.intro`. Button text "Envoyer le message" / "Send message".
- Add a success message: `ContactController@store` returns `redirect()->back()->with('status', __('form.sent'))`; the form renders the flash above the fields in `$ember` text.

### 4.5 Shared shell

- **Nav** per §3.4. Remove the login modal trigger from the guest nav; add a discreet "Admin" link in the footer that opens the existing modal. Authenticated admins keep the dropdown, restyled with the tokens.
- **Locale**: text toggle `FR / EN` replacing `french.png` / `english.png`; the active locale in `$paper`, the other in `$paper-2`. Delete the two PNGs.
- **Visit band** per §3.4 on every page. Address, phone, email are already known; opening hours go in `resources/lang/{fr,en}/footer.php` as `footer.hours` with a placeholder to confirm with the client (see §7).
- **Footer**: `$ink`, hairline top, four columns left-aligned: wordmark + one-line description, Pages, Contact, Social. Bottom row: `© {{ date('Y') }} Auberge de Founex` and the Admin link. Delete `afx-footer.jpg`.

## 5. Files

Create:
- `resources/sass/_tokens.scss` — colours, type scale, spacing, radii (imported first from `_bootstrap.scss`).
- `resources/views/components/hero.blade.php` — replaces the hero part of `page.blade.php` (props: `image`, `title`, `lead`, `size`, slot for buttons).
- `resources/views/components/visit-band.blade.php`.
- `resources/views/components/tile.blade.php` — image tile with title link.
- `resources/views/components/strip.blade.php` — scroll-snap gallery strip.
- `resources/views/components/icon/facebook.blade.php`, `icon/instagram.blade.php` — inline SVG.

Rewrite:
- `resources/sass/_bootstrap.scss` (variable mapping only), `app.scss`, `components/_navigation.scss`, `_page.scss`, `_footer.scss`, `_gallery.scss`, `_input.scss`, `pages/home.scss`, `pages/menu.scss`, `pages/contact.scss`.
- `resources/views/layouts/guest.blade.php` (skip link, sentinel for nav, remove inline Alpine carousel, `<meta name="description">` from page content, `<html lang>` already fine).
- `resources/views/components/navigation.blade.php`, `app/partials/navigation.blade.php`, `components/locale.blade.php`, `components/footer.blade.php`, `components/page.blade.php`, `components/gallery/index.blade.php`, `components/section.blade.php`.
- `resources/views/app/{home,menu,restaurant,hotel,contact}.blade.php`.
- `resources/js/app.js` — drop jQuery scroll handler, Glide, `animation()`; add nav sentinel observer, strip buttons, mobile nav (Alpine stays).
- `resources/lang/{fr,en}/{app,footer,form,nav}.php` — new strings: `app.reserve`, `app.see_menu`, `app.doors.*`, `footer.hours`, `footer.social`, `footer.rights`, `form.intro`, `form.sent`, `form.send`.

Touch (minimal):
- `app/Http/Controllers/GuestController.php@index` — pass `doors` (restaurant, menu, hotel pages).
- `app/Http/Controllers/ContactController.php@store` — flash `status`.
- `vite.config.js` — no change expected; confirm `sass` `@import` of `_tokens` works with `additionalData`.
- `.gitignore` — add `resources/sass/**/*.css` and `*.css.map`; `git rm --cached` the 22 tracked artifacts.
- `package.json` — remove `@glidejs/glide`, `jquery`, `@popperjs/core`? Only if `resources/views/layouts/admin.blade.php` no longer needs them (it references `$(`); check before removing. Bootstrap dropdown/modal JS needs Popper, so likely keep Popper, drop Glide, and decide on jQuery after the admin check.

Delete: `resources/images/{afx-footer.jpg, afx-menu.jpg, contact.png, french.png, english.png, facebook.png, instagram.png}`, `resources/sass/components/_carousel.scss`, `_animation.scss`, the tracked `.css` artifacts, `resources/fonts/YesevaOne-Regular.ttf`, `VarelaRound-Regular.ttf` (unused since the Inter/Playfair switch, confirm with grep).

## 6. Execution phases

Each phase: implement → `npm run build` → `php artisan serve` against the Docker Postgres (`docker compose up -d db`, `make db_reset` if needed) → screenshot `/`, `/la-carte`, `/restaurant`, `/hotel`, `/contact` at 390px, 768px and 1440px → fix → commit.

0. **Prep** — branch `design/orange-black` from `production`; untrack compiled CSS; add `.gitignore` rules; delete unused assets after grep confirms.
1. **Foundation** — `_tokens.scss`, Bootstrap variable mapping, base `body`/headings/links, buttons, inputs, focus styles, reduced-motion wrapper, container and section rhythm. Visual check: existing pages still render, now in the new palette.
2. **Shell** — nav (desktop + mobile + scroll state), locale toggle, footer, visit band, hero component, `page.blade.php` slimmed to hero + content + band. Remove parallax and page footer image. Visual check on all five pages.
3. **Home** — hero lockup + buttons, intro split, three doors, scroll-snap strip. Controller change. Remove Glide.
4. **Menu** — sticky section nav, item list, prices, remove section banners.
5. **Galleries** — grid for Restaurant and Hotel, PhotoSwipe kept, lazy images, hero CTA.
6. **Contact** — two-column layout, form panel, translated intro, flash message, SVG social icons.
7. **Polish** — `resources/js/app.js` cleanup, dependency removal, image `width`/`height`/`loading`/`fetchpriority`, skip link, `aria-expanded` on the mobile toggle, keyboard pass on nav/modal/gallery, iOS Safari check (no fixed attachment left), Lighthouse ≥ 90 on Performance and Accessibility for `/`.

Definition of done: all five guest pages in both locales use only the §3.1 tokens, no `background-attachment: fixed`, no uppercase/capitalize transforms on content, one animation on the site, admin pages unchanged and still functional (`/admin/home` renders, login modal still works from the footer link).

## 7. Open points to confirm with the client (do not block execution)

- Opening hours for the restaurant and reception (needed for the visit band and contact page). Ship with a clearly marked placeholder string in the lang files.
- Whether "Réserver une table" should go to WhatsApp for the restaurant as well as the hotel (assumed yes, same number +41 78 685 78 45).
- Hero photos: the current `pages.image` values are reused; if better landscape shots exist for the hero (min 2000px wide), swap them through the admin, no code change needed.

## 8. Sources consulted

- Auberge Resorts Collection, art and design ethos: https://auberge.com/art-design/
- Hotel Tech Report, hotel website design examples: https://hoteltechreport.com/news/hotel-website-designs
- Awwwards, hotel and restaurant websites: https://www.awwwards.com/websites/hotel-restaurant/
- Amenitiz, hotel website designs to draw from: https://amenitiz.com/fr/blog/les-plus-beaux-designs-de-site-web-pour-hotel-creez-une-experience-unique-pour-vos-visiteurs
- Snoweb, modern minimal site examples: https://www.snoweb.io/fr/site-internet/exemple/
- Wikipedia, "Auberge": https://en.wikipedia.org/wiki/Auberge
