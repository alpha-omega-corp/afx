<?php

namespace App\Models;

use App\Models\Scopes\LocaleScope;
use Database\Factories\PageLocaleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageLocale extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
    ];

    public static function booted(): void
    {
        static::addGlobalScope(new LocaleScope());
    }

    protected static function newFactory(): PageLocaleFactory
    {
        return PageLocaleFactory::new();
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
