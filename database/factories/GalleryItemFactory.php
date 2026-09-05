<?php

namespace Database\Factories;

use App\Models\GalleryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryItem>
 */
class GalleryItemFactory extends Factory
{
    /**
     * Seeded galleries deliberately mix shapes — portrait, landscape and
     * square — so the masonry layout is exercised in development.
     */
    public function definition(): array
    {
        $shapes = [
            ['mock/gallery/g1.jpg', 900, 1200],
            ['mock/gallery/g2.jpg', 1200, 800],
            ['mock/gallery/g3.jpg', 1000, 1000],
            ['mock/gallery/g4.jpg', 800, 1200],
            ['mock/gallery/g5.jpg', 1200, 675],
            ['mock/gallery/g6.jpg', 1000, 1250],
            ['mock/gallery/g7.jpg', 1200, 900],
            ['mock/gallery/g8.jpg', 900, 1350],
            ['mock/gallery/g9.jpg', 1200, 1200],
        ];

        [$path, $width, $height] = $this->faker->randomElement($shapes);

        return [
            'image' => "storage/$path",
            'width' => $width,
            'height' => $height,
        ];
    }
}
