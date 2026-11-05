# Auberge de Founex — brief for the build pipeline

The current site (`alpha-omega-corp/afx`, live at aubergedefounex.ch), described as a site-brief
for a rewrite on the worker template. Sections 1–8 are the brief; the appendix after them is
not part of it, and lists what the rewrite drops and what has to be carried over from the live
site.

Sources: PRODUCT.md, DESIGN.md, IDEAS.md, routes/web.php, the models, seeders and factories,
the views and resources/lang. Where PRODUCT.md (5 Sept) and the code disagree, the code wins.

---

```text
You are building a website for Auberge de Founex:
the restaurant, bar and hotel of the village of Founex, on La Côte between Geneva and Nyon — a
village table and a few rooms upstairs.
It is a Laravel application from the organisation's worker template, run by deployer as a project.
Read the whole brief before writing code.

## 1. The application's shape (required by deployer — do not change)

The folder is pulled by mcp__deployer__create_laravel_app and registered by
mcp__deployer__create_project; never clone the template or run composer yourself. Deployer reads the
project off these, so they stay as the template has them:
- composer.json → its require.php decides the PHP pool. Never widen it.
- resources/pages.json → the site's pages: the routes, the navigation and the sitemap. A page is
  registered (plan-ui-site / build-ui-site, ui:page), never a Route::view or a hand-typed nav item.
- public/index.php → the document root is public/.

Deployment rules:
- No Dockerfile, and no compose service that serves the app: a compose file that serves the app
  turns the project into a docker one. compose.local.yml is the laptop's database, and deployer's —
  never edit it or start its container yourself.
- Services beside the site: none — the postgres deployer attaches is enough. Deployer attaches them
  from its chunk library; a console never writes their credentials.
- The .env on the server is the sysadmin's. Never commit a .env; .env.example carries the keys the
  site needs (APP_LOCALE, APP_FAKER_LOCALE, SEED_ADMIN_EMAIL, SEED_ADMIN_PASSWORD) with placeholder
  values. Deployer writes the database, the cache, APP_URL and reverb itself, and DB_PASSWORD once.
- The database is postgres, here and on the server. Every migration runs on postgres; a migration
  only sqlite accepts is a failed rollout.
- Long-running processes (none) are supervisor programs deployer writes. Do not ship a supervisor
  conf or a systemd unit.
- GET / answers 200: deployer's healthcheck asks it through nginx.
- The server checks the repository out: never commit vendor/, node_modules/, public/build, logs or
  large media (< 5 MB of committed pictures). Photographs of the business are the owner's, uploaded
  at /admin.

## 2. Product

Business type: Restaurant (with rooms — see the hotel page; there is no separate hotel type to add).
Audiences:
- Local and regional diners (Founex, La Côte, the Nyon–Geneva belt), mostly on a phone, often
  deciding within minutes: they should come away knowing what is on the carte, whether the
  auberge is open, and how to reserve.
- Overnight guests needing a room near Geneva: fewer, but the rooms are the claim that sets the
  house apart — simple, honest, affordable rooms minutes from Geneva airport and the lake. Most
  visitors arrive as diners; the rooms must never become a footnote.
- Staff, on a weekly rhythm: they keep the carte, the plat du jour, the photographs, the hours and
  the closures true from /admin. Fast for someone who has done it before, legible after a
  fortnight away.
Facts:
- Name: Auberge de Founex (written exactly so; the wordmark is the name in full, no logotype).
- Restaurant, bar and hotel. Grand'Rue 31, 1297 Founex, Vaud, Switzerland.
- Telephone 022 776 10 29 (+41 22 776 10 29). Email aubergedefounex@bluewin.ch.
- WhatsApp +41 78 685 78 45 (078 685 78 45) — reservations of tables and rooms.
- Facebook facebook.com/AubergeFounex · Instagram @auberge_de_founex.
- In the village centre, 500 m (five minutes on foot) from Lake Geneva; renovated July 2012.
- A dining room, a brasserie, a bar open between services, a shaded terrace onto the square and
  the garden.
- Rooms upstairs: free Wi-Fi in the common areas, free private parking, bus stop 20 m away,
  Geneva airport 15 km away.
- Known for: perch fillets from Lake Geneva, lamb shank confit in Founex Pinot Noir; perch "à
  volonté" on Wednesdays; cheese fondue served on the brasserie side only; game in season
  ("Spécialité de chasse", which the owner asked to see in large on the home page).
- Hours: lunch and dinner service by day of week, edited by staff (see §5). Take the current
  week from the live site's admin — not from the old seed, which says Tue–Sat 11h30–14h00 and
  18h00–22h00. Prices in CHF, two decimals, apostrophe thousands separator.
- Reservations happen off the site: telephone, WhatsApp, or walking in. The site takes no
  booking and holds no availability, inventory or rates.
Found for: restaurant, auberge, hôtel, Founex, La Côte, entre Genève et Nyon, district de Nyon.
(Demo data, not facts: the DemoSeeder carries the auberge's own carte as it stands in the current
MenuSeeder — nine sections, about 45 dishes with prices — which is real and may be shown; it may
also invent one plat du jour dated today, and one upcoming closure period with a reason. It
invents no hours, no rooms, no rates, no reviews.)
The one thing a visitor should do: book a table — the header's action, which opens WhatsApp on
+41 78 685 78 45 (https://wa.me/41786857845) and shows that number on the button; where the
header's action can only be a path, /contact.

## 3. Pages

Language: fr, for Switzerland. One language per site: every label, title, button and alt in it.
Built from the shared UI kit: theme sandstone (re-coloured in §6), direction carte, home page
layout poster (the names list-ui-themes, list-ui-layouts and the Build tab give).
Prefabs: menu on /la-carte (and the plat du jour on /), schedule on /contact (and in the footer),
map on /contact.

home — / — Accueil — Auberge de Founex — Auberge de Founex, restaurant, bar et chambres à Founex, sur La Côte entre Genève et Nyon. — full-screen photo hero, plat du jour, the chasse sign, three doors, welcome and photo strip
offer — /la-carte — La carte — La carte — La carte de l'Auberge de Founex, restaurant à Founex sur La Côte, entre Genève et Nyon. — the menu prefab, sections with an anchor rail
about — /restaurant — Restaurant — Le restaurant — Le restaurant de l'Auberge de Founex : salle, brasserie, bar et terrasse au cœur de Founex. — the page's words, the reserve action, the restaurant gallery
rooms — /hotel — Hôtel — Les chambres — Les chambres de l'Auberge de Founex, hôtel à Founex sur La Côte, entre Genève et Nyon. — the page's words, the practical facts, the reserve-a-room action, the hotel gallery
contact — /contact — Contact — Nous trouver — Adresse, téléphone et horaires de l'Auberge de Founex, Grand'Rue 31 à Founex. — the details, the schedule prefab, the map prefab

PRIORITY: build all five in full; the house's copy for each exists (appendix, "Copy to carry over").

### Accueil (/)
- Hero, the full screen: the home page's photograph (owner-uploaded, editable in /admin), the
  name, the lead and two actions straight on the scrim, lower left — "Réserver une table" (ember,
  with 078 685 78 45 on it) and "Voir la carte" (ghost, to /la-carte). When a plat du jour is
  served today, a chip above the name, "Plat du jour · <dish> (+n)", jumps to it. The hero closes
  on a hairline row of practical facts: the hours, the reservation numbers, the address. No
  photograph uploaded: the hero stands bare on the warm-black ground.
- "Spécialité de chasse": a hanging sign, large, the drawn stag (ember-line `tete-de-cerf`) and
  the words. Its antlers draw themselves in as it arrives; a warm light follows the pointer over
  it. Not a control — nothing to focus. Still without the script or under reduced motion.
- Plat du jour (see §5): the card with a covered dish drawing (`cloche-fumante`), the day ("Aujourd'hui"
  with a pulse when it is today, otherwise the date), and one or several dishes, each name, a
  leader line and its CHF price, the description under it. Empty or past: nothing for guests;
  staff see an empty-state line and the edit button.
- Three doors: Restaurant, La carte, Hôtel — tiles carrying each page's own hero photograph,
  linking to it. A tile with no photograph draws its own panel.
- "Bienvenue": the home page's body text, then a horizontal photo strip with arrows either side
  — the building photograph first (resources/images/afx-building.jpg, the one real photo in the
  repo), then the home gallery. No gallery photos: the building alone, no arrows.
- JSON-LD Restaurant: name, url, telephone, email, address, sameAs (both socials) and
  openingHoursSpecification read from the hours table.

### La carte (/la-carte)
- Hero, the quieter inner one: the page's photograph, the name with the ember rule, the page's
  words, and one action "Voir la carte ↓" anchored to the section rail.
- A sticky rail of the section names (anchors), scrolling horizontally, fading at the end with
  more; a "…" button opens a panel listing every section.
- The sections in their saved order, each an h2 and its dishes: name (h3), price "CHF 41.00",
  the description line under it when there is one. Sections with no dish are not shown. No
  section at all: the hero, then a single line saying the carte is being written.
- The plat du jour's dishes sit in their section like any other dish; it is not a second card here.

### Restaurant (/restaurant)
- Inner hero: photograph, name with the rule, the page's words, "Réserver une table" (WhatsApp).
- The restaurant gallery: a masonry of the owner's photographs, each keeping its own proportions
  (boxes reserved from the stored width and height), opening in a lightbox. Empty: nothing.

### Hôtel (/hotel)
- Inner hero: photograph, name with the rule, the page's words, "Réserver une chambre" (WhatsApp).
- The practical facts as a short icon list, each icon beside its words: Wi-Fi gratuit dans les
  parties communes, parking privé gratuit, arrêt de bus à 20 m, aéroport de Genève à 15 km, le lac
  à cinq minutes à pied. Not the restaurant's hours here — they would read as the hotel's.
- The hotel gallery, as on /restaurant.
- Nothing about room count, room types or rates: the site does not know them (§7).

### Contact (/contact)
- Inner hero: photograph, "Nous trouver", the page's words.
- "Nous rendre visite": a definition list — address (linked to Google Maps), telephone (tel:),
  email (mailto:), the hours (the schedule prefab), the two socials with their icons.
- The map prefab: a keyless Google Maps embed of Grand'Rue 31, lazy-loaded, with the address and
  an ember "Itinéraire" button — Google directions everywhere, Apple Maps on iOS and macOS.
- No message form: it was taken out on purpose (staff never read it; replies come by phone).

## 4. On every page

- Header: the kit's site-header, its navigation drawn from resources/pages.json — at most five
  items (Accueil, La carte, Restaurant, Hôtel, Contact), the wordmark "Auberge de Founex" on the
  left, the action "Réserver une table" (WhatsApp). It carries the mobile menu; do not build a
  second one. On a phone that menu is a full-height sidebar from the right with the pages large,
  and at its foot, where the thumb is, the reserve button, the telephone number and the hours. It
  stays at the top as the page scrolls, turning to a blurred raised bar once stuck.
- The frame: every page is four parts — the navigation, the hero, the content, the footer — laid
  out in the types /build's mockup chose (the direction's own until /build sets build.variants;
  no theme picker), the same on every page; the content's type is each page's layout. The whole
  site stands in one centred column 1200px wide: the banner, the bar, every hero photograph, the
  bands and the footer stop at its edges; wider windows show the edges as two hairlines with a
  soft shadow on a wall one step darker than the page. Narrower: the frame is the screen.
- Footer: the kit's site-footer — the wordmark and the blurb "Restaurant, bar et chambres à
  Founex. Une table de village et quelques chambres à l'étage.", the pages, the address (to Google
  Maps), telephone and email, the two socials, then the week's hours closing it, then
  "© <year> Auberge de Founex" and the credit "Réalisé par apdigital.ch" with its mark
  (public/apdigital.svg, carried over). And the admin button, which the kit draws. Never remove it.
- The closure banner (§5): a status strip above the header on every page while the auberge is
  closed — "L'auberge est actuellement fermée.", the period's reason when it has one, "Réouverture
  le <date>." when the date is known. It scrolls away with the page. It closes nothing: every page
  stays readable.

## 5. Features

None of these is a public form; the only form a visitor meets is the admin login.

1. The carte (menu prefab). Ordered sections, each with dishes: name, an optional description line,
   a price. Staff add, edit and delete sections and their dishes in /admin, drag a section to
   reorder it, delete a section with its dishes (confirmed), and remove single dishes.
2. The plat du jour. A card of one or several dishes, each a dish of the carte filed under one of
   its sections (flag daily, position on the card), with one date for the whole card. Staff edit
   it in place from the home page (an edit button only they see, above the card) and in /admin.
   - The date rolls forward to the next serving day: a card written for a closed day is served on
     the next open one, and the card shows that day.
   - Guests never see a card whose served day has passed; staff still do, with its old date, so
     they know to write today's. An undated card is never past.
   - The editor never offers a past date back.
   - Empty: guests see nothing; staff see the empty-state line. No section yet: the editor says to
     create one first.
3. Opening hours (schedule prefab). Seven rows, Monday first: closed all day, or lunch from–to and
   dinner from–to. Printed as runs of days that serve alike — "Mardi au samedi, 11h30 – 14h00 et
   18h00 – 22h00", then "Fermé le dimanche et le lundi" — in the footer, the phone menu, the home
   hero's facts and /contact, and read by the plat du jour and the JSON-LD. A missing table or
   row falls back to the published week rather than failing the page.
4. Closures. Two sources, either one closes the house: a manual switch in /admin (wins; until
   reopened by hand) and a calendar of periods (from, to, an optional reason). The banner shows
   the current one; the reopening date is the day after the period ends, none when closed by hand.
   Staff see the current state, the days of service and the next closure at the top of the screen,
   past/current/upcoming badges on each period ("Dans 12 jours"), and confirm before closing or
   reopening.
5. Page headers. Per page (home, carte, restaurant, hotel, contact): the hero photograph, the title
   (on the home page, the lead under the name) and the body text, edited in /admin.
6. Galleries. Three — home ("Accueil" in the admin), restaurant, hotel — of the owner's
   photographs: multi-file upload (images only, refused otherwise), multi-select delete
   (confirmed), width and height recorded at upload; a command measures older ones
   (php artisan gallery:measure [--all]).
7. Admin access. One role: any signed-in user is an administrator. Signed in from the footer's
   admin button; signed out from the footer, which then lists the admin sections (Pages, Galerie,
   La carte, Ouverture). The first account is seeded from SEED_ADMIN_EMAIL / SEED_ADMIN_PASSWORD;
   in production the seeder refuses to run without the password. Every write route sits behind the
   admin gate (tested: a visitor cannot upload to or delete from a gallery).
8. Search. A sitemap of the five pages (pages.json), a canonical per page, each page's own title
   ("La carte — Auberge de Founex") and its description, the Restaurant JSON-LD on /.

Every form or endpoint:
- Validated on the server by a FormRequest, stored in postgres through a migration, duplicates
  refused by a unique index where they apply (one row per weekday in the hours).
- Public forms behind throttle middleware and with a honeypot field — here only the login, which
  is throttled.
- A clear success and error message on the page, in the site's language; every field labelled.
- On the base (composer.json requires alpha-omega-corp/site), read what it already keeps — its
  content, its leads, its media, its admin — before adding a table for the same thing: the page
  headers and the galleries are content and media; the carte, the hours and the map are its
  prefabs if it has them. The plat du jour and the closures are this site's own.

## 6. Look and feel

Identity, written to DESIGN.md before the pages: a lit house at night — the auberge seen from the
Grand'Rue after dark, warm black all round, the house's own photographs as its windows, and one
ember orange for whatever is lit: the thing to press, the page you are on, the rule under a name.
Faces Playfair Display (400 at display size, 500 on a phone) for names only — the wordmark, page
h1s, section h2s, dish names — and Inter (400; 450 on a phone) for everything read or pressed. The
one all-caps voice is the small fact label (Inter 600, 0.6875rem, 0.14em). Signature section: the
ember rule — a 44 × 2px ember bar under every inner page's h1 — and the full-screen photo hero of
the home page.
Colours are the theme's tokens (var(--color-…)); these go in resources/css/site.css as tokens,
never as a hex in a view — the client's one binding constraint is orange and black:
- ink #141210 — the page; ink-2 #1E1B18 and ink-3 #24201C — raised planes
- paper #F3EDE4 — names, primary text (16:1); paper-1 #DCD4C9 — running copy; paper-2 #B0A79B —
  secondary; paper-3 #928A80 — labels and meta only
- ember #F26B1D — lit: the primary action, the current page, the rule (6.1:1 on ink);
  ember-hi #FF8A3D — hover and focus; ember-lo #B84A0E — pressed and borders, never small text
- ink-soft #3A2A18 — secondary text on ember
- line #2E2A25 — hairlines; line-strong #736A60 — interactive borders; wall #0B0A09 — outside the frame
Rules: text on ember is ink (its quieter line ink-soft), never paper and never ink thinned with
opacity. One ember fill per screen; a second action beside it is a ghost button. Text over a
photograph sits on a scrim, never on the bare picture.
Pictures: one set in ember line, from deployer's picture library (collection `ember-line`:
tete-de-cerf, cloche-fumante, couvert-et-verre-de-vin, carte-ouverte, lit-sous-la-fenetre,
carte-pliee-et-repere, auberge-aux-fenetres-allumees) or drawn in that manner and filed back
(site-assets, site-illustration): one pen weight, round caps, strokes in currentColor, paper on
warm black, ember for the one lit thing (steam, a lamp, a window, a pin), a soft ember glow under
the subject, flat frontal elevation, an alt beginning "Dessin de…". Never a pretend photograph of
the business, never words in a picture, never people; an animal drawn as a sign would carry it.
Do not use the older `auberge-lamplit` set. `auberge-aux-fenetres-allumees` stands in only where
a page has no photograph.
Motion: the home hero's photograph scrolls away slower than the page (all heroes may); the chasse
sign's antlers draw in and its light follows the pointer; the doors reveal as they arrive;
elsewhere only in answer to a click. prefers-reduced-motion respected everywhere, with a still
fallback.

Quality:
- Mobile-first and responsive; a visible focus style and a skip link ("Aller au contenu").
- Semantic landmarks, one h1 per page, an alt on every picture, WCAG AA contrast for every accent on
  its ground.
- mcp__deployer__check_site answers that the site holds together: no FAILED finding, its notes
  fixed where the fix is ours.

## 7. Content

Copy is the site's, not the components': on the base, database/content/fr.php (identity, seo and
each prefab's schema); otherwise the views and lang/fr. Lists — dishes, hours, closures — are rows
in the database, seeded by database/seeders/DemoSeeder.php from the demo parenthesis, never typed
into a view. The voice is the house's own: plain and unadorned — "une table de village et quelques
chambres à l'étage". No luxury-hotel vocabulary.
Real text comes later from l'Auberge de Founex. Until then a placeholder reads
"[TEXT: <what> — l'Auberge de Founex]" and appears only on pages that are not a priority. Already
owed by the house: a line about what the rooms are like.

Content rules — enforced by a test (php artisan test) that fails and lists every hit:
- The name is "Auberge de Founex": never "Auberge De Founex", "auberge de founex" in a title, or
  "Auberge Communale".
- No reviews, testimonials, awards, press, star ratings, room count, room rates, capacity, chef
  biography, founding date or house history beyond "rénovée en juillet 2012".
- Banned words: luxe, luxueux, prestige, prestigieux, raffiné, exquis, d'exception, gastronomique,
  étoilé, 5 étoiles.
- No promise of an online form ("formulaire") and no Booking.com link or "réservation via Booking".
- No hex colour in a view.

## 8. Done means

- composer install, npm run build and php artisan test pass.
- mcp__deployer__prepare_local_app answers / with 200 on this machine, against its own postgres,
  with the demo seeded. These work end to end: staff sign in from the footer, write today's plat
  du jour on the home page and see it in the hero chip and the card; close the site and see the
  banner on every page, reopen it and see it go; add a closure period with a reason and see the
  banner on its first day with the reopening date; change Tuesday's hours and see the footer,
  /contact and the JSON-LD follow; upload two photos to the hotel gallery and open one in the
  lightbox; a guest does not see yesterday's plat du jour.
- mcp__deployer__check_site holds the site together.
- mcp__deployer__preflight_project refuses nothing but the repository (a local project has none
  until it is published).
- mcp__deployer__record_change wrote that it exists.
- README.md says what the site is, that deployer deploys it, which .env keys the server's copy must
  set (SEED_ADMIN_EMAIL, SEED_ADMIN_PASSWORD), and the artisan commands the owner runs
  (gallery:measure).

When finished, report:
1. what is built in full and what is still placeholder,
2. every placeholder the owner must fill — text, photographs, documents — by page,
3. every assumption made where the brief was silent.
```

