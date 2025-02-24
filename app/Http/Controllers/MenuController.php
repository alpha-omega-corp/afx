<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMenuRequest;
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
