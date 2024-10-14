<?php

namespace App\Http\Controllers;

use App\Enums\Language;
use App\Http\Requests\PageRequest;
use App\Interfaces\IFileService;
use App\Models\Page;
use App\Services\FileService;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Redirect;

class PageController extends Controller
{
    public function update(Page $page, PageRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public');

            $page->update([
                'image' => str_replace('public', 'storage', $path),
            ]);
        }

        $page->ofLang(Language::FR)->first()->locale->update([
            'title' => $data['title_fr'],
            'content' => $data['content_fr'],
        ]);

        $page->ofLang(Language::EN)->first()->locale->update([
            'title' => $data['title_en'],
            'content' => $data['content_en'],
        ]);

        return Redirect::back();
    }
}
