<?php

namespace App\Models;

use Database\Factories\GalleryItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryItem extends Model
{
    use HasFactory;

    /** The shape a photograph falls back to when it has never been measured. */
    public const FALLBACK_RATIO = '3 / 2';

    protected $fillable = [
        'image',
        'width',
        'height',
        'gallery_id',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
    ];

    protected static function newFactory(): GalleryItemFactory
    {
        return GalleryItemFactory::new();
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function isMeasured(): bool
    {
        return $this->width > 0 && $this->height > 0;
    }

    /** A CSS `aspect-ratio` value, so the grid reserves the right box up front. */
    public function ratio(): string
    {
        return $this->isMeasured()
            ? "{$this->width} / {$this->height}"
            : self::FALLBACK_RATIO;
    }
}
