<?php

namespace App\Http\Controllers;

use App\Enums\Language;
use App\Http\Requests\HolidayRequest;
use App\Models\Holiday;
use App\Models\HolidayLocale;
use Illuminate\Http\RedirectResponse;

class HolidayController extends Controller
{
    public function store(HolidayRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $holiday = Holiday::create([
            'starts_on' => $data['starts_on'],
            'ends_on' => $data['ends_on'],
        ]);

        $this->writeLocales($holiday, $data);

        return redirect()->back();
    }

    public function update(Holiday $holiday, HolidayRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $holiday->update([
            'starts_on' => $data['starts_on'],
            'ends_on' => $data['ends_on'],
        ]);

        $this->writeLocales($holiday, $data);

        return redirect()->back();
    }

    public function destroy(Holiday $holiday): RedirectResponse
    {
        $holiday->delete();

        return redirect()->back();
    }

    /**
     * The note is optional, so a blank field clears it rather than failing.
     * The locale scope is dropped here: this writes every language, not the
     * one the admin happens to be browsing in.
     */
    private function writeLocales(Holiday $holiday, array $data): void
    {
        foreach (Language::cases() as $lang) {
            HolidayLocale::withoutGlobalScopes()->updateOrCreate(
                ['holiday_id' => $holiday->id, 'lang' => $lang->value],
                ['reason' => $data["reason_{$lang->value}"] ?? null],
            );
        }
    }
}
