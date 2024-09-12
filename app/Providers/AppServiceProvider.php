<?php

namespace App\Providers;

use App\Enums\Color;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('Color', Color::class);
    }

    public function boot(): void
    {
        Vite::macro('image', fn(string $file) => $this->asset("resources/images/$file"));
    }
}
