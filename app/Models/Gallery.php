<?php

namespace App\Models;

use Database\Factories\GalleryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected static function newFactory(): GalleryFactory
    {
        return GalleryFactory::new();
    }

    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class);
    }
}
