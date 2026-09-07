<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMenuRequest;
use App\Http\Requests\DailySpecialRequest;
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

            Translations::write($item, ['fr' => $dish['fr'], 'en' => $dish['en']]);
        }

        return redirect()->back();
    }

    /**
     * Writes the card of the day, edited from the card itself.
     *
     * The card is a handful of dishes on the carte carrying a flag rather
     * than records of their own, so this edits those rows and the two views
     * of them cannot drift apart. A row arrives with the id of the dish it
     * edits; a row without one is a dish being written for the first time,
     * and a name already sitting in that section is promoted rather than
     * copied — otherwise taking a dish off the card and putting it back would
     * leave the carte holding it twice.
     *
     * Dishes dropped from the card go back to being ordinary dishes on the
     * carte. Nothing here deletes anything.
     *
     * This editor is French-only: it is written in a hurry, most days, and
     * the English follows on its own. Because it sends no English at all,
     * rather than sending it blank, any English a person has corrected on the
     * carte survives being edited from here — see App\Support\Translations.
     */
    public function daily(DailySpecialRequest $request): RedirectResponse
    {
        $show = $request->boolean('daily');
        $date = $request->validated('daily_on');
        $kept = [];

        foreach ($request->dishes() as $position => $dish) {
            $item = $dish['id'] !== null
                ? MenuItem::find($dish['id'])
                : self::dishNamed($dish['menu_section_id'], $dish['fr']['title']);

            if (! $item) {
                continue;
            }

            $item->menu_section_id = $dish['menu_section_id'];
            $item->price = $dish['price'];
            $item->daily_on = $date;

            // Clearing the box takes the whole card off the home page and
            // leaves every dish on the carte.
            $item->daily = $show;
            // A dish that is not on the card holds no place in its order.
            $item->daily_position = $show ? $position : null;
            $item->save();

            Translations::write($item, ['fr' => $dish['fr']]);

            $kept[] = $item->getKey();
        }

        MenuItem::daily()
            ->whereNotIn('id', $kept)
            ->update(['daily' => false, 'daily_position' => null]);

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

    /**
     * A dish already on the carte under this French name, or a fresh one.
     *
     * The name is matched in the source language only: the English is a
     * translation of it, and two dishes are the same dish when the kitchen
     * calls them the same thing.
     */
    private static function dishNamed(int $section, string $title): MenuItem
    {
        $existing = MenuItem::where('menu_section_id', $section)
            ->whereHas('locales', fn ($query) => $query
                ->where('lang', Translations::SOURCE->value)
                ->where('title', $title))
            ->first();

        return $existing ?? new MenuItem(['menu_section_id' => $section]);
    }
}
