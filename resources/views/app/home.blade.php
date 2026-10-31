@extends('layouts.guest')

@section('description', Str::limit(trim(strip_tags($page?->locale?->content ?? '')), 155) ?: __('footer.blurb'))

@section('content')
    {{-- The house itself, framed in the firelight beside the name. The
         image is the home page's own, changed from Administration → Pages
         like every other hero on the site.

         Every reference to the record is optional. A database whose pages
         have not been seeded is a database a member of staff can still log
         into and fix, and the hero already knows how to stand without a
         photograph — it goes bare, on warm black. A missing row is a page
         that reads a little plainer, never a page that will not open. --}}
    <x-page
        :page="$page"
        :title="__('app.title')"
        :lead="$page?->locale?->title"
        :tall="true"
        :embers="true"
        :rule="false"
        :intro="false"
    >
        {{-- Today's dish, named in the hero itself: the first thing a
             returning guest looks for, readable before any scrolling. --}}
        @if($specials->isNotEmpty())
            <x-slot:eyebrow>
                <a href="#plat-du-jour" class="hero-chip">
                    <span class="hero-chip__label"><span class="hero-chip__dot" aria-hidden="true"></span>{{ __('app.daily') }}</span>
                    <span class="hero-chip__dish">{{ $specials->first()->locale?->title }}</span>
                    @if($specials->count() > 1)
                        <span class="hero-chip__more">+{{ $specials->count() - 1 }}</span>
                    @endif
                    @svg('heroicon-o-arrow-down', 'hero-chip__go')
                </a>
            </x-slot:eyebrow>
        @endif

        <x-slot:actions>
            {{-- The number is the one this button opens a conversation with,
                 not the landline: a button that shows one number and dials
                 another is a complaint waiting to happen. --}}
            <a href="https://wa.me/+41786857845" class="btn btn-primary" target="_blank" rel="noopener">
                {{ __('app.reserve') }}
                <span class="btn__aside">078 685 78 45</span>
            </a>
            <a href="{{ route(__('route.menu')) }}" class="btn btn-ghost">
                {{ __('app.see_menu') }}
            </a>
        </x-slot:actions>

        {{-- What the house is known for, asked for in large by the owner
             (ticket #1, client review 4): straight under the hero's buttons,
             where the eye lands after the name, and above today's card so
             the dish of the day reads as part of it.

             Interactive since ticket #2 (client review 4): the stag's antlers
             draw themselves in as the sign arrives, and the hearth's light
             follows the pointer across it. It is a sign, not a control —
             nothing to press, so nothing to focus; without the script it is
             the same sign, standing still. --}}
        <section class="home-chasse" aria-labelledby="home-chasse-title">
            {{-- The reveal on the container, not the sign: the reveal's own
                 transform would otherwise overwrite the sign's lean. --}}
            <div class="container" data-reveal>
                <div class="home-chasse__sign" data-chasse>
                    <x-illustration.cerf class="home-chasse__art"/>
                    <h2 class="home-chasse__title" id="home-chasse-title">{{ __('app.game') }}</h2>
                </div>
            </div>
        </section>

        {{-- The one thing on the page that is different tomorrow. It sits in
             the last of the hero's firelight, above the three doors, because
             it is the reason to come today rather than one day. --}}
        <x-daily :specials="$specials" :sections="$sections"/>

        {{-- The three ways into the house: a table, the carte, a bed.
             This band has no top gap and no seam — the cards climb into the
             hero's firelight, and reveal themselves as they arrive. --}}
        <section class="home-doors">
            <div class="container">
                {{-- `get`, not `[...]`: a collection subscript throws on a key
                     that is not there, and these three doors must open even
                     when the page records behind them have not been written.
                     A tile with no photograph draws its own panel. --}}
                <div class="home-doors__grid">
                    <x-tile :href="route(__('route.restaurant'))" :image="$doors->get('restaurant')?->image" :title="ucfirst(__('nav.restaurant'))" :reveal="true"/>
                    <x-tile :href="route(__('route.menu'))" :image="$doors->get('menu')?->image" :title="ucfirst(__('nav.menu'))" :reveal="true"/>
                    <x-tile :href="route(__('route.hotel'))" :image="$doors->get('hotel')?->image" :title="ucfirst(__('nav.hotel'))" :reveal="true"/>
                </div>
            </div>
        </section>

        {{-- One chapter, not two: the house in its own words and the look
             around it were the same subject under two headings. The words
             open the band, the photographs follow, centred under them with
             an arrow on either side, and the house itself is the frame the
             strip opens on. Raised off the page, as one movement. --}}
        @php($photos = $gallery?->items ?? collect())

        <section class="home-lede" aria-labelledby="home-lede-title">
            <div class="container home-lede__body" data-strip>
                <h2 class="app-section__title home-lede__title" id="home-lede-title">{{ __('app.welcome') }}</h2>

                <p class="prose home-lede__text">{{ $page?->locale?->content }}</p>

                <div class="home-lede__stage">
                    @if($photos->isNotEmpty())
                        <button type="button" class="strip__btn home-lede__btn" data-strip-prev>
                            <span class="visually-hidden">{{ __('app.previous') }}</span>
                            <x-icon.chevron-left/>
                        </button>
                    @endif

                    <ul class="strip__track home-lede__track" data-strip-track>
                        <li class="strip__item">
                            <img src="{{ Vite::image('afx-building.jpg') }}" alt="{{ __('app.title') }}" loading="lazy" decoding="async"/>
                        </li>

                        @foreach($photos as $item)
                            <li class="strip__item">
                                <img src="{{ asset($item->image) }}" alt="" loading="lazy" decoding="async"/>
                            </li>
                        @endforeach
                    </ul>

                    @if($photos->isNotEmpty())
                        <button type="button" class="strip__btn home-lede__btn" data-strip-next>
                            <span class="visually-hidden">{{ __('app.next') }}</span>
                            <x-icon.chevron-right/>
                        </button>
                    @endif
                </div>
            </div>
        </section>
    </x-page>

    {{-- Only what the site already says: the footer's address, phone, mail
         and social links, and the week from Opening — the same hours the
         footer prints. Inline php directives only: Blade pairs the inline
         one above with any later end-php directive, comments included. --}}
    @php($days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'])
    @php($restaurant = [
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => __('app.title'),
            'url' => route('guest.fr.home'),
            'telephone' => '+41227761029',
            'email' => 'aubergedefounex@bluewin.ch',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => "Grand'Rue 31",
                'postalCode' => '1297',
                'addressLocality' => 'Founex',
                'addressCountry' => 'CH',
            ],
            'sameAs' => [
                'https://www.facebook.com/AubergeFounex',
                'https://www.instagram.com/auberge_de_founex/',
            ],
            'openingHoursSpecification' => \App\Support\Opening::week()
                ->flatMap(fn ($day) => collect($day->services())->map(fn ($range) => [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => 'https://schema.org/' . $days[$day->day],
                    'opens' => $range[0],
                    'closes' => $range[1],
                ]))
                ->values()
                ->all(),
        ])
    <script type="application/ld+json">{!! json_encode($restaurant, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endsection