---

## Appendix — not part of the brief

### Decisions this brief made

- **French only.** The current site is bilingual (French at `/`, English under `/en`, a locale
  switch that keeps the visitor on the same page, hreflang). The pipeline offers one language per
  site, so English goes. A second English site is possible later; until then `/en/*` should
  301 to its French page (`/en/menu` → `/la-carte`), which is an nginx include on the
  server, not part of the site.
- With it go the translation machinery (`App\Support\Translations`, the glossary in
  `resources/glossary/fr-en.php`, MyMemory through `App\Services\Translator`, the
  `TranslateRecord` job, `php artisan carte:translate`, the admin's "À relire" panel and the
  per-language holiday reason).
- **Theme sandstone, direction carte, home layout poster.** No kit theme is orange and black;
  sandstone is the nearest in spirit (warm, serif display, rated best with carte and poster) and is
  re-coloured to the house's tokens. Check the names against the Build tab's lists before running.
- **Paths kept** (`/la-carte`, `/restaurant`, `/hotel`, `/contact`) so the indexed URLs survive.
- **The contact page's hero title** changes from "Nous écrire" to "Nous trouver", since there is
  no form to write in.

### Dropped on purpose

- The contact form and its `contacts` table (taken out of the page already; the table stayed).
- The unused `about` page, `MenuItemLocale` leftovers, the `public/images/generated/` set.
- Bootstrap, jQuery and the native SCSS: the kit and its tokens replace them.

