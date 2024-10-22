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
    public function home(): View
    {
        return view('app.admin.home', [
            'page' => Page::where('name', PageEnum::HOME)->first(),
            'gallery' => Gallery::where('name', GalleryEnum::DELICACIES)->first(),
        ]);
    }

    public function menu(): View
    {
        return view('app.admin.menu', [
            'page' => Page::where('name', PageEnum::MENU)->first(),
            'sections' => MenuSection::all(),
        ]);
    }

    public function restaurant(): View
    {
        return view('app.admin.restaurant', [
            'page' => Page::where('name', PageEnum::RESTAURANT)->first(),
            'gallery' => Gallery::where('name', GalleryEnum::RESTAURANT)->first(),
        ]);
    }

    public function hotel(): View
    {
        return view('app.admin.hotel', [
            'page' => Page::where('name', PageEnum::HOTEL)->first(),
            'gallery' => Gallery::where('name', GalleryEnum::HOTEL)->first(),
        ]);
    }

    public function contact(): View
    {
        return view('app.admin.contact', [
            'page' => Page::where('name', PageEnum::HOTEL)->first(),
            'messages' => Contact::all()->reverse(),
        ]);
    }
}
