<?php

namespace Database\Factories;

use App\Models\MenuSection;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MenuSection>
 */
class MenuSectionFactory extends Factory
{
    protected $model = MenuSection::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (MenuSection $section) {
            MenuItem::factory()
                ->count(5)
                ->create([
                    'menu_section_id' => $section->id,
                ]);
        });
    }
}
