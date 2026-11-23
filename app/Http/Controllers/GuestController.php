<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\Page;
use App\Enums\Page as PageEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\View;
use App\Enums\Gallery as GalleryEnum;

class GuestController extends Controller
{
    public function index(): View
    {
        return view('app.home', [
            'page' => Page::where('name', PageEnum::HOME)->first(),
            // The card on show today: outside the days staff gave it, it is
            // off the page for everyone. The editor still opens on the whole
            // card, so staff can re-date it rather than write it again.
            'specials' => MenuItem::onShow(),
            'editing' => Auth::check() ? MenuItem::specials() : collect(),
            // Only the editor needs the list of sections to file the special
            // under; a guest never sees that field, so never pays for it.
            // `locales`, not `locale`: the editor names each section in the
            // source language whichever one the page is being read in, and
            // without them that is a query per section per staff page view.
            'sections' => Auth::check()
                ? MenuSection::with('locales')->orderBy('position')->get()
                : collect(),
            'strip' => $this->welcomeStrip(),
            'doors' => Page::whereIn('name', [
                PageEnum::RESTAURANT,
                PageEnum::MENU,
                PageEnum::HOTEL,
            ])->get()->keyBy('name'),
        ]);
    }

    /**
     * The photographs under « Bienvenue »: the house first, then the plates.
     * On a developer's machine the strip shows the generated art-direction
     * images instead of the auberge's own photographs, so screenshots and
     * demos taken locally never pass the real premises around.
     *
     * @return Collection<int, array{src: string, alt: string}>
     */
    private function welcomeStrip(): Collection
    {
        if (app()->isLocal()) {
            return collect(['hero-bg', 'restaurant', 'carte', 'hotel'])
                ->map(fn (string $name) => ['src' => asset("storage/mock/{$name}.jpg"), 'alt' => '']);
        }

        $photos = Gallery::where('name', GalleryEnum::DELICACIES)->first()?->items ?? collect();

        return collect([['src' => Vite::image('afx-building.jpg'), 'alt' => __('app.title')]])
            ->concat($photos->map(fn ($item) => ['src' => asset($item->image), 'alt' => '']));
    }

    public function menu(): View
    {
        // `locale` is the row for the language being browsed; loading it here
        // keeps a nine-section carte to three queries rather than to one per
        // heading and one per dish. The plats du jour are the home page's
        // alone: the carte is what is always on it, so a dish on the card of
        // the day is left out of its section, and a section with nothing else
        // in it drops out rather than printing a heading over a hole.
        $carte = fn ($query) => $query->where('daily', false);

        return view('app.menu', [
            'page' => Page::where('name', PageEnum::MENU)->first(),
            'carte' => MenuSection::with(['locale', 'items' => fn ($query) => $carte($query)->with('locale')])
                ->whereHas('items', $carte)
                ->orderBy('position')
                ->get(),
        ]);
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
