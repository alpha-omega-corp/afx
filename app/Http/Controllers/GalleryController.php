<?php

namespace App\Http\Controllers;

use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use App\Models\GalleryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class GalleryController extends Controller
{
    public function store(Gallery $gallery, GalleryRequest $request): RedirectResponse
    {
        $request->validated();

        foreach ($request->file('items') as $item) {
            $path = $item->store('public/app');

            GalleryItem::create([
                'image' => str_replace('public', 'storage', $path),
                'gallery_id' => $gallery->id,
            ]);
        }

        return Redirect::back();
    }

    public function destroy(GalleryRequest $request): JsonResponse
    {
        $data = $request->validated();

        foreach ($data['items'] as $id) {
            GalleryItem::find($id)->delete();
        }

        return response()->json([
            'message' => $data,
        ]);
    }
}
