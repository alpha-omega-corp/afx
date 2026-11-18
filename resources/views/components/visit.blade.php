@props(['home' => false])

{{-- Where the house is and how to reach it, in two places. On the home page
     (`home`) it is the band with the opening hours and the map beside them;
     on the contact page it is the page's body, the address and the ways to
     write, without the hours and the map the home page carries.

     Modelled on the UI kit's description list "left-aligned with inline
     actions" (data-display/description-lists/06): a label column, the value
     beside it, rows divided by a hairline, and the one action a row has
     (Directions) at the end of that row. Re-themed to the site's tokens; the
     map beside it on the home page is x-map without its own address bar,
     since the list already carries the address and the button. --}}
@php
    $destination = rawurlencode("Auberge de Founex, Grand'Rue 31, 1297 Founex");
    $google = 'https://www.google.com/maps/dir/?api=1&destination=' . $destination;
    $apple = 'https://maps.apple.com/?daddr=' . $destination . '&dirflg=d';
@endphp

<section @class(['app-visit', 'app-visit--home' => $home, 'app-visit--single' => ! $home]) aria-labelledby="app-visit-title">
    <div class="container app-visit__grid">
        <div class="app-visit__info">
            <h2 class="app-section__title app-visit__title" id="app-visit-title">{{ __('footer.visit') }}</h2>

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
                        >{{ __('app.itinerary') }}</a>
                    </dd>
                </div>

                @if($home)
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
                @endif

                <div class="app-visit__row">
                    <dt>@svg('heroicon-o-phone') {{ __('app.reservations') }}</dt>
                    <dd>
                        <a class="app-visit__value" href="tel:+41227761029">022&nbsp;776&nbsp;10&nbsp;29</a>
                    </dd>
                </div>

                @unless($home)
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
                                    <x-icon.facebook/> Auberge de Founex
                                </a>
                                <a href="https://www.instagram.com/auberge_de_founex/" target="_blank" rel="noopener">
                                    <x-icon.instagram/> auberge_de_founex
                                </a>
                            </div>
                        </dd>
                    </div>
                @endunless
            </dl>
        </div>

        @if($home)
            <x-map :bar="false"/>
        @endif
    </div>
</section>
