<?php

namespace App\Console\Commands;

use App\Models\GalleryItem;
use Illuminate\Console\Command;

/**
 * Fills in the width and height of photographs uploaded before the gallery
 * started recording them, by reading the files on disk.
 */
class MeasureGalleryItems extends Command
{
    protected $signature = 'gallery:measure {--all : Re-measure every photograph, not only the unmeasured ones}';

    protected $description = 'Record the pixel dimensions of gallery photographs';

    public function handle(): int
    {
        $query = GalleryItem::query()
            ->when(! $this->option('all'), fn ($q) => $q->whereNull('width')->orWhereNull('height'));

        $items = $query->get();

        if ($items->isEmpty()) {
            $this->info('Every photograph is already measured.');

            return self::SUCCESS;
        }

        $measured = 0;
        $missing = [];

        foreach ($items as $item) {
            $path = public_path($item->image);

            if (! is_file($path)) {
                $missing[] = $item->image;
                continue;
            }

            [$width, $height] = getimagesize($path) ?: [null, null];

            if (! $width || ! $height) {
                $missing[] = $item->image;
                continue;
            }

            $item->update(['width' => $width, 'height' => $height]);
            $measured++;
        }

        $this->info("Measured {$measured} photograph(s).");

        foreach (array_unique($missing) as $path) {
            $this->warn("Could not read: {$path}");
        }

        return self::SUCCESS;
    }
}
