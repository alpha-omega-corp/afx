# Design

What the guest site looks like and why, as it ships. Tokens live in `resources/sass/_tokens.scss`
and win over anything written here; `DESIGN_PLAN.md` is the original plan and is out of date
where they differ (it forbade shadows and parallax, which the hero now has).

## Concept

**A lit house at night.** The auberge seen from the Grand'Rue after dark: warm black all round,
the house's own photographs as the windows, and one ember orange for whatever is lit — the thing
to press, the page you are on, the rule under a name. The voice is the house's own, *une table
de village et quelques chambres à l'étage*: plain, unadorned, no luxury vocabulary, and the type
says the same thing.

## Faces

- **Playfair Display** (variable, 400 at display size, 500 on a phone) for names only: the
  wordmark, page `h1`s, section `h2`s, dish names. Never for running text, labels or buttons.
- **Inter** (variable, 400; 450 on a phone) for everything a guest reads or presses.
- The one all-caps voice is the small fact label (`dt` in the hero facts, the footer's
  headings): Inter 600, `0.6875rem`, `0.14em` tracking. Nothing else is set in capitals.
- Scale from the tokens: display `clamp(2.375rem, 6vw, 5.5rem)`, h2 `clamp(1.75rem, 3vw, 2.75rem)`,
  lead `1.1875rem`, body `1.0625rem`, small `0.9375rem`; prose capped at `68ch`, hero words at `46ch`.

## Palette

Colour means something here; it is never decoration.

| Token | Role | Contrast |
|---|---|---|
| `$ink` `#141210` | the page, warm black | — |
| `$ink-2` / `$ink-3` | raised planes, one shade apart | — |
| `$paper` `#F3EDE4` | names and primary text | 16:1 on `$ink` |
| `$paper-1` `#DCD4C9` | running copy, leads | 12.7:1 |
| `$paper-2` `#B0A79B` | secondary text | 7.9:1 |
| `$paper-3` `#928A80` | labels and meta only | 5.5:1 on `$ink`, 4.75:1 on `$ink-3` |
| `$ember` `#F26B1D` | **lit**: the primary action, the current page, the name's rule | 6.1:1 on `$ink` |
| `$ember-hi` | hover and focus | 8.0:1 |
| `$ember-lo` | pressed state and borders, never small text | — |
| `$ink-soft` `#3A2A18` | secondary text **on** ember | 4.5:1 on `$ember`, 5.9:1 on `$ember-hi` |

- Text on ember is `$ink`, its quieter line `$ink-soft`. Never `$paper` (2.6:1), and never
  `$ink` thinned with `opacity` — ink at 72% over ember is 4.0:1 and fails.
- One ember fill per screen. A second action beside it is a ghost button (paper hairline).
- Text over a photograph sits on the scrim (`.hero__scrim`), never on the bare picture.

## Signature

**The ember rule under a name** — a 44 × 2px bar of `$ember` under each inner page's `h1`
(`.hero--rule`), and the same ember hairline along the top edge of the glass cards. It is the one
ornament the site allows; anything else that wants to glow is lit in an illustration instead.

## First screen (the hero)

Every page opens on the house's own photograph (`.hero--cover`), scrolling away slower than the
page. Nothing sits on it in a card or panel:

- **Home** is the one full-screen hero (`100svh`): name, lead and the two actions straight on the
  scrim, lower left. The photograph is the subject of the first screen.
- **Inner pages** are quieter (`.hero--stage`): a band of `clamp(460px, 68svh, 720px)`, a darker
  scrim, the name at a smaller size with its ember rule, the page's words and its one action
  straight on the scrim, lower left. The photograph is a backdrop there; no card, no emblem. On a
  phone it is a `44svh` band with the words following on the page under it.
- **The practical facts** (hours from `App\Support\Opening`, the numbers, the address) close the
  home hero on a hairline of their own; they are the house's real facts and never copy. Inner
  heroes leave them to the footer (and the contact page's own panel).
- One primary action in the first screen, ember; the reservation number it dials is shown on it
  in `$ink-soft`.

## Illustrations

One manner for every picture that is not a photograph: **ember line**.

- **Line.** One pen weight: `stroke-width="2.25"`, round caps and joins, no fill but a dot (an eye, a nose). Strokes are
  `currentColor`, so a drawing takes the text colour of wherever it lands and turns to
  `CanvasText` in forced colours (`resources/sass/components/_illustration.scss`).
- **Palette.** Paper on warm black, and `$ember` for the one thing that is lit — steam, a lamp,
  a window, a pin, a flame (`.ill-accent`, `.ill-lit`, `.ill-lantern`, `.ill-pin`). A soft
  `.ill-glow` ellipse of ember at 9% sits under the subject. No other colour, no gradient.
- **Perspective.** Flat, frontal elevation; a fold or a skew where the object itself bends (the
  map, the carte). One subject, a ground line under it (a sign hangs, so the stag has none), nothing
  in the distance.
- **Never** words, people, or anything that could pass for a photograph of the house. An
  animal is drawn as a sign would carry it — the stag has dot eyes and a nose, never a mouth or
  an expression. Photographs are the auberge's own and are uploaded in Administration → Pages /
  Galleries; every hero background, door tile and gallery picture is one of those, never a drawing.
- **Scale.** A drawing must still read at emblem size (about 5rem wide, a hairline stroke): one
  silhouette, no detail under 4 units of its viewBox, the lit part large enough to carry the ember.
- **Where they live.** Inline Blade components in `resources/views/components/illustration/`,
  each with `role="img"` and an alt from `resources/lang/{fr,en}/illustration.php` that begins
  “Dessin de…” / “Drawing of…”. The same drawings are filed in deployer's picture library as the
  `ember-line` collection (search it before drawing a new one; file any new one back there).

| Slot                                         | Drawing   | Library name                   |
|----------------------------------------------|-----------|--------------------------------|
| Home, “Spécialité de chasse” sign (animated) | `cerf`    | `tete-de-cerf`                 |
| Plat du jour card (home, carte)              | `cloche`  | `cloche-fumante`               |
| *(spare — was the restaurant hero card)*     | `table`   | `couvert-et-verre-de-vin`      |
| *(spare — was the carte hero card)*          | `carte`   | `carte-ouverte`                |
| *(spare — was the hôtel hero card)*          | `room`    | `lit-sous-la-fenetre`          |
| *(spare — was the contact hero card)*        | `map`     | `carte-pliee-et-repere`        |
| *(spare — no slot since the photo hero)*     | `auberge` | `auberge-aux-fenetres-allumees`|

No hero carries a drawing since the cards went; the four page emblems stay in
`components/illustration/` for a slot that wants them. `auberge` is the drawing to reach for if a page ever opens without its photograph (a bare hero or a door
tile with no image) — never as a stand-in on a page that has one.

`public/images/generated/` holds an older, filled-gradient set (library collection
`auberge-lamplit`) in a different manner. Nothing references it; do not wire it in.
