<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\Page;
use App\Enums\Page as PageEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Enums\Gallery as GalleryEnum;

class GuestController extends Controller
{
    public function index(): View
    {
        return view('app.home', [
            'page' => Page::where('name', PageEnum::HOME)->first(),
            'specials' => MenuItem::currentSpecials(),
            // Only the editor needs the list of sections to file the special
            // under; a guest never sees that field, so never pays for it.
            // `locales`, not `locale`: the editor names each section in the
            // source language whichever one the page is being read in, and
            // without them that is a query per section per staff page view.
            'sections' => Auth::check()
                ? MenuSection::with('locales')->orderBy('position')->get()
                : collect(),
            'gallery' => Gallery::where('name', GalleryEnum::DELICACIES)->first(),
            'doors' => Page::whereIn('name', [
                PageEnum::RESTAURANT,
                PageEnum::MENU,
                PageEnum::HOTEL,
            ])->get()->keyBy('name'),
        ]);
    }

    public function menu(): View
    {
        // `locale` is the row for the language being browsed; loading it here
        // keeps a nine-section carte to three queries rather than to one per
        // heading and one per dish. The plat du jour is no card of its own
        // here: its dishes sit in their sections like any other.
        return view('app.menu', [
            'page' => Page::where('name', PageEnum::MENU)->first(),
            'carte' => MenuSection::with(['locale', 'items.locale'])
                ->has('items')
                ->orderBy('position')
                ->get(),
        ]);
    }

    public function restaurant(): View
    {
        return view('app.restaurant', [
            'page' => Page::where('name', PageEnum::RESTAURANT)->first(),
            'gallery' => Gallery::where('name', GalleryEnum::RESTAURANT)->first(),
        ]);
    }

    public function hotel(): View
    {
        return view('app.hotel', [
            'page' => Page::where('name', PageEnum::HOTEL)->first(),
            'gallery' => Gallery::where('name', GalleryEnum::HOTEL)->first(),
        ]);
    }

    public function contact(): View
    {
        return view('app.contact', [
            'page' => Page::where('name', PageEnum::CONTACT)->first(),
        ]);
    }
}
