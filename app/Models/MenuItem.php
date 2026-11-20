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
use Illuminate\Support\Carbon;
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
        'daily_until',
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
            'daily_until' => 'date',
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

    /**
     * The card as the home page shows it: the dishes whose days include today.
     *
     * Staff write the card with a first and a last day (both optional). The
     * card leaves the page by itself once the last one has gone by, so nobody
     * — guest or staff — reads last month's dish as today's (ticket #3). The
     * editor still opens on the whole card: see specials().
     *
     * @return Collection<int, self>
     */
    public static function onShow(?CarbonInterface $day = null): Collection
    {
        $day ??= self::today();

        return static::specials()->filter->isShownOn($day)->values();
    }

    /** Whether the card's days include this one. A bound left empty is no bound. */
    public function isShownOn(CarbonInterface $day): bool
    {
        $day = $day->toDateString();

        return ($this->daily_on === null || $this->daily_on->toDateString() <= $day)
            && ($this->daily_until === null || $this->daily_until->toDateString() >= $day);
    }

    /**
     * Where the card stands today: on show, still to come, over, or with no
     * dates at all (up until the box is cleared).
     *
     * @return 'current'|'upcoming'|'ended'|'always'
     */
    public function dailyState(?CarbonInterface $day = null): string
    {
        $day = ($day ?? self::today())->toDateString();

        return match (true) {
            $this->daily_on === null && $this->daily_until === null => 'always',
            $this->daily_on !== null && $this->daily_on->toDateString() > $day => 'upcoming',
            $this->daily_until !== null && $this->daily_until->toDateString() < $day => 'ended',
            default => 'current',
        };
    }

    /**
     * Today on the auberge's clock. The app keeps UTC, so without this a card
     * ending on Friday would stay up until 1 or 2 in the morning on Saturday
     * and would go up an hour or two late on its first day.
     */
    public static function today(): CarbonInterface
    {
        return Carbon::now(Opening::TIMEZONE)->startOfDay();
    }
}
