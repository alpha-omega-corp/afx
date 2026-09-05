<?php

namespace App\Http\Controllers;

use App\Enums\Setting as SettingKey;
use App\Models\Holiday;
use App\Models\Setting;
use App\Support\SiteStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OpeningController extends Controller
{
    public function index(SiteStatus $status): View
    {
        return view('app.admin.opening', [
            'status' => $status,
            'holidays' => Holiday::with('locale')->orderBy('starts_on')->get(),
        ]);
    }

    /** Flips the manual switch; the holiday calendar is left untouched. */
    public function toggle(): RedirectResponse
    {
        Setting::put(SettingKey::SITE_CLOSED, ! Setting::bool(SettingKey::SITE_CLOSED));

        return redirect()->back();
    }
}
