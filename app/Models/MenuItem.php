<?php

namespace App\Models;

use App\Support\Opening;
use App\Traits\HasLocaleScope;
use Carbon\CarbonInterface;
use Database\Factories\MenuItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use HasFactory;
    use HasLocaleScope;

    protected $fillable = [
        'title',
        'description',
        'price',
        'menu_section_id',
        'daily',
        'daily_on',
    ];

    protected function casts(): array
    {
        return [
            'daily' => 'boolean',
            'daily_on' => 'date',
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

    /** Today's special: at most one dish carries the flag. */
    public function scopeDaily(Builder $query): void
    {
        $query->where('daily', true);
    }

    /**
     * The dish on show today, or null.
     *
     * Ordered rather than left to the database: the writer enforces one flag
     * at a time, but if a second ever appeared — a hand-edited row, a restore
     * — an unordered read would pick one of them at random, and the page and
     * the editor could then be looking at different dishes.
     */
    public static function special(): ?self
    {
        return static::daily()->latest('id')->first();
    }
}
