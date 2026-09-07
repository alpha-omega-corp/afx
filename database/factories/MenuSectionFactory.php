<?php

namespace Database\Factories;

use App\Models\MenuSection;
use App\Models\MenuItem;
use App\Support\Translations;
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
            'position' => $this->faker->unique()->numberBetween(0, 99),
        ];
    }

    /** The heading lives in a locale row, so it is written after the insert. */
    public function configure(): self
    {
        return $this->afterCreating(function (MenuSection $section) {
            Translations::write($section, ['fr' => ['title' => $this->faker->words(2, true)]]);

            MenuItem::factory()
                ->count(5)
                ->create([
                    'menu_section_id' => $section->id,
                ]);
        });
    }
}
