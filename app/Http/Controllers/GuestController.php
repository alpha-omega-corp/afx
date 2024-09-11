<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Enums\Page as PageEnum;
use Illuminate\View\View;

class GuestController extends Controller
{
    public function index(): View
    {
        return view('app.home', [
            'page' => Page::where('name', PageEnum::HOME)->first(),
        ]);
    }

    public function about(): View
    {
        return view('app.about');
    }

    public function menu(): View
    {
        return view('app.menu');
    }

    public function restaurant(): View
    {
        return view('app.restaurant');
    }

    public function hotel(): View
    {
        return view('app.hotel');
    }

    public function contact(): View
    {
        return view('app.contact');
    }
}
