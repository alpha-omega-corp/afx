<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\MenuSection;
use App\Models\Page;
use App\Enums\Page as PageEnum;
use Illuminate\View\View;
use App\Enums\Gallery as GalleryEnum;

class GuestController extends Controller
{
    public function index(): View
    {
        return view('app.home', [
            'page' => Page::where('name', PageEnum::HOME)->first(),
            'gallery' => Gallery::where('name', GalleryEnum::DELICACIES)->first(),
        ]);
    }

    public function menu(): View
    {
        return view('app.menu', [
            'page' => Page::where('name', PageEnum::MENU)->first(),
            'sections' => MenuSection::all(),
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
