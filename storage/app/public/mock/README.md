# Seed photography

These are the auberge's own photographs, pulled from the published site
(`aubergedefounex.ch`) so that a freshly seeded database looks like the real
house rather than like stock. They are development seed data: production
photographs are uploaded through the admin (Pages, or Gallery) and live under
`storage/app/public/app/`.

## Page headers

| File             | Shows                                                | Used by      |
|------------------|------------------------------------------------------|--------------|
| `hero-bg.jpg`    | The auberge at dusk, the lake and the Alps behind it  | any page     |
| `restaurant.jpg` | The dining room laid for service                      | Restaurant   |
| `carte.jpg`      | Souris d'agneau confite au Pinot Noir de Founex       | La carte     |
| `hotel.jpg`      | A room upstairs, garden side                          | Hôtel        |
| `afx-dummy.jpg`  | Pre-existing stand-in, kept for older fixtures        | —            |

## Galleries

One directory per gallery, so the restaurant page is not seeded with eight
photographs of a bedroom. `GalleryItemFactory::SETS` lists each file with the
pixel dimensions the masonry needs to reserve its box before the file arrives;
update that constant if a file here is added, removed or replaced.

| Directory     | Shows                                                        |
|---------------|--------------------------------------------------------------|
| `restaurant/` | The dining room, the bar, the entrance, the terraces, the house |
| `hotel/`      | The rooms upstairs and their bathrooms                        |
| `delicacies/` | The plates: lamb shank, tartare, salade du chef, carbonara    |
