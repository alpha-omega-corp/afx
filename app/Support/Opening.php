<?php

namespace App\Support;

use App\Models\OpeningHour;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * The week the auberge serves.
 *
 * One list, read by everything that has to know: the footer and the contact
 * page print it, the plat du jour rolls its date forward past the closed
 * days, and the card says which those are. Staff edit it in Administration →
 * Ouverture, beside the holiday calendar.
 *
 * Holidays and the manual switch are a different question and live in
 * SiteStatus — those close the auberge on days it would otherwise be open.
 */
class Opening
{
    /**
     * The auberge's published week, used until the table exists.
     *
     * Code is deployed before migrations run, and a footer that prints the
     * right hours from a constant is far better than a 500. Kept in step with
     * the migration that seeds the table.
     */
    private const PUBLISHED = [
        0 => null,
        1 => null,
        2 => ['11:30', '14:00', '18:00', '22:00'],
        3 => ['11:30', '14:00', '18:00', '22:00'],
        4 => ['11:30', '14:00', '18:00', '22:00'],
        5 => ['11:30', '14:00', '18:00', '22:00'],
        6 => ['11:30', '14:00', '18:00', '22:00'],
    ];

    /** Monday first: the week is read the way it is printed, not the way it is stored. */
    private const WEEK = [1, 2, 3, 4, 5, 6, 0];

    /** @var Collection<int, OpeningHour>|null */
    private static ?Collection $days = null;

    /**
     * The seven days, keyed by Carbon's day number.
     *
     * Every day is present whether or not the table holds a row for it, so
     * callers never have to ask whether Thursday exists.
     *
     * @return Collection<int, OpeningHour>
     */
    public static function days(): Collection
    {
        // `replace`, never `merge`. Collection::merge is array_merge, which
        // RENUMBERS integer keys instead of overwriting them: seven fallback
        // days keyed 0-6 merged with seven saved days keyed 0-6 gave fourteen
        // entries keyed 0-13, and every lookup by dayOfWeek kept reading the
        // hardcoded week off the front. Editing the hours in the admin saved
        // correctly and changed nothing anywhere. array_replace respects
        // integer keys, so a saved day wins and a missing one falls back.
        return self::$days ??= self::fallback()->replace(
            OptionalTable::read(
                fn () => OpeningHour::all()->keyBy('day'),
                collect(),
            )
        );
    }

    /** The week in the order it is printed: Monday through Sunday. */
    public static function week(): Collection
    {
        $days = self::days();

        return collect(self::WEEK)->map(fn (int $day): OpeningHour => $days[$day])->values();
    }

    /** Dropped after a write, so the same request reads back what it just saved. */
    public static function flush(): void
    {
        self::$days = null;
    }

    public static function isClosedOn(CarbonInterface $date): bool
    {
        return self::days()[$date->dayOfWeek]->isClosed();
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
        $names = self::week()
            ->filter(fn (OpeningHour $day): bool => $day->isClosed())
            ->map(fn (OpeningHour $day): string => __('app.day_prefix') . $day->name() . __('app.day_suffix'))
            ->values()
            ->all();

        if ($names === []) {
            return '';
        }

        $last = array_pop($names);

        return $names === []
            ? $last
            : implode(', ', $names) . ' ' . __('app.and') . ' ' . $last;
    }

    /**
     * The opening hours as they are printed, one line per run of days that
     * serve the same thing: "Mardi au samedi, 11h30 – 14h00 et 18h00 – 22h00",
     * then "Fermé le dimanche et le lundi".
     *
     * @return array<int, string>
     */
    public static function schedule(): array
    {
        $lines = collect(self::runs())
            ->reject(fn (array $run): bool => $run[0]->isClosed())
            ->map(fn (array $run): string => Str::ucfirst(
                self::runLabel($run) . ', ' . $run[0]->serviceLine()
            ))
            ->values()
            ->all();

        $closed = self::closedDays();

        if ($closed !== '') {
            $lines[] = __('footer.hours_closed', ['days' => $closed]);
        }

        return $lines;
    }

    /**
     * The week split into runs of consecutive days that serve alike.
     *
     * @return array<int, array<int, OpeningHour>>
     */
    private static function runs(): array
    {
        $runs = [];

        foreach (self::week() as $day) {
            $last = array_key_last($runs);

            if ($last !== null && end($runs[$last])->signature() === $day->signature()) {
                $runs[$last][] = $day;
                continue;
            }

            $runs[] = [$day];
        }

        return $runs;
    }

    /** "mardi", "mardi et mercredi", "mardi au samedi". */
    private static function runLabel(array $run): string
    {
        $first = head($run)->name();
        $last = end($run)->name();

        return match (count($run)) {
            1 => $first,
            2 => $first . ' ' . __('app.and') . ' ' . $last,
            default => $first . ' ' . __('app.to') . ' ' . $last,
        };
    }

    /** @return Collection<int, OpeningHour> */
    private static function fallback(): Collection
    {
        return collect(self::PUBLISHED)->map(fn (?array $hours, int $day): OpeningHour => new OpeningHour([
            'day' => $day,
            'closed' => $hours === null,
            'lunch_from' => $hours[0] ?? null,
            'lunch_to' => $hours[1] ?? null,
            'dinner_from' => $hours[2] ?? null,
            'dinner_to' => $hours[3] ?? null,
        ]));
    }
}
