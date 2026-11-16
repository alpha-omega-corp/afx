<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMenuRequest;
use App\Http\Requests\DeleteMenuItemRequest;
use App\Http\Requests\SortMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Support\Translations;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class MenuController extends Controller
{
    public function create(CreateMenuRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $section = MenuSection::create([
            'position' => (int) MenuSection::max('position') + 1,
        ]);

        Translations::write($section, [
            'fr' => ['title' => $data['title_fr']],
            'en' => ['title' => $data['title_en'] ?? null],
        ]);

        return redirect()->back();
    }

    public function update(UpdateMenuRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $section = MenuSection::findOrFail($data['section_id']);

        Translations::write($section, [
            'fr' => ['title' => $data['title_fr']],
            'en' => ['title' => $data['title_en'] ?? null],
        ]);

        foreach ($request->dishes() as $dish) {
            $item = $dish['id'] !== null
                ? MenuItem::find($dish['id'])
                : new MenuItem();

            // The row carried an id that is no longer there. The rest of the
            // section is still worth saving.
            if (! $item) {
                continue;
            }

            $item->menu_section_id = $section->id;
            $item->price = $dish['price'];
            $item->save();

            // A null 'en' is dropped rather than passed: Translations reads a
            // missing language as "not managed here", and a present-but-empty
            // one as "translate this for me".
            Translations::write($item, array_filter([
                'fr' => $dish['fr'],
                'en' => $dish['en'],
            ], fn ($given) => $given !== null));
        }

        return redirect()->back();
    }

    public function remove(DeleteMenuItemRequest $request): JsonResponse
    {
        $data = $request->validated();

        MenuItem::whereIn('id', $data['items'])->get()->each->delete();

        return response()->json([
            'message' => $data
        ]);
    }

    public function destroy(MenuSection $section)
    {
        // One at a time rather than a mass delete: the locale rows hang off
        // the items by a cascading foreign key, and a query builder delete
        // would skip the models that own them.
        $section->items->each->delete();
        $section->delete();

        return redirect()->back();
    }

    public function sort(SortMenuRequest $request): JsonResponse
    {
        $data = $request->validated();

        $section = MenuSection::find($data['id']);
        $previousPosition = $section->position;

        MenuSection::where('position', $data['position'])->update([
            'position' => $previousPosition,
        ]);

        $section->update(['position' => $data['position']]);

        return response()->json([
            'message' => $data
        ]);
    }
}
