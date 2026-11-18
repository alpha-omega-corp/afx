@props(['specials' => null, 'sections' => null])

@php
    // One block, not an @php(...) line and a block after it: Blade's inline
    // form has no closing tag of its own and swallows the next @php whole.
    $specials ??= collect();
    $sections ??= collect();
@endphp

{{-- Nothing at all when there is no card and nobody to write one: the page
     closes back up rather than leaving a hole where a dish should be. --}}
@if($specials->isNotEmpty() || auth()->check())
    <section id="plat-du-jour" class="home-special" @if($specials->isNotEmpty()) aria-labelledby="daily-eyebrow" @endif>
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
                {{-- The courses as a short path, top to bottom, in the order
                     they are eaten (set in the editor): a lit disc with the
                     course's number on a pill at its shoulder, the dish's
                     name, the line about it and its price, and a dashed
                     thread down to the next. --}}
                <article class="daily" data-reveal>
                    <h2 class="daily__eyebrow" id="daily-eyebrow">{{ __('app.daily') }}</h2>

                    <ol class="daily__list">
                        @foreach($specials as $dish)
                            <li class="daily__dish">
                                <span class="daily__mark" aria-hidden="true">
                                    <x-icon.concierge-bell class="daily__icon"/>
                                    <span class="daily__step">{{ $loop->iteration }}</span>
                                </span>

                                <h3 class="daily__title">{{ $dish->locale?->title }}</h3>

                                @if(filled($dish->locale?->description))
                                    <p class="daily__description">{{ $dish->locale->description }}</p>
                                @endif

                                <p class="daily__price">
                                    <span class="daily__currency">CHF</span> {{ number_format($dish->price, 2, '.', "'") }}
                                </p>
                            </li>
                        @endforeach
                    </ol>
                </article>
            @else
                {{-- Staff only: the card's own empty state, so the button above
                     it has something to explain itself against. --}}
                <p class="daily daily--empty">{{ __('admin.empty.daily') }}</p>
            @endif
        </div>
    </section>

    {{-- Outside the section on purpose. A fixed-position modal inside a stacking context cannot rise above the backdrop that
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
