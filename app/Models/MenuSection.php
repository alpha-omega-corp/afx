<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Database\Factories\MenuSectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuSection extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'position',
    ];

    /** The heading lives in menu_section_locales, one row per language. */
    public static function localeModel(): string
    {
        return MenuSectionLocale::class;
    }

    /** @return array<int, string> */
    public static function translatable(): array
    {
        return ['title'];
    }

    protected static function newFactory(): MenuSectionFactory
    {
        return MenuSectionFactory::new();
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_section_id', 'id');
    }
}
