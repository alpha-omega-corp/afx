<?php

namespace App\Http\Controllers;

use App\Enums\Setting as SettingKey;
use App\Models\Holiday;
use App\Models\Setting;
use App\Support\OptionalTable;
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
        $ready = Schema::hasTable('settings') && Schema::hasTable('holidays');

        return view('app.admin.opening', [
            'ready' => $ready,
            'status' => $status,
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
}
