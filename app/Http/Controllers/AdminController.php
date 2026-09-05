<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Gallery;
use App\Models\MenuSection;
use App\Models\Page;
use Illuminate\View\View;
use App\Enums\Page as PageEnum;
use App\Enums\Gallery as GalleryEnum;

class AdminController extends Controller
{
    /** Every page header is edited from here, rather than one per screen. */
    public function pages(): View
    {
        return view('app.admin.pages', [
            'pages' => Page::whereIn('name', [
                PageEnum::HOME,
                PageEnum::MENU,
                PageEnum::RESTAURANT,
                PageEnum::HOTEL,
                PageEnum::CONTACT,
            ])->get(),
        ]);
    }

    /**
     * The three galleries are one job done three times, so they are one
     * screen with a switch rather than three entries in the rail.
     */
    public function gallery(?string $name = null): View
    {
        $selected = GalleryEnum::tryFrom((string) $name) ?? GalleryEnum::DELICACIES;

        return view('app.admin.gallery', [
            'selected' => $selected,
            'gallery' => Gallery::where('name', $selected)->firstOrFail(),
        ]);
    }

    public function menu(): View
    {
        return view('app.admin.menu', [
            'sections' => MenuSection::orderBy('position')->get(),
        ]);
    }

    public function contact(): View
    {
        return view('app.admin.contact', [
            'messages' => Contact::all()->reverse(),
        ]);
    }
}
