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
    $defaultDate = ($specials->first()?->daily_on ?? App\Support\Opening::nextOpen(today()))->format('Y-m-d');

    // The rows the editor opens on. Ids and section ids are strings because
    // Alpine matches a <select>'s value by identity, and 3 is not "3".
    //
    // French, whichever language the page is being read in: this editor
    // writes the source text, and the English follows on its own.
    $rows = $specials->map(fn ($dish) => [
        'id' => (string) $dish->id,
        'menu_section_id' => (string) $dish->menu_section_id,
        'title' => $dish->localeIn(Lang::FR)->title,
        'description' => $dish->localeIn(Lang::FR)->description,
        'price' => number_format($dish->price, 2, '.', ''),
    ])->values()->all();

    // A fresh row lands in the first section rather than in none: the field
    // is required, and an empty select is a form that will not send.
    $blankRow = ['menu_section_id' => (string) $sections->first()?->id];
@endphp

{{-- Nothing at all when there is no card and nobody to write one: the page
     closes back up rather than leaving a hole where a dish should be. --}}
@if($specials->isNotEmpty() || auth()->check())
    {{-- `tuck` is the climb into the hero's fade. True on the home page,
         where the card sits directly under the firelight; false anywhere it
         follows something else. --}}
    <section @class(['home-special', 'home-special--flat' => ! $tuck]) @if($specials->isNotEmpty()) aria-labelledby="daily-eyebrow" @endif>
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
                    <h2 class="daily__eyebrow" id="daily-eyebrow">
                        {{ __('app.daily') }}

                        {{-- The day it is served, in the visitor's language.
                             Written on a closed day, it reads as the next day
                             the kitchen is open — see the note below the
                             dishes. Only when a date was written at all: no
                             date beats a stale one. --}}
                        @if($servedOn)
                            <span class="daily__date">{{ $servedOn->isoFormat('dddd D MMMM') }}</span>
                        @endif
                    </h2>

                    {{-- A card can be one dish or a whole meal. The order is
                         the order it is eaten in, set in the editor. --}}
                    <ul class="daily__list">
                        @foreach($specials as $dish)
                            <li class="daily__dish">
                                <h3 class="daily__title">{{ $dish->locale?->title }}</h3>

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
            @if($sections->isEmpty())
                <p class="admin-confirm">{{ __('admin.empty.daily_sections') }}</p>
            @else
                {{-- Defaults to today, which is what it is nearly always for;
                     clearing it takes the date off the card. One date for the
                     whole card: a meal is served on one day. --}}
                <x-forms.date
                    name="daily_on"
                    :label="__('admin.field.daily_on')"
                    :value="$defaultDate"
                    :required="false"
                />

                {{-- Taking a row off the card unflags the dish; it stays on
                     the carte. Nothing in here deletes anything, which is why
                     this repeater queues no removals.

                     Written in French only. The English is translated on its
                     own and can be corrected in Administration → Carte, which
                     is where a dish is edited carefully rather than daily. --}}
                <x-forms.repeater
                    :title="__('admin.field.daily_dishes')"
                    :items="$rows"
                    id-name="daily_ids[]"
                    :queue="false"
                    :defaults="$blankRow"
                >
                    <div class="form-floating mb-3">
                        <select
                            class="form-select"
                            name="daily_sections[]"
                            x-model="item.menu_section_id"
                            :id="`daily-section-${index}`"
                            required
                        >
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->locale?->title }}</option>
                            @endforeach
                        </select>
                        <label :for="`daily-section-${index}`">{{ __('admin.field.section') }}</label>
                    </div>

                    <x-forms.input
                        model="item.title"
                        name="daily_titles[]"
                        :icon="Icon::TITLE"
                        :label="__('admin.field.dish_fr')"
                        :required="true"
                    />

                    <x-forms.input
                        model="item.description"
                        name="daily_descriptions[]"
                        :icon="Icon::INFO"
                        :label="__('admin.field.description_fr')"
                        :required="false"
                    />

                    <x-forms.input
                        type="number"
                        step="0.05"
                        model="item.price"
                        name="daily_prices[]"
                        :icon="Icon::PRICE"
                        :label="__('admin.field.price')"
                        :required="true"
                    />
                </x-forms.repeater>

                {{-- Clearing this leaves every dish on the carte and takes the
                     card off the home page. It is not a delete. Always open
                     checked: a card that is being read is a card that is on
                     show, and a card being written is one about to be. --}}
                <label class="admin-check">
                    <input type="checkbox" name="daily" value="1" checked>
                    <span>{{ __('admin.field.show_on_home') }}</span>
                </label>

                <p class="admin-hint">{{ __('admin.hint.daily_language') }}</p>
            @endif
        </x-modal.index>
    @endauth
@endif
