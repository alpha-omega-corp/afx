# Ideas — Auberge de Founex

What the site should do next, newest first. Written from the live site (aubergedefounex.ch, read 16 October 2026), `PRODUCT.md`, `DESIGN_PLAN.md` and the project log. This repo has no `DESIGN.md` or `resources/pages.json`: it is not a worker-template site, its five pages are routed in `routes/web.php`. Kit components are Tailwind markup; here they are rebuilt in the native SCSS (`_glass.scss`, `_bento.scss`, tokens), never by adding Tailwind.

Nothing below invents a price, an hour or a fact. Every fact quoted is already on the site.

## 2026-10-16

### 1. A plat du jour that is a month old should not be shown
- **For:** the returning diner who opens the site to see today's dish, mostly on a phone.
- **Found:** on 16 October the home hero chip and the card under it show the plat du jour for *jeudi 17 septembre*. The carte page shows the same card again. Meanwhile staff post the real dish as a carte section called *« Plat du jour Vendredi 16 Octobre »* (EN *"Dish of the day Friday, October 16"*), with the perch at CHF 21.00. That gives two plats du jour on one page, one of them stale. `x-daily` already leaves the date out when none is set. It does not hide a date that has passed.
- **Smallest change:** in `components/daily.blade.php` and the home hero chip, treat specials whose `servedOn()` is before today as empty for guests. Staff keep seeing the card along with its "edit" button, so they know to update it. Then ask the house to write the day's dish in the daily editor instead of a carte section.
- **Agent:** code (then content, for the house's habit).

### 2. Draw the carte with the house's dishes, not a generic cloche
- **For:** the diner choosing between the auberge and the next restaurant on La Côte.
- **Found:** the carte page uses `x-illustration.cloche` twice, in its intro panel and in the daily card just below it. The carte's own words name what the house is known for: *filets de perche du lac Léman*, perch à volonté every Wednesday evening, perch at lunch every Friday, *fondue au fromage* (brasserie side), *assiette de viandes séchées*. Deployer's library already holds these in the auberge's own lamplit set: `auberge-lamplit/table-dressee` (perch fillets, lemon, bread, red wine), `auberge-lamplit/caquelon-de-fondue`, `auberge-lamplit/planche-du-terroir`, plus `lakeside-ink/pesce-persico`.
- **Smallest change:** use `table-dressee` for the carte intro's art in place of the cloche, so the cloche stays with the plat du jour only. If one more fits, put `planche-du-terroir` beside *Nos entrées* or `caquelon-de-fondue` beside *Suggestions*. Take each one with `get-ui-picture` in the page's palette.
- **Agent:** illustration (Quill); placement in the intro panel → design (Loom).

### 3. Show what the hotel already says, as facts and not a paragraph
- **For:** the overnight guest looking for a room near Geneva. PRODUCT.md calls the rooms "the claim".
- **Found:** `/hotel` is one sentence, a WhatsApp button and a gallery. The sentence holds four facts a traveller scans for: free Wi-Fi in the common areas, a private car park, the village bus stop 20 m away, Geneva airport 15 km away. The restaurant page adds the lake, 500 m away. Nothing says what the rooms are like (how many, what kind). That is the owner's to tell, not ours to write.
- **Smallest change:** under the hotel intro, put those five facts in a short icon list. Base it on kit `data-display/description-lists/05-two-column`, rebuilt in the site's SCSS, with Lucide icons (wifi, car, bus, plane, waves), each next to its words. Leave the paragraph as it is. Then ask the house for one honest line about the rooms.
- **Agent:** design (Loom) for the list; content for the rooms line; Pocket to check it on a phone.

### 4. Fix the copy that is broken or out of date
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

### 5. Say whether the auberge is open right now
- **For:** someone deciding at 13h30 or 18h whether to drive out. This is the first of PRODUCT.md's three questions.
- **Found:** the header, footer and contact page print the week (*Lundi au vendredi, 7h00 – 14h30 et 17h00 – 23h00 · Fermé le samedi et le dimanche*). The visitor still has to work out the time against it. The closure banner only covers holidays and the manual switch. `App\Support\Opening` already has the week and the closures.
- **Smallest change:** add one status line beside the hours in the header's facts: *Ouvert · jusqu'à 14h30* or *Fermé · rouvre à 17h00* (or *lundi 7h00*). Compute it from `Opening::week()` and `closedToday()`, in the visitor's language and in Europe/Zurich time.
- **Agent:** code; Pocket for how it sits in the phone menu.
