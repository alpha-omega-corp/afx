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
            Str::contains($target, ['a-propos-de-nous', 'about-us']) => route(__('route.about')),

            default => route(__('route.home')),
        };


        return redirect($route);
    }
}
