<?php

namespace Database\Factories;

use App\Models\PageLocale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageLocale>
 */
class PageLocaleFactory extends Factory
{
    protected $model = PageLocale::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'content' => $this->faker->paragraph,
        ];
    }
}
