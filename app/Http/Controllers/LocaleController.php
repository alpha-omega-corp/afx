<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class LocaleController extends Controller
{
    public function locale(string $lang)
    {
        App::setLocale($lang);
        $target = Redirect::back()->getTargetUrl();
        $segments = explode('/', $target);

        $route = match (true) {
            Str::contains($target, ['la-carte', 'menu']) => route(__('route.menu')),
            Str::contains($target, ['restaurant', 'restaurant']) => route(__('route.restaurant')),
            Str::contains($target, ['hotel', 'hotel']) => route(__('route.hotel')),
            Str::contains($target, ['contact', 'contact']) => route(__('route.contact')),


            default => route(__('route.home')),
        };


        return redirect($route);
    }
}
