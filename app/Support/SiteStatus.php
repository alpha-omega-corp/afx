<?php

namespace App\Support;

use App\Enums\Setting as SettingKey;
use App\Models\Holiday;
use App\Models\Setting;

/**
 * Answers one question for the whole site: is the auberge open right now?
 *
 * Two things can close it — the manual switch in the admin, and the holiday
 * calendar. The manual switch wins, because it exists precisely for the days
 * the calendar did not foresee.
 */
class SiteStatus
{
    private bool $resolved = false;
    private bool $closedManually = false;
    private ?Holiday $holiday = null;

    public function isClosed(): bool
    {
        $this->resolve();

        return $this->closedManually || $this->holiday !== null;
    }

    public function isOpen(): bool
    {
        return ! $this->isClosed();
    }

    public function closedManually(): bool
    {
        $this->resolve();

        return $this->closedManually;
    }

    /** The holiday closing the auberge today, if the calendar is the reason. */
    public function holiday(): ?Holiday
    {
        $this->resolve();

        return $this->holiday;
    }

    /**
     * The reason to show a guest: the period's own note when it has one,
     * otherwise nothing — the banner falls back to a generic message.
     */
    public function reason(): ?string
    {
        return $this->holiday()?->locale?->reason;
    }

    /** The day the auberge reopens, when that day is known. */
    public function reopensOn(): ?\Illuminate\Support\Carbon
    {
        $holiday = $this->holiday();

        if ($this->closedManually() || ! $holiday) {
            return null;
        }

        return $holiday->ends_on->copy()->addDay();
    }

    private function resolve(): void
    {
        if ($this->resolved) {
            return;
        }

        $this->resolved = true;
        $this->closedManually = Setting::bool(SettingKey::SITE_CLOSED);

        // Same reasoning as the settings table: no calendar means no banner,
        // never a broken page.
        $this->holiday = OptionalTable::read(
            fn () => Holiday::covering()->with('locale')->orderBy('ends_on', 'desc')->first(),
            null,
        );
    }
}
