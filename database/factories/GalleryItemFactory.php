<?php

namespace Database\Factories;

use App\Enums\Gallery as GalleryEnum;
use App\Models\GalleryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryItem>
 */
class GalleryItemFactory extends Factory
{
    /**
     * The auberge's own photographs, filed by the gallery they belong to, so
     * the restaurant page is not seeded with eight photographs of a bedroom.
     *
     * Each entry carries its pixel dimensions: the masonry reserves a box from
     * them before the file arrives, which is what stops the columns
     * rebalancing as the page loads. Shapes are mixed on purpose inside every
     * set, so the layout is exercised.
     *
     * @var array<string, array<int, array{0: string, 1: int, 2: int}>>
     */
    public const SETS = [
        GalleryEnum::RESTAURANT->value => [
            ['mock/gallery/restaurant/01.jpg', 1024, 1365],  // the dining room laid for service
            ['mock/gallery/restaurant/02.jpg', 1024, 1365],  // the bar and the chef's blackboard
            ['mock/gallery/restaurant/03.jpg', 1024, 1365],  // the entrance under the marquee
            ['mock/gallery/restaurant/04.jpg', 1024, 1365],  // the terrace under the parasols
            ['mock/gallery/restaurant/05.jpg', 1024, 1365],  // the covered terrace
            ['mock/gallery/restaurant/06.jpg', 1024, 768],   // the house at dusk, the lake behind
            ['mock/gallery/restaurant/07.jpg', 576, 768],    // the house at sunset
        ],
        GalleryEnum::HOTEL->value => [
            ['mock/gallery/hotel/01.jpg', 1024, 768],
            ['mock/gallery/hotel/02.jpg', 1024, 768],
            ['mock/gallery/hotel/03.jpg', 1024, 768],
            ['mock/gallery/hotel/04.jpg', 1024, 765],
            ['mock/gallery/hotel/05.jpg', 1024, 765],
            ['mock/gallery/hotel/06.jpg', 1024, 768],
            ['mock/gallery/hotel/07.jpg', 1024, 765],
            ['mock/gallery/hotel/08.jpg', 576, 768],
        ],
        GalleryEnum::DELICACIES->value => [
            ['mock/gallery/delicacies/01.jpg', 1024, 1365],  // souris d'agneau confite
            ['mock/gallery/delicacies/02.jpg', 1024, 1365],  // tartare de bœuf
            ['mock/gallery/delicacies/03.jpg', 1024, 1365],  // salade du chef
            ['mock/gallery/delicacies/04.jpg', 1024, 1365],  // spaghetti carbonara
        ],
    ];

    protected $model = GalleryItem::class;

    public function definition(): array
    {
        return self::shape(
            $this->faker->randomElement(array_merge(...array_values(self::SETS)))
        );
    }

    /**
     * One photograph from a set, by position. The gallery factory walks its
     * own set in order rather than drawing at random, so no gallery is seeded
     * with the same photograph twice.
     */
    public static function shape(array $photo): array
    {
        [$path, $width, $height] = $photo;

        return [
            'image' => "storage/$path",
            'width' => $width,
            'height' => $height,
        ];
    }
}
