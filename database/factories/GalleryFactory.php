<?php

namespace Database\Factories;

use App\Models\Gallery;
use App\Models\GalleryItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\Gallery as GalleryEnum;

/**
 * @extends Factory<Gallery>
 */
class GalleryFactory extends Factory
{
    protected $model = Gallery::class;

    public function definition(): array
    {
        return [];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Gallery $gallery) {
            $name = $gallery->name instanceof GalleryEnum
                ? $gallery->name->value
                : $gallery->name;

            // Every photograph in the gallery's own set, once each, in order:
            // a seeded restaurant page then shows the restaurant rather than a
            // random ten-of-nineteen with three of them repeated.
            $photos = GalleryItemFactory::SETS[$name] ?? [];

            foreach ($photos as $photo) {
                GalleryItem::factory()
                    ->for($gallery)
                    ->create(GalleryItemFactory::shape($photo));
            }
        });
    }

    public function delicacies(): self
    {
        return $this->state(function () {
            return [
                'name' => GalleryEnum::DELICACIES,
            ];
        });
    }

    public function restaurant(): self
    {
        return $this->state(function () {
            return [
                'name' => GalleryEnum::RESTAURANT,
            ];
        });
    }

    public function hotel(): self
    {
        return $this->state(function () {
            return [
                'name' => GalleryEnum::HOTEL,
            ];
        });
    }


}
