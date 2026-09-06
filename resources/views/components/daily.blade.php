@props(['special' => null, 'sections' => null])

@php($sections ??= collect())

{{-- Nothing at all when there is no special and nobody to write one: the
     page closes back up rather than leaving a hole where a dish should be. --}}
@if($special || auth()->check())
    <section class="home-special" @if($special) aria-labelledby="daily-eyebrow" @endif>
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

            @if($special)
                <article class="daily">
                    <p class="daily__eyebrow" id="daily-eyebrow">
                        {{ __('app.daily') }}

                        {{-- The day it is for, in the visitor's language. Only
                             when one was written: no date beats a stale one. --}}
                        @if($special->daily_on)
                            <span class="daily__date">{{ $special->daily_on->isoFormat('dddd D MMMM') }}</span>
                        @endif
                    </p>

                    <h2 class="daily__title">{{ $special->title }}</h2>

                    <p class="daily__price">
                        <span class="daily__currency">CHF</span>{{ number_format($special->price, 2, '.', "'") }}
                    </p>

                    @if(filled($special->description))
                        <p class="daily__description">{{ $special->description }}</p>
                    @endif
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
                <div class="form-floating mb-3">
                    <select class="form-select" id="daily-section" name="menu_section_id" required>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" @selected($special?->menu_section_id === $section->id)>
                                {{ $section->title }}
                            </option>
                        @endforeach
                    </select>
                    <label for="daily-section">{{ __('admin.field.section') }}</label>
                </div>

                {{-- Defaults to today, which is what it is nearly always for;
                     clearing it takes the date off the card. --}}
                <x-forms.date
                    name="daily_on"
                    :label="__('admin.field.daily_on')"
                    :value="($special?->daily_on ?? today())->format('Y-m-d')"
                    :required="false"
                />

                <x-forms.input
                    name="title"
                    :icon="Icon::TITLE"
                    :label="__('admin.field.dish')"
                    :value="$special?->title"
                    :required="true"
                />

                <x-forms.input
                    name="description"
                    :icon="Icon::INFO"
                    :label="__('admin.field.description')"
                    :value="$special?->description"
                />

                <x-forms.input
                    name="price"
                    type="number"
                    step="0.05"
                    :icon="Icon::PRICE"
                    :label="__('admin.field.price')"
                    {{-- Two decimals in the field, as on the card and the
                         carte: a price stored as 41.5 is written 41.50. --}}
                    :value="$special ? number_format($special->price, 2, '.', '') : null"
                    :required="true"
                />

                {{-- Clearing this leaves the dish on the carte and takes it
                     off the home page. It is not a delete. --}}
                <label class="admin-check">
                    <input type="checkbox" name="daily" value="1" @checked($special === null || $special->daily)>
                    <span>{{ __('admin.field.show_on_home') }}</span>
                </label>
            @endif
        </x-modal.index>
    @endauth
@endif
