@props(['home' => false])

{{-- Where the house is, when it is open and how to reach it — the same band
     on the home page (`home`, with a seam above it like the other home
     bands) and as the body of the contact page.

     A raised card holding the UI kit's description list "left-aligned with
     inline actions" (data-display/description-lists/06) — a label column,
     the value beside it, hairlines between rows, a row's one action at its
     end — and the map beside it, stretched to the card's height. The map
     goes without its own address bar: the card already carries the address
     and the Directions link. --}}
@php
    $destination = rawurlencode("Auberge de Founex, Grand'Rue 31, 1297 Founex");
    $google = 'https://www.google.com/maps/dir/?api=1&destination=' . $destination;
    $apple = 'https://maps.apple.com/?daddr=' . $destination . '&dirflg=d';
@endphp

<section @class(['app-visit', 'app-visit--home' => $home]) aria-labelledby="app-visit-title">
    <div class="container">
        <h2 class="app-section__title app-visit__title" id="app-visit-title">{{ __('footer.visit') }}</h2>

        <div class="app-visit__grid">
            <div class="app-visit__card">
                <dl class="app-visit__list">
                    <div class="app-visit__row">
                        <dt>@svg('heroicon-o-map-pin') {{ __('footer.address') }}</dt>
                        <dd>
                            <address class="app-visit__value">
                                Grand'Rue&nbsp;31<br>1297&nbsp;Founex
                            </address>
                            <a
                                class="app-visit__action"
                                href="{{ $google }}"
                                data-directions
                                data-apple="{{ $apple }}"
                                target="_blank"
                                rel="noopener"
                            >{{ __('app.itinerary') }} @svg('heroicon-o-arrow-up-right')</a>
                        </dd>
                    </div>

                    <div class="app-visit__row">
                        <dt>@svg('heroicon-o-clock') {{ __('footer.hours') }}</dt>
                        <dd>
                            <div class="app-visit__value app-visit__hours">
                                {{-- The days on one line, then each service under
                                     them: opening and closing times in two
                                     aligned columns, an arrow down from the noon
                                     service to the evening one. --}}
                                @foreach(App\Support\Opening::services() as $run)
                                    <p class="app-visit__days">{{ $run['days'] }}</p>
                                    <ol class="app-visit__services">
                                        @foreach($run['services'] as [$from, $to])
                                            @unless($loop->first)
                                                <li class="app-visit__then" aria-hidden="true">@svg('heroicon-o-arrow-long-down')</li>
                                            @endunless
                                            <li class="app-visit__service">
                                                <span>{{ $from }}</span>
                                                <span class="app-visit__dash">–</span>
                                                <span>{{ $to }}</span>
                                            </li>
                                        @endforeach
                                    </ol>
                                @endforeach

                                @if(($closed = App\Support\Opening::closedDays()) !== '')
                                    <p class="app-visit__closed">{{ __('footer.hours_closed', ['days' => $closed]) }}</p>
                                @endif
                            </div>
                        </dd>
                    </div>

                    <div class="app-visit__row">
                        <dt>@svg('heroicon-o-phone') {{ __('app.reservations') }}</dt>
                        <dd>
                            <a class="app-visit__value app-visit__phone" href="tel:+41227761029">022&nbsp;776&nbsp;10&nbsp;29</a>
                        </dd>
                    </div>

                    <div class="app-visit__row">
                        <dt>@svg('heroicon-o-envelope') {{ __('form.email') }}</dt>
                        <dd>
                            <a class="app-visit__value app-visit__email" href="mailto:aubergedefounex@bluewin.ch">aubergedefounex@bluewin.ch</a>
                        </dd>
                    </div>

                    <div class="app-visit__row">
                        <dt>@svg('heroicon-o-share') {{ __('footer.social') }}</dt>
                        <dd>
                            <div class="app-visit__social">
                                <a href="https://www.facebook.com/AubergeFounex" target="_blank" rel="noopener">
                                    <x-icon.facebook/> Facebook
                                </a>
                                <a href="https://www.instagram.com/auberge_de_founex/" target="_blank" rel="noopener">
                                    <x-icon.instagram/> Instagram
                                </a>
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>

            <x-map :bar="false"/>
        </div>
    </div>
</section>
