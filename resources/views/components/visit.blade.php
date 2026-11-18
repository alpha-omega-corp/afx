{{-- Where the house is and when it is open — a band of its own on the home
     page instead of two cards squeezed under the hero.

     Modelled on the UI kit's description list "left-aligned with inline
     actions" (data-display/description-lists/06): a label column, the value
     beside it, rows divided by a hairline, and the one action a row has
     (Directions) at the end of that row. Re-themed to the site's tokens; the
     map beside it is the contact page's x-map without its own address bar,
     since the list already carries the address and the button. --}}
@php
    $destination = rawurlencode("Auberge de Founex, Grand'Rue 31, 1297 Founex");
    $google = 'https://www.google.com/maps/dir/?api=1&destination=' . $destination;
    $apple = 'https://maps.apple.com/?daddr=' . $destination . '&dirflg=d';
@endphp

<section class="home-visit" aria-labelledby="home-visit-title">
    <div class="container home-visit__grid">
        <div class="home-visit__info">
            <h2 class="app-section__title home-visit__title" id="home-visit-title">{{ __('footer.visit') }}</h2>

            <dl class="home-visit__list">
                <div class="home-visit__row">
                    <dt>@svg('heroicon-o-map-pin') {{ __('footer.address') }}</dt>
                    <dd>
                        <address class="home-visit__value">
                            Grand'Rue&nbsp;31<br>1297&nbsp;Founex
                        </address>
                        <a
                            class="home-visit__action"
                            href="{{ $google }}"
                            data-directions
                            data-apple="{{ $apple }}"
                            target="_blank"
                            rel="noopener"
                        >{{ __('app.itinerary') }}</a>
                    </dd>
                </div>

                <div class="home-visit__row">
                    <dt>@svg('heroicon-o-clock') {{ __('footer.hours') }}</dt>
                    <dd>
                        <div class="home-visit__value home-visit__hours">
                            {{-- The days on one line, then each service under
                                 them: opening and closing times in two
                                 aligned columns, an arrow down from the noon
                                 service to the evening one. --}}
                            @foreach(App\Support\Opening::services() as $run)
                                <p class="home-visit__days">{{ $run['days'] }}</p>
                                <ol class="home-visit__services">
                                    @foreach($run['services'] as [$from, $to])
                                        @unless($loop->first)
                                            <li class="home-visit__then" aria-hidden="true">@svg('heroicon-o-arrow-long-down')</li>
                                        @endunless
                                        <li class="home-visit__service">
                                            <span>{{ $from }}</span>
                                            <span class="home-visit__dash">–</span>
                                            <span>{{ $to }}</span>
                                        </li>
                                    @endforeach
                                </ol>
                            @endforeach

                            @if(($closed = App\Support\Opening::closedDays()) !== '')
                                <p class="home-visit__closed">{{ __('footer.hours_closed', ['days' => $closed]) }}</p>
                            @endif
                        </div>
                    </dd>
                </div>

                <div class="home-visit__row">
                    <dt>@svg('heroicon-o-phone') {{ __('app.reservations') }}</dt>
                    <dd>
                        <a class="home-visit__value" href="tel:+41227761029">022&nbsp;776&nbsp;10&nbsp;29</a>
                    </dd>
                </div>
            </dl>
        </div>

        <x-map :bar="false"/>
    </div>
</section>
