<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Support\Translations;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MenuItem>
 */
class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition(): array
    {
        return [
            'price' => $this->faker->randomFloat(2, 12, 50),
        ];
    }

    /** The name and its line live in locale rows, written after the insert. */
    public function configure(): self
    {
        return $this->afterCreating(function (MenuItem $item) {
            Translations::write($item, ['fr' => [
                'title' => $this->faker->words(4, true),
                'description' => $this->faker->sentence,
            ]]);
        });
    }
}
