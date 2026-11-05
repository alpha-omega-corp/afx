# Ideas — Auberge de Founex

What the site should do next, newest first. Written from `PRODUCT.md`, `DESIGN.md`, the views, the project log and deployer's record of the domain and rollouts. The repo has a `DESIGN.md` (illustration manner, ember line) but no `resources/pages.json`: it is not a worker-template site, and its five pages are routed in `routes/web.php`. Kit components are Tailwind markup. Here they are rebuilt in the native SCSS (`_glass.scss`, `_bento.scss`, tokens), never by adding Tailwind.

Nothing below invents a price, an hour or a fact. Every fact quoted is already in the site's own words.

## 2026-10-31 — the heroes

Read against b806525 (full-screen parallax photo hero, the page's words and facts in one glass card over it), on integration as rollout #12 and not yet live. Each idea is a step for whoever is improving the heroes now. None of them asks for a new layout.

### 1. The hotel's hero gives the restaurant's hours
- **For:** the overnight guest. PRODUCT.md says the rooms are "the claim".
- **Found:** `components/hero.blade.php` prints the same three facts on every page: hours from `Opening::schedule()`, the reservation numbers and the address. On `/hotel` the first fact a traveller reads is the restaurant's week, ending *« Fermé le samedi et le dimanche »*. That reads as if the hotel shuts at weekends, which the site does not know. Meanwhile the hotel's own paragraph holds the facts a traveller scans for: free Wi-Fi in the common areas, free private parking, the bus stop 20 m away, Geneva airport 15 km away, the lake five minutes on foot. In the card they are buried in a sentence.
- **Smallest change:** let `x-page`/`x-hero` take a named facts set. `/hotel` passes its own: bus 20 m, airport 15 km, parking, Wi-Fi, each with a Heroicon beside its words, plus the existing reservations fact pointing at WhatsApp. The hours stay off the hotel hero. Keep the `hero-stage__fact` markup so it looks the same. Words come from the hotel paragraph only; ask the house to confirm them. This replaces idea 3 of 16 October.
- **Agent:** code (hero facts prop, lang strings); design (Loom) for the icons and the four-up strip.

### 2. "Voir la carte" lands on a photograph, not the carte
- **For:** the diner on a phone who tapped *Voir la carte* on the home page to see what is served tonight. This is PRODUCT.md's first question.
- **Found:** the carte hero now fills at least one whole screen (`.hero--cover`, `min-height: 100svh`). Its card holds the drawing, the title, the paragraph and the three facts, but no button: `app/menu.blade.php` passes no `cta`. The first section of the carte and its section rail (`.menu-anchors`) start below the first screen. Nothing on screen says the carte is down there, and the hero has no scroll cue either.
- **Smallest change:** give the carte hero one action, *Voir la carte ↓* (`__('app.see_menu')`), anchored to the section rail. If a plat du jour is served today, also give it the home's `hero-chip` as its eyebrow, pointing at `#plat-du-jour`. Optionally, cap the carte's hero below a full screen (it is a reference page, not a welcome) so the rail peeks above the fold.
- **Agent:** code for the anchor and the chip; design (Loom) if the height changes.

### 3. The home hero does not say whether the house is open
- **For:** someone deciding at 13h30 or 18h whether to drive out. This is PRODUCT.md's second question, and principle 2.
- **Found:** the inner pages print the week in their facts strip, but the home hero has none (`:intro="false"`, so no facts). Its first screen answers "what" and "how to book", not "is it open". The hours are real and editable now (`Opening::days()` reads the admin's table), and `closedToday()` and `nextOpen()` already exist. Idea 5 of 16 October is still open. The home hero is where it would pay most.
- **Smallest change:** add one line under the home hero's buttons: *Ouvert · jusqu'à 14h30* or *Fermé · rouvre à 17h00* / *lundi à 7h00*. Compute it from `Opening::week()`, `closedToday()` and `nextOpen()` in Europe/Zurich, in the visitor's language. It needs no new hour, only the published ones. When the closure banner is up, the line says the same as the banner, never something else.
- **Agent:** code; Pocket for how it sits under the two buttons on a phone.

### 4. The home hero never mentions the rooms
- **For:** the overnight guest arriving on the home page, and the diner who does not know there are rooms.
- **Found:** the home hero is the name, the lead *« Nous sommes ravis de vous accueillir dans une auberge traditionnelle et chaleureuse. »* and two buttons (*Réserver une table*, *Voir la carte*). The rooms first appear in the three doors, below the chasse sign and the plat du jour. The house already describes itself in its own register: *« une table de village et quelques chambres à l'étage »* (PRODUCT.md), and the home paragraph says *« … un bar et quelques chambres à l'étage »*.
- **Smallest change:** under the two buttons, add a quiet text link *Les chambres →* to `/hotel` (EN *The rooms →*), not a third button. Then ask the house whether their own line may become the lead in Administration → Pages (home, title). Don't write a new one for them.
- **Agent:** design (Loom) for the link; content for the lead.

### 5. On a phone the card may cover the photograph it sits on
- **For:** the phone visitor, who is most visitors (PRODUCT.md, Accessibility).
- **Found:** on the inner pages the glass card carries the drawing, a title up to 2.375rem, the whole page paragraph (the restaurant's is about 300 characters), the button and three stacked facts. At 390 px wide that is likely about one screen tall. The hero grows past `100svh` to fit it, so the full-screen photograph shows as a band under the nav, and the parallax moves a picture nobody sees. Not measured from here. Check it at 390×844 before acting.
- **Smallest change:** below `md`, cut the card's paragraph to its first sentence with the existing `x-prose-more` (it already cuts on a word and offers the rest). Let the facts run as one compact row of icons + short labels rather than three stacked blocks, so the photograph keeps the top half of the first screen.
- **Agent:** Pocket (mobile) to measure and decide; design (Loom) for the compact facts.

## 2026-10-16

### 1. A plat du jour that is a month old should not be shown — **done** (f530b07 hides past plats du jour from guests)
- **For:** the returning diner who opens the site to see today's dish, mostly on a phone.
- **Found:** on 16 October the home hero chip and the card under it show the plat du jour for *jeudi 17 septembre*. The carte page shows the same card again. Meanwhile staff post the real dish as a carte section called *« Plat du jour Vendredi 16 Octobre »* (EN *"Dish of the day Friday, October 16"*), with the perch at CHF 21.00. That gives two plats du jour on one page, one of them stale. `x-daily` already leaves the date out when none is set. It does not hide a date that has passed.
- **Smallest change:** in `components/daily.blade.php` and the home hero chip, treat specials whose `servedOn()` is before today as empty for guests. Staff keep seeing the card along with its "edit" button, so they know to update it. Then ask the house to write the day's dish in the daily editor instead of a carte section.
- **Agent:** code (then content, for the house's habit).

### 2. Draw the carte with the house's dishes, not a generic cloche — **done, differently** (the carte hero has its own ember-line `carte` drawing; DESIGN.md now rules out the `auberge-lamplit` set proposed here)
- **For:** the diner choosing between the auberge and the next restaurant on La Côte.
- **Found:** the carte page uses `x-illustration.cloche` twice, in its intro panel and in the daily card just below it. The carte's own words name what the house is known for: *filets de perche du lac Léman*, perch à volonté every Wednesday evening, perch at lunch every Friday, *fondue au fromage* (brasserie side), *assiette de viandes séchées*. Deployer's library already holds these in the auberge's own lamplit set: `auberge-lamplit/table-dressee` (perch fillets, lemon, bread, red wine), `auberge-lamplit/caquelon-de-fondue`, `auberge-lamplit/planche-du-terroir`, plus `lakeside-ink/pesce-persico`.
- **Smallest change:** use `table-dressee` for the carte intro's art in place of the cloche, so the cloche stays with the plat du jour only. If one more fits, put `planche-du-terroir` beside *Nos entrées* or `caquelon-de-fondue` beside *Suggestions*. Take each one with `get-ui-picture` in the page's palette.
- **Agent:** illustration (Quill); placement in the intro panel → design (Loom).

### 3. Show what the hotel already says, as facts and not a paragraph — **superseded** by idea 1 of 31 October (the facts now belong in the hotel hero's strip)
- **For:** the overnight guest looking for a room near Geneva. PRODUCT.md calls the rooms "the claim".
- **Found:** `/hotel` is one sentence, a WhatsApp button and a gallery. The sentence holds four facts a traveller scans for: free Wi-Fi in the common areas, a private car park, the village bus stop 20 m away, Geneva airport 15 km away. The restaurant page adds the lake, 500 m away. Nothing says what the rooms are like (how many, what kind). That is the owner's to tell, not ours to write.
- **Smallest change:** under the hotel intro, put those five facts in a short icon list. Base it on kit `data-display/description-lists/05-two-column`, rebuilt in the site's SCSS, with Lucide icons (wifi, car, bus, plane, waves), each next to its words. Leave the paragraph as it is. Then ask the house for one honest line about the rooms.
- **Agent:** design (Loom) for the list; content for the rooms line; Pocket to check it on a phone.

### 4. Fix the copy that is broken or out of date — *partly fixed in the seeded copy (the restaurant intro is whole there); the live database was not readable from here. Check each item in Administration*
- **For:** every visitor. These are the first words on each page.
- **Found (all editable in Administration):**
  - `/restaurant` intro stops mid-sentence: *« … Elle vous propose un restaurant régional »* / EN *"It offers a regional restaurant"*. The home page's own sentence (*« On y sert une cuisine régionale à midi comme le soir, et sur la terrasse dès les beaux jours »*) could finish it.
  - `/contact` still promises *« via notre formulaire en ligne »* / *"via our online form"*, but that form was taken out.
  - `/hotel`: *« L hôtel est implantée »*.
  - Carte, EN: the section titles *Carte estival* and *Pour Les Ptits Loups* are untranslated. *Carte estival* (summer menu) is still up in October. Dish typo: *« Émince de veau zhurichoise »*.
  - The EN carte intro leaves out the Wednesday-evening perch à volonté that the FR intro announces.
  - EN home lead: *"Welcome to Auberge De Founex"* has a capital D.
- **Smallest change:** correct the text in the admin's page and carte editors. No code. Ask the house whether the summer carte is still served before hiding it.
- **Agent:** content.

### 5. Say whether the auberge is open right now — *still open; carried into idea 3 of 31 October, for the home hero*
- **For:** someone deciding at 13h30 or 18h whether to drive out. This is the first of PRODUCT.md's three questions.
- **Found:** the header, footer and contact page print the week (*Lundi au vendredi, 7h00 – 14h30 et 17h00 – 23h00 · Fermé le samedi et le dimanche*). The visitor still has to work out the time against it. The closure banner only covers holidays and the manual switch. `App\Support\Opening` already has the week and the closures.
- **Smallest change:** add one status line beside the hours in the header's facts: *Ouvert · jusqu'à 14h30* or *Fermé · rouvre à 17h00* (or *lundi 7h00*). Compute it from `Opening::week()` and `closedToday()`, in the visitor's language and in Europe/Zurich time.
- **Agent:** code; Pocket for how it sits in the phone menu.
