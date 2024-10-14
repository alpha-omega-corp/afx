<?php

namespace Database\Factories;

use App\Enums\Language;
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
            'image' => 'storage/hero-bg.jpg',
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Page $page) {
           PageLocale::factory()
               ->for($page)
               ->count(2)
               ->sequence(fn(Sequence $sequence) => [
                   'lang' => $sequence->index == 0
                        ? Language::FR
                        : Language::EN
               ])->create();

        });
    }

    public function home(): self
    {
        return $this->state([
            'name' => \App\Enums\Page::HOME,
        ]);
    }

    public function about(): self
    {
        return $this->state([
            'name' => \App\Enums\Page::ABOUT,
        ]);
    }

    public function menu(): self
    {
        return $this->state([
            'name' => \App\Enums\Page::MENU,
        ]);
    }

    public function restaurant(): self
    {
        return $this->state([
            'name' => \App\Enums\Page::RESTAURANT,
        ]);
    }

    public function hotel(): self
    {
        return $this->state([
            'name' => \App\Enums\Page::HOTEL,
        ]);
    }

    public function contact(): self
    {
        return $this->state([
            'name' => \App\Enums\Page::CONTACT,
        ]);
    }
}
