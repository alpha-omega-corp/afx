<?php

namespace Database\Factories;

use App\Enums\Locale;
use App\Models\Page;
use App\Models\PageLocale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        return [
            'image' => 'storage/afx-dummy.jpg',
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Page $page) {
           PageLocale::factory()
               ->for($page)
               ->count(2)
               ->sequence(fn(Sequence $sequence) => [
                   'locale' => $sequence->index == 0
                        ? Locale::FR
                        : Locale::EN
               ])->create();

        });
    }
}
