<?php

namespace App\Http\Controllers;

use App\Enums\Setting as SettingKey;
use App\Http\Requests\OpeningHoursRequest;
use App\Models\Holiday;
use App\Models\OpeningHour;
use App\Models\Setting;
use App\Support\Opening;
use App\Support\SiteStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class OpeningController extends Controller
{
    public function index(SiteStatus $status): View
    {
        // Staff are told the truth rather than shown an empty calendar: a
        // missing table means pending migrations, not "no closures planned".
        $ready = Schema::hasTable('settings')
            && Schema::hasTable('holidays')
            && Schema::hasTable('opening_hours');

        return view('app.admin.opening', [
            'ready' => $ready,
            'status' => $status,
            // Always seven days, from the table or from the published week
            // behind it, so the form draws even before the migration runs.
            'hours' => Opening::week(),
            'holidays' => $ready
                ? Holiday::with('locale')->orderBy('starts_on')->get()
                : collect(),
        ]);
    }

    /** Flips the manual switch; the holiday calendar is left untouched. */
    public function toggle(): RedirectResponse
    {
        Setting::put(SettingKey::SITE_CLOSED, ! Setting::bool(SettingKey::SITE_CLOSED));

        return redirect()->back();
    }

    /**
     * Writes the serving week.
     *
     * Seven rows every time, so a day that used to be missing from the table
     * is created rather than left to the fallback. The cache is dropped after
     * the write: the page that redirects here re-reads the week to print it.
     */
    public function hours(OpeningHoursRequest $request): RedirectResponse
    {
        foreach ($request->week() as $day => $values) {
            OpeningHour::updateOrCreate(['day' => $day], $values);
        }

        Opening::flush();

        return redirect()->back();
    }
}
