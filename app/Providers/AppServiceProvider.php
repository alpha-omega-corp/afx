<?php

namespace App\Providers;

use App\Enums\Action;
use App\Enums\Color;
use App\Enums\Icon;
use App\Enums\Language;
use App\Enums\Modal;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('Color', Color::class);
        $loader->alias('Icon', Icon::class);
        $loader->alias('Modal', Modal::class);
        $loader->alias('Action', Action::class);
        $loader->alias('Lang', Language::class);
    }

    public function boot(): void
    {
        Vite::macro('image', fn(string $file) => $this->asset("resources/images/$file"));
    }
}
