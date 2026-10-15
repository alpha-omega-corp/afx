# Seed photography

Development seed data. Production photographs are uploaded through the admin
(Pages, or Gallery) and live under `storage/app/public/app/`.

Two kinds of file live here, and the difference matters:

- **`original/`** — the auberge's own photographs, pulled from the published
  site (`aubergedefounex.ch`). These show the real house: the real square, the
  real dining room, a real room upstairs, a real plate.
- **everything else** — art-direction imagery, generated (GPT Image 2, 2026-09-09)
  to carry the ember-and-warm-black look the page headers are designed around.
  They are *evocative, not documentary*: they do not show these premises.

## Page headers

The generated headers are dark, low-key and composed with the subject right of
centre, because `x-hero` lays a veil over the image, raises embers off the
bottom edge, and sets the title, lead and buttons into the **lower left**. An
image with detail in that corner loses the type. 2400px wide, progressive JPEG.

| File             | Shows                                                     | Used by    |
|------------------|-----------------------------------------------------------|------------|
| `hero-bg.jpg`    | A lit village inn at blue hour, the lake and Alps behind   | any page   |
| `restaurant.jpg` | A table laid by a lit hearth, panelled wall, candlelight   | Restaurant |
| `carte.jpg`      | A braised lamb shank in a wine reduction, raking firelight | La carte   |
| `hotel.jpg`      | A renovated room at first light, shutters half closed      | Hôtel      |
| `afx-dummy.jpg`  | Pre-existing stand-in, kept for older fixtures             | —          |

### Before you put these in front of guests

The auberge is a real, named business at a real address, and two of these
pages are pages somebody books from. A generated room is not the room a guest
will sleep in, and a generated façade is not this house. Use them as the
look-and-feel placeholder they are; for production, upload the auberge's own
photographs through Administration → Pages. `original/` holds the real ones,
and swapping a file back is enough — `PageFactory` reads these paths by name.

## Galleries

One directory per gallery, so the restaurant page is not seeded with eight
photographs of a bedroom. These are all the auberge's own photographs.
`GalleryItemFactory::SETS` lists each file with the pixel dimensions the
masonry needs to reserve its box before the file arrives; update that constant
if a file here is added, removed or replaced.

| Directory     | Shows                                                           |
|---------------|-----------------------------------------------------------------|
| `restaurant/` | The dining room, the bar, the entrance, the terraces, the house |
| `hotel/`      | The rooms upstairs and their bathrooms                          |
| `delicacies/` | The plates: lamb shank, tartare, salade du chef, carbonara      |
