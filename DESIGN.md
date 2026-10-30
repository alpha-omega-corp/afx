# Design

## Illustrations

One manner for every picture that is not a photograph: **ember line**.

- **Line.** One pen weight: `stroke-width="2.25"`, round caps and joins, no fill. Strokes are
  `currentColor`, so a drawing takes the text colour of wherever it lands and turns to
  `CanvasText` in forced colours (`resources/sass/components/_illustration.scss`).
- **Palette.** Paper on warm black, and `$ember` for the one thing that is lit — steam, a lamp,
  a window, a pin, a flame (`.ill-accent`, `.ill-lit`, `.ill-lantern`, `.ill-pin`). A soft
  `.ill-glow` ellipse of ember at 9% sits under the subject. No other colour, no gradient.
- **Perspective.** Flat, frontal elevation; a fold or a skew where the object itself bends (the
  map, the carte). One subject, a ground line under it, nothing in the distance.
- **Never** words, figures, faces, or anything that could pass for a photograph of the house.
  Photographs are the auberge's own and are uploaded in Administration → Pages / Galleries.
- **Where they live.** Inline Blade components in `resources/views/components/illustration/`,
  each with `role="img"` and an alt from `resources/lang/{fr,en}/illustration.php` that begins
  “Dessin de…” / “Drawing of…”. The same drawings are filed in deployer's picture library as the
  `ember-line` collection (search it before drawing a new one; file any new one back there).

| Slot                          | Drawing                 |
|-------------------------------|-------------------------|
| Home, welcome lede            | `auberge`               |
| Plat du jour card (home, carte) | `cloche`              |
| Restaurant intro              | `table`                 |
| La carte intro                | `carte`                 |
| Hôtel intro                   | `room`                  |
| Contact intro                 | `map`                   |

`public/images/generated/` holds an older, filled-gradient set (library collection
`auberge-lamplit`) in a different manner. Nothing references it; do not wire it in.
