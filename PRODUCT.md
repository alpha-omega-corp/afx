# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

**Primary — local and regional diners.** People in Founex, along La Côte, and across the Nyon–Geneva belt deciding where to eat. They mostly know roughly where the auberge is; they want to know what is on the carte, whether it is open, and how to reserve. Confirmed as the primary audience of the guest site.

**Secondary — overnight guests.** Travellers and visitors needing a room near Geneva. Fewer in number, but the differentiated offer (see Positioning).

**Back office — staff, on a weekly rhythm.** Someone on the team updating la carte, photographs and closures regularly. Not a daily driver and not a once-a-quarter visit: it should reward repetition and stay quick for someone who already knows it.

## Product Purpose

The public site for **Auberge de Founex** — restaurant, bar and hotel at Grand'Rue 31, 1297 Founex (Vaud, Switzerland), on La Côte between Geneva and Nyon.

It exists so that someone deciding where to eat, or where to sleep near Geneva, can see the carte, the rooms and the house, learn whether the auberge is open, and reserve. Success is a table booked or a room taken — by telephone, by WhatsApp, or by a walk-in who checked the carte first.

It is also the tool the house uses to keep that information true: la carte, the photographs, and the days the auberge is closed.

## Positioning

**The rooms above the bar.** A neighbouring restaurant on La Côte can copy a menu; it cannot offer simple, honest, affordable rooms minutes from Geneva airport and the lake. The auberge is the practical alternative to a chain hotel — a real inn, where the bed sits above a working table rather than a hotel with a restaurant attached.

There is a deliberate asymmetry here, confirmed in the product interview: **most visitors arrive as diners, but the claim that distinguishes the house is the rooms.** The site serves the first without letting the second become a footnote.

## Operating Context

- Bilingual French and English. French is the default and lives at the root (`/`, `/la-carte`); English is prefixed (`/en`). The locale switch keeps the visitor on the equivalent page.
- **Reservations happen off the site**: telephone (022 776 10 29), WhatsApp (+41 78 685 78 45), or in person. The site takes no bookings and holds no availability, inventory or rates.
- The contact form **writes a message to the database and sends no email**. Staff read messages in the back office and reply from their own mail client. A message nobody opens in the admin is a message nobody sees.
- The house closes — weekly rest days, annual holidays, and the unforeseen. Guests need to know before they drive out.
- Staff maintain the site from the back office on a weekly rhythm, mainly la carte and closures.

## Capabilities and Constraints

Confirmed:

- Guest surfaces: home, la carte, restaurant, hotel, contact — each with an editable header (image, title, body) per language.
- La carte is a set of ordered sections, each holding dishes with a title, description and price. **Menu content is not translated**: one title and one description serve both languages. (`app/Models/MenuItemLocale.php` exists but is unused and has no table behind it.)
- Three photo galleries — home delicacies, restaurant, hotel — managed as uploads with multi-select delete.
- Open/closed state has two sources: a manual site-wide switch, and a calendar of closure periods carrying an optional per-language reason. Either one closes the house and raises a banner for guests. **Closing is informational** — the site stays fully browsable.
- Contact messages are listed, read and deleted in the back office.
- Access control is binary: any authenticated user is an administrator. There are no roles.
- Prices are stored as plain numbers; no currency or formatting rule is recorded.

Open / undecided — record, do not invent:

- **Real opening hours are not known.** The footer and contact page ship a placeholder explicitly marked TODO in the language files. Nothing may present it as fact.
- **Booking.com is unresolved.** The language files carry a "reserve via Booking" string, but no Booking.com link exists anywhere in the site. Either the listing or the string is stale.
- Whether "réserver une table" should route to the same WhatsApp number as the rooms is an assumption, not a confirmation.

## Brand Commitments

- Name: **Auberge de Founex**, used in full as the wordmark. No logotype asset exists.
- Grand'Rue 31, 1297 Founex, Vaud, Switzerland.
- Telephone 022 776 10 29 · aubergedefounex@bluewin.ch · WhatsApp +41 78 685 78 45.
- Facebook `facebook.com/AubergeFounex` · Instagram `@auberge_de_founex`.
- **Voice: plain and unadorned, in both languages.** The house describes itself as *"une table de village et quelques chambres à l'étage"* — a village table and a few rooms upstairs. That register is the commitment: no luxury-hotel vocabulary.
- One binding visual constraint from the client brief: the site is **orange and black**. The detail of that system lives in `DESIGN_PLAN.md`, not here.

## Evidence on Hand

Real:

- Address, telephone, email, WhatsApp number and both social accounts — live in `resources/views/components/footer.blade.php`.
- The building photograph, `resources/images/afx-building.jpg`.

**Not real. Must not be presented as fact, quoted, or reused as copy:**

- **Every page header and all menu content in the database is Faker-generated placeholder text** (`database/seeders/PageSeeder.php` and the model factories). The titles and paragraphs visible today are lorem, not the auberge's words.
- Gallery photographs and page images currently in the database are demonstration content.
- Opening hours, as above.
- The holiday calendar holds three demonstration periods added while the feature was built.

Absent, and not to be invented: reviews, testimonials, awards, press, star ratings, room counts, room rates, capacity, chef biography, founding date or house history.

## Product Principles

1. **Answer the three questions first** — what is on the carte, is it open, how do I reserve. Everything else is secondary to someone deciding where to eat tonight.
2. **Closure is information, not a wall.** When the house is shut, say so plainly and say when it opens — then let people keep reading.
3. **Never dress up what is not there.** Placeholder copy, unknown hours and absent reviews stay visibly absent rather than being filled with plausible prose.
4. **The rooms are the claim; the table is the door.** Serve the diner first, but never reduce the rooms to a footnote.
5. **The back office is a weekly tool.** Fast and repeatable for someone who has done it before, and still legible after a fortnight away.

## Accessibility & Inclusion

- Bilingual FR/EN is functional, not decorative: both languages must reach the same information. Menu content is the known, recorded exception.
- WCAG AA contrast is an established commitment of the current design — text on the ember orange is always ink, never white. Focus is visibly indicated everywhere and never removed.
- `prefers-reduced-motion` is honoured.
- Guests read this on a phone, often outdoors, often deciding within minutes.
