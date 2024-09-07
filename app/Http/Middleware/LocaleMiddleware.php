<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;

class LocaleMiddleware
{
    private static string $defaultLang = 'fr';
    private static array $languages = ['en', 'fr'];

    public function handle($request, Closure $next)
    {
        $locale = self::getLocale();

        if ($locale) app()->setLocale($locale);
        else app()->setLocale(self::$defaultLang);

        return $next($request);
    }

    public static function getLocale(): string|null
    {
        $path = Request::path();
        $segments = explode('/', $path);

        if (!empty($segments[0]) && in_array($segments[0], self::$languages)) {
            return $segments[0];
        }

        return null;
    }

}