### Carry over from the live site (aubergedefounex.ch, production, project `afx`)

The rewrite is a new project; the live site keeps running until a cutover is decided. Before
the cutover, from the production database and `storage/app/public`:
- the carte as staff have edited it since the seed (sections, dishes, prices, order);
- the week's hours (the live admin's, which differ from the old seed);
- the closure calendar and the manual switch;
- every page's photograph, title and text, and the three galleries' photographs;
- the admin account(s).

### Copy to carry over (the house's own words, from `database/factories/PageFactory.php`)

- **Accueil** — lead: *Nous sommes ravis de vous accueillir dans une auberge traditionnelle et
  chaleureuse.* Text: *Au centre du village, à cinq minutes du lac Léman, l'Auberge de Founex réunit
  sous un même toit une salle à manger, un bar et quelques chambres à l'étage. La maison a été
  rénovée en juillet 2012. On y sert une cuisine régionale à midi comme le soir, et sur la terrasse
  dès les beaux jours.*
- **La carte** — *Le chef vous propose une cuisine traditionnelle et soignée : les filets de perche
  du lac Léman, ou notre souris d'agneau confite au Pinot Noir de Founex. La carte suit les saisons,
  et le plat du jour est écrit à l'ardoise.*
- **Le restaurant** — *Au centre du village, à 500 mètres du lac Léman, l'auberge a été rénovée en
  juillet 2012. On y mange en salle, à la brasserie, ou dès les beaux jours sur la terrasse ombragée
  qui donne sur la place et le jardin. Le bar reste ouvert entre les services.*
- **Les chambres** — *Quelques chambres à l'étage, simples et calmes, toutes rénovées. Wi-Fi gratuit
  dans les parties communes et parking privé gratuit. L'arrêt de bus est à 20 mètres, l'aéroport de
  Genève à 15 kilomètres, et le lac à cinq minutes à pied.*
- **Contact** — *Une table, une chambre, une question : appelez-nous, écrivez-nous, ou passez
  simplement à la Grand'Rue.*
- **Footer blurb** — *Restaurant, bar et chambres à Founex. Une table de village et quelques
  chambres à l'étage.*

The live database may hold edited versions of these; those win.

### Still open with the house

- The real hours, confirmed (the live admin shows one week; the old site another).
- Whether "Réserver une table" should go to the same WhatsApp number as the rooms (assumed).
- Whether the summer carte ("Carte estivale") is still served.
- One honest line about the rooms.
- The Booking.com listing: the old site carried a "réservation via Booking" string with no link.
