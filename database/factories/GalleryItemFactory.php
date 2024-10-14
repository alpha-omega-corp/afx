<?php

namespace Database\Factories;

use App\Models\GalleryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryItem>
 */
class GalleryItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'image' => 'storage/hero-bg.jpg',
        ];
    }
}
