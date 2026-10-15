@props(['specials' => null, 'sections' => null, 'tuck' => true])

@php
    // One block, not an @php(...) line and a block after it: Blade's inline
    // form has no closing tag of its own and swallows the next @php whole.
    $specials ??= collect();
    $sections ??= collect();

    // The day the card is actually served. It still steps over the days the
    // kitchen is shut — App\Support\Opening is the only place the serving
    // week is written down — but the card no longer says which those are:
    // the closing days are printed in the footer and on the contact page,
    // and repeating them here read as a caveat on the invitation.
    $servedOn = $specials->first()?->servedOn();
@endphp

{{-- Nothing at all when there is no card and nobody to write one: the page
     closes back up rather than leaving a hole where a dish should be. --}}
@if($specials->isNotEmpty() || auth()->check())
    {{-- `tuck` is the climb into the hero's fade. True on the home page,
         where the card sits directly under the firelight; false anywhere it
         follows something else. --}}
    <section id="plat-du-jour" @class(['home-special', 'home-special--flat' => ! $tuck]) @if($specials->isNotEmpty()) aria-labelledby="daily-eyebrow" @endif>
        <div class="container">
            @auth
                {{-- Directly above the card, and only ever seen by staff. --}}
                <div class="home-special__admin">
                    <x-admin.action
                        :name="Modal::ADMIN_DAILY"
                        :action="Action::UPDATE"
                        :label="__('admin.action.edit_daily')"
                    />
                </div>
            @endauth

            @if($specials->isNotEmpty())
                <article @class(['daily', 'daily--several' => $specials->count() > 1])>
                    {{-- The label column: a covered dish, the words "plat du
                         jour", and the day — "today" when it is, so the one
                         word a guest scans for is the first one they meet. --}}
                    <header class="daily__head">
                        <x-illustration.cloche class="daily__art"/>

                        <div>
                            {{-- The day it is served, in the visitor's language.
                                 Only when a date was written at all: no date
                                 beats a stale one. --}}
                            @if($servedOn)
                                <p @class(['daily__when', 'is-today' => $servedOn->isToday()])>
                                    @if($servedOn->isToday())
                                        <span class="daily__pulse" aria-hidden="true"></span>{{ __('app.today') }}
                                    @else
                                        {{ $servedOn->isoFormat('dddd D MMMM') }}
                                    @endif
                                </p>
                            @endif

                            <h2 class="daily__eyebrow" id="daily-eyebrow">{{ __('app.daily') }}</h2>

                            @if($servedOn?->isToday())
                                <p class="daily__date">{{ $servedOn->isoFormat('dddd D MMMM') }}</p>
                            @endif
                        </div>
                    </header>

                    {{-- A card can be one dish or a whole meal. The order is
                         the order it is eaten in, set in the editor. --}}
                    <ul class="daily__list">
                        @foreach($specials as $dish)
                            <li class="daily__dish">
                                <h3 class="daily__title">{{ $dish->locale?->title }}</h3>

                                <span class="daily__leader" aria-hidden="true"></span>

                                <p class="daily__price">
                                    <span class="daily__currency">CHF</span>{{ number_format($dish->price, 2, '.', "'") }}
                                </p>

                                @if(filled($dish->locale?->description))
                                    <p class="daily__description">{{ $dish->locale->description }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                </article>
            @else
                {{-- Staff only: the card's own empty state, so the button above
                     it has something to explain itself against. --}}
                <p class="daily daily--empty">{{ __('admin.empty.daily') }}</p>
            @endif
        </div>
    </section>

    {{-- Outside the section on purpose. `.home-special` is a stacking context
         — it needs `position` and a `z-index` to tuck under the hero — and a
         fixed-position modal inside one cannot rise above the backdrop that
         Bootstrap appends to <body>: the modal opened, and every click landed
         on the backdrop instead of the field. The login modal in the guest
         layout sits at the root for the same reason. --}}
    @auth
        <x-modal.index
            :name="Modal::ADMIN_DAILY"
            :action="Action::UPDATE"
            :title="__('admin.action.edit_daily')"
            :route="route('admin.menu.daily')"
        >
            <x-admin.daily-fields :specials="$specials" :sections="$sections"/>
        </x-modal.index>
    @endauth
@endif
