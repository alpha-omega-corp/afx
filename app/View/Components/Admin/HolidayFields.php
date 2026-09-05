<?php

namespace App\View\Components\Admin;

use App\Enums\Language;
use App\Models\Holiday;
use App\Models\HolidayLocale;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * The create and edit forms for a closure are the same fields, so they are
 * written once. A null holiday means "new period".
 */
class HolidayFields extends Component
{
    public string $startsOn;
    public string $endsOn;
    public array $reasons;

    public function __construct(
        public ?Holiday $holiday = null,
    ) {
        $this->startsOn = $holiday?->starts_on->toDateString() ?? today()->toDateString();
        $this->endsOn = $holiday?->ends_on->toDateString() ?? today()->toDateString();

        $this->reasons = $this->reasonsFor($holiday);
    }

    /** Reads every language's note, not just the one the admin is browsing in. */
    private function reasonsFor(?Holiday $holiday): array
    {
        if (! $holiday) {
            return array_fill_keys(Language::values(), '');
        }

        $stored = HolidayLocale::withoutGlobalScopes()
            ->where('holiday_id', $holiday->id)
            ->pluck('reason', 'lang')
            ->all();

        return array_map(
            fn (string $lang) => $stored[$lang] ?? '',
            array_combine(Language::values(), Language::values()),
        );
    }

    public function render(): View
    {
        return view('components.admin.holiday-fields');
    }
}
