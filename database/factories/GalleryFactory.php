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
            GalleryItem::factory()
                ->for($gallery)
                ->count(10)
                ->create();
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
