<?php

namespace App\Http\Controllers;

use App\Enums\Language;
use App\Models\Gallery;
use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\Page;
use Illuminate\View\View;
use App\Enums\Page as PageEnum;
use App\Enums\Gallery as GalleryEnum;

class AdminController extends Controller
{
    /** Every page header is edited from here, rather than one per screen. */
    public function pages(): View
    {
        return view('app.admin.pages', [
            'pages' => Page::whereIn('name', [
                PageEnum::HOME,
                PageEnum::MENU,
                PageEnum::RESTAURANT,
                PageEnum::HOTEL,
                PageEnum::CONTACT,
            ])->get(),
        ]);
    }

    /**
     * The three galleries are one job done three times, so they are one
     * screen with a switch rather than three entries in the rail.
     */
    public function gallery(?string $name = null): View
    {
        $selected = GalleryEnum::tryFrom((string) $name) ?? GalleryEnum::DELICACIES;

        return view('app.admin.gallery', [
            'selected' => $selected,
            'gallery' => Gallery::where('name', $selected)->firstOrFail(),
        ]);
    }

    /**
     * The carte, in both languages at once.
     *
     * `locales` rather than `locale`: the editor is the one place that has to
     * see every language regardless of the one it is being read in, and
     * loading them here keeps the repeater from asking per dish per language.
     */
    public function menu(): View
    {
        $sections = MenuSection::with(['locales', 'items.locales'])
            ->orderBy('position')
            ->get();

        return view('app.admin.menu', [
            'sections' => $sections,
            'rows' => $sections->mapWithKeys(fn (MenuSection $section): array => [
                $section->id => $section->items->map(function (MenuItem $item): array {
                    $fr = $item->localeIn(Language::FR);
                    $en = $item->localeIn(Language::EN);

                    return [
                        'id' => (string) $item->id,
                        'title_fr' => $fr->title,
                        'title_en' => $en->title,
                        'description_fr' => $fr->description,
                        'description_en' => $en->description,
                        // Two decimals in the field, as on the carte itself:
                        // a price stored as 41.5 is written 41.50.
                        'price' => number_format($item->price, 2, '.', ''),
                    ];
                })->values()->all(),
            ])->all(),
        ]);
    }
}
