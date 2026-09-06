<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMenuRequest;
use App\Http\Requests\DailySpecialRequest;
use App\Http\Requests\DeleteMenuItemRequest;
use App\Http\Requests\SortMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\MenuItem;
use App\Models\MenuSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
class MenuController extends Controller
{
    public function create(CreateMenuRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $position = MenuSection::all()->sortBy('position')->reverse()->get(0)->position + 1;

        MenuSection::create([
            'title' => $data['title'],
            'position' => $position
        ]);

        return redirect()->back();
    }

    public function update(UpdateMenuRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $section = MenuSection::find($data['section_id']);

        if ($section->title !== $data['title']) {
            $section->update([
                'title' => $data['title'],
            ]);
        }

        if (array_key_exists('ids', $data)) {
            foreach ($data['ids'] as $key => $id) {
                if ($id === null) {
                    MenuItem::create([
                        'menu_section_id' => $section->id,
                        'title' => $data['section_titles'][$key],
                        'price' => $data['section_prices'][$key],
                        'description' => $data['section_descriptions'][$key],
                    ]);
                    continue;
                }

                MenuItem::find($id)->update([
                    'title' => $data['section_titles'][$key],
                    'price' => $data['section_prices'][$key],
                    'description' => $data['section_descriptions'][$key],
                ]);
            }
        }

        return redirect()->back();
    }

    /**
     * Writes today's special, edited from the card on the home page.
     *
     * The special is a dish on the carte carrying a flag rather than a record
     * of its own, so this edits that row and the two views of it cannot drift
     * apart. With no special set, a dish already sitting in that section under
     * that name is promoted rather than copied — otherwise taking the special
     * off the home page and putting it back would leave the carte holding the
     * same dish twice. Only a name new to the section creates a row.
     */
    public function daily(DailySpecialRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $item = MenuItem::special() ?? MenuItem::firstOrNew([
            'menu_section_id' => $data['menu_section_id'],
            'title' => $data['title'],
        ]);

        $item->fill([
            'menu_section_id' => $data['menu_section_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'daily_on' => $data['daily_on'] ?? null,
        ]);

        // Clearing the box takes the dish off the home page and leaves it on
        // the carte. Nothing here deletes anything.
        $item->daily = $request->boolean('daily');
        $item->save();

        if ($item->daily) {
            // One special at a time, whatever the table held before.
            MenuItem::daily()->whereKeyNot($item->getKey())->update(['daily' => false]);
        }

        return redirect()->back();
    }

    public function remove(DeleteMenuItemRequest $request): JsonResponse
    {
        $data = $request->validated();

        foreach ($data['items'] as $id) {
            MenuItem::find($id)->delete();
        }

        return response()->json([
            'message' => $data
        ]);
    }

    public function destroy(MenuSection $section) {
        $section->items()->delete();
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
