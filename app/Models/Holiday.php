<?php

namespace App\Models;

use App\Interfaces\ILocale;
use App\Traits\HasLocaleScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * A period during which the auberge is closed. Dates are inclusive on
 * both ends: a one-day closure starts and ends on the same date.
 */
class Holiday extends Model implements ILocale
{
    use HasLocaleScope;

    protected $fillable = [
        'starts_on',
        'ends_on',
    ];

    protected $casts = [
        'starts_on' => 'date',
        'ends_on' => 'date',
    ];

    public function locale(): HasOne
    {
        return $this->hasOne(HolidayLocale::class);
    }

    /** Periods covering the given day, today by default. */
    public function scopeCovering(Builder $query, ?string $day = null): void
    {
        $day ??= now()->toDateString();

        $query->whereDate('starts_on', '<=', $day)
            ->whereDate('ends_on', '>=', $day);
    }

    public function scopeUpcoming(Builder $query): void
    {
        $query->whereDate('starts_on', '>', now()->toDateString());
    }

    public function isCurrent(): bool
    {
        return $this->starts_on->startOfDay()->lte(now())
            && $this->ends_on->endOfDay()->gte(now());
    }

    public function isPast(): bool
    {
        return $this->ends_on->endOfDay()->lt(now());
    }
}
