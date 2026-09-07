<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\Page;
use Illuminate\Support\Collection;
use App\Enums\Page as PageEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Enums\Gallery as GalleryEnum;

class GuestController extends Controller
{
    public function index(): View
    {
        return view('app.home', [
            'page' => Page::where('name', PageEnum::HOME)->first(),
            'specials' => MenuItem::specials(),
            // Only the editor needs the list of sections to file the special
            // under; a guest never sees that field, so never pays for it.
            'sections' => Auth::check()
                ? MenuSection::with('locale')->orderBy('position')->get()
                : collect(),
            'gallery' => Gallery::where('name', GalleryEnum::DELICACIES)->first(),
            'doors' => Page::whereIn('name', [
                PageEnum::RESTAURANT,
                PageEnum::MENU,
                PageEnum::HOTEL,
            ])->get()->keyBy('name'),
        ]);
    }

    public function menu(): View
    {
        // `locale` is the row for the language being browsed; loading it here
        // keeps a nine-section carte to three queries rather than to one per
        // heading and one per dish.
        $sections = MenuSection::with(['locale', 'items.locale'])
            ->orderBy('position')
            ->get();

        return view('app.menu', [
            'page' => Page::where('name', PageEnum::MENU)->first(),
            // Every section, for the editor's own use: the card of the day
            // files each of its dishes under one, and a section emptied by
            // today's card still has to be offered.
            'sections' => $sections,
            'carte' => $this->carte($sections),
            // The same dishes the home page shows, at the head of the carte
            // they belong to. One record each, two places they are read from.
            'specials' => MenuItem::specials(),
        ]);
    }

    /**
     * The carte as a guest reads it.
     *
     * The dishes on today's card are printed once, in the card at the head of
     * the page, and not a second time inside their own section — the same
     * name twice on one page reads as two dishes. A section left with nothing
     * else in it drops out rather than printing a heading over a hole.
     *
     * @param  \Illuminate\Support\Collection<int, MenuSection>  $sections
     * @return \Illuminate\Support\Collection<int, MenuSection>
     */
    private function carte(Collection $sections): Collection
    {
        return $sections
            ->map(function (MenuSection $section): MenuSection {
                // A copy: the editor's list of sections still holds every dish.
                $copy = clone $section;
                $copy->setRelation('items', $section->items->reject->daily->values());

                return $copy;
            })
            ->filter(fn (MenuSection $section): bool => $section->items->isNotEmpty())
            ->values();
    }

    public function restaurant(): View
    {
        return view('app.restaurant', [
            'page' => Page::where('name', PageEnum::RESTAURANT)->first(),
            'gallery' => Gallery::where('name', GalleryEnum::RESTAURANT)->first(),
        ]);
    }

    public function hotel(): View
    {
        return view('app.hotel', [
            'page' => Page::where('name', PageEnum::HOTEL)->first(),
            'gallery' => Gallery::where('name', GalleryEnum::HOTEL)->first(),
        ]);
    }

    public function contact(): View
    {
        return view('app.contact', [
            'page' => Page::where('name', PageEnum::CONTACT)->first(),
        ]);
    }
}
