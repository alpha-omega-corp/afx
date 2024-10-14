<?php

namespace Database\Factories;

use App\Models\MenuItem;
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
            'title' => $this->faker->words(4, true),
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 12, 50),
        ];
    }
}
