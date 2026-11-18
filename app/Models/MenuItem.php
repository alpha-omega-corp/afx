<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Database\Factories\MenuItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class MenuItem extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'price',
        'menu_section_id',
        'daily',
        'daily_position',
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

    protected function casts(): array
    {
        return [
            'daily' => 'boolean',
            'daily_position' => 'integer',
        ];
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

    /** The dishes on today's card: every dish carrying the flag. */
    public function scopeDaily(Builder $query): void
    {
        $query->where('daily', true);
    }

    /**
     * The card of the day, in the order it is eaten.
     *
     * Position first, id second: a card written before the column existed —
     * or two dishes filed at the same position by a hand-edited row — still
     * comes back in a stable order rather than whatever the database felt
     * like returning.
     *
     * @return Collection<int, self>
     */
    public static function specials(): Collection
    {
        return static::daily()
            ->orderByRaw('daily_position IS NULL, daily_position')
            ->orderBy('id')
            ->get();
    }
}
