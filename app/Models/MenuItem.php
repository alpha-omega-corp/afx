<?php

namespace App\Models;

use App\Support\Opening;
use App\Traits\HasTranslations;
use Carbon\CarbonInterface;
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
        'daily_on',
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
            'daily_on' => 'date',
            'daily_position' => 'integer',
        ];
    }

    protected static function newFactory(): MenuItemFactory
    {
        return MenuItemFactory::new();
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(MenuSection::class);
    }

    /**
     * The day the special is actually served.
     *
     * The date is stored as it was written; this is the day a guest can come
     * and eat it. Written on a Saturday, the dish is Monday's — nobody is in
     * the kitchen in between, and a card offering today's special on a day
     * the door is locked is worse than no card.
     */
    public function servedOn(): ?CarbonInterface
    {
        return $this->daily_on ? Opening::nextOpen($this->daily_on) : null;
    }

    /** True when the written date had to move to reach an open day. */
    public function movedFromClosedDay(): bool
    {
        return $this->daily_on !== null && ! $this->daily_on->isSameDay($this->servedOn());
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
