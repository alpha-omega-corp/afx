<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMenuRequest;
use App\Http\Requests\DeleteMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\MenuItem;
use App\Models\MenuSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuController extends Controller
{
    public function create(CreateMenuRequest $request): RedirectResponse
    {
        $data = $request->validated();

        MenuSection::create([
            'title' => $data['title'],
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

    public function destroy(DeleteMenuRequest $request): JsonResponse
    {
        $data = $request->validated();

        foreach ($data['items'] as $id) {
            MenuItem::find($id)->delete();
        }

        return response()->json([
            'message' => $data
        ]);
    }
}
