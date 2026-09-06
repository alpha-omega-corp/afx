<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

/**
 * The days of the week the auberge does not serve.
 *
 * One list, read by everything that has to know: the plat du jour rolls its
 * date forward past these days, and the card says which they are. Holidays
 * and the manual switch are a different question and live in SiteStatus —
 * those close the auberge on days it would otherwise be open.
 *
 * NOTE: these are the days the site was asked to treat as closed. The hours
 * printed in the footer and on the contact page say something else — "Mardi
 * au samedi" and "Fermé le dimanche et le lundi" — and those strings are the
 * auberge's own published copy, so they have been left alone. Whichever is
 * right, this constant is the only place the behaviour has to change.
 */
class Opening
{
    public const CLOSED = [Carbon::SATURDAY, Carbon::SUNDAY];

    public static function isClosedOn(CarbonInterface $date): bool
    {
        return in_array($date->dayOfWeek, self::CLOSED, true);
    }

    public static function closedToday(): bool
    {
        return self::isClosedOn(Carbon::today());
    }

    /**
     * The given day, or the first serving day after it.
     *
     * The loop is bounded by the week: if every day were closed there would
     * be no next open day to find, and a site that hangs is worse than one
     * that shows a date nobody can eat on.
     */
    public static function nextOpen(CarbonInterface $date): CarbonInterface
    {
        $day = $date->copy();

        for ($i = 0; $i < 7 && self::isClosedOn($day); $i++) {
            $day = $day->addDay();
        }

        return $day;
    }

    /** "le samedi et le dimanche", "Saturdays and Sundays" — in the visitor's language. */
    public static function closedDays(): string
    {
        // Sunday is day 0, so the Sunday of the current week indexes the rest.
        $week = Carbon::today()->startOfWeek(Carbon::SUNDAY);

        $names = collect(self::CLOSED)
            ->map(fn (int $day): string => __('app.day_prefix')
                . $week->copy()->addDays($day)->isoFormat('dddd')
                . __('app.day_suffix'))
            ->all();

        $last = array_pop($names);

        return $names === []
            ? $last
            : implode(', ', $names) . ' ' . __('app.and') . ' ' . $last;
    }
}
