<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * One day of the serving week.
 *
 * A day is closed when staff say so, and also when nobody wrote a single
 * service into it — an open day with no hours is not something a guest can
 * act on, and printing it would be worse than leaving it out.
 */
class OpeningHour extends Model
{
    protected $fillable = [
        'day',
        'closed',
        'lunch_from',
        'lunch_to',
        'dinner_from',
        'dinner_to',
    ];

    protected function casts(): array
    {
        return [
            'day' => 'integer',
            'closed' => 'boolean',
        ];
    }

    /** @return array<int, array{0: string, 1: string}> */
    public function services(): array
    {
        if ($this->closed) {
            return [];
        }

        return collect([
            [$this->lunch_from, $this->lunch_to],
            [$this->dinner_from, $this->dinner_to],
        ])
            // Half a range is not a range: a service needs both ends before
            // it can be printed as one.
            ->filter(fn (array $range): bool => filled($range[0]) && filled($range[1]))
            ->values()
            ->all();
    }

    public function isClosed(): bool
    {
        return $this->services() === [];
    }

    /**
     * What makes two days print as one line. Days sharing a signature and
     * sitting next to each other in the week are collapsed into a range.
     */
    public function signature(): string
    {
        return $this->isClosed()
            ? 'closed'
            : json_encode($this->services());
    }

    /** "mardi", "Tuesday" — in the visitor's language. */
    public function name(): string
    {
        // Sunday is day 0, so the Sunday of the current week indexes the rest.
        return Carbon::today()
            ->startOfWeek(Carbon::SUNDAY)
            ->addDays($this->day)
            ->locale(app()->getLocale())
            ->isoFormat('dddd');
    }

    /** "11h30 – 14h00 et 18h00 – 22h00", or null on a closed day. */
    public function serviceLine(): ?string
    {
        $services = $this->services();

        if ($services === []) {
            return null;
        }

        return collect($services)
            ->map(fn (array $range): string => self::time($range[0]) . ' – ' . self::time($range[1]))
            ->join(' ' . __('app.and') . ' ');
    }

    /** "11:30" in the language's own clock: 11h30 in French, 11:30am in English. */
    public static function time(string $value): string
    {
        return Carbon::createFromFormat('H:i', $value)
            ->locale(app()->getLocale())
            ->isoFormat(__('app.time_format'));
    }
}
