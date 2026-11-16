<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Database\Factories\MenuItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'price',
        'menu_section_id',
    ];

    /** The name and the line under it live in menu_item_locales. */
    public static function localeModel(): string
    {
        return MenuItemLocale::class;
    }

    /** @return array<int, string> */
    public static function translatable(): array
    {
        return ['title', 'description'];
    }

    protected static function newFactory(): MenuItemFactory
    {
        return MenuItemFactory::new();
    }

    /**
     * The foreign key is named, not inferred. Laravel would derive it from
     * the method — `section_id` — and the column is `menu_section_id`, so the
     * relation silently resolved to null for every dish. MenuSection::items()
     * names its key for the same reason.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(MenuSection::class, 'menu_section_id');
    }
}
