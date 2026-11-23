@props(['specials' => null, 'editing' => null, 'sections' => null])

@php
    // One block, not an @php(...) line and a block after it: Blade's inline
    // form has no closing tag of its own and swallows the next @php whole.
    $specials ??= collect();
    // The whole card, on show today or not: what the editor opens on.
    $editing ??= $specials;
    $sections ??= collect();
    // A card staff wrote that is not on show today, because its first day is
    // still to come or its last one has gone by.
    $waiting = $specials->isEmpty() ? $editing->first() : null;
@endphp

{{-- The plat du jour as a slip of the day's carte, on the home hero's
     photograph: the label between two ember strokes, then each course on
     one line — its name, a dotted leader, its price — and the line about
     it underneath, as a printed menu sets them. Nothing at all for a guest
     when there is no card; staff get the slip's outline and the button. --}}
@if($specials->isNotEmpty() || auth()->check())
    <aside id="plat-du-jour" @class(['daily', 'daily--empty' => $specials->isEmpty()]) aria-labelledby="daily-label">
        <header class="daily__head">
            <h2 class="daily__label" id="daily-label">{{ __('app.daily') }}</h2>
        </header>

        @if($specials->isNotEmpty())
            <ol class="daily__list">
                @foreach($specials as $dish)
                    <li class="daily__dish">
                        <p class="daily__line">
                            <span class="daily__title">{{ $dish->locale?->title }}</span>
                            <span class="daily__leader" aria-hidden="true"></span>
                            <span class="daily__price">
                                <span class="daily__currency">CHF</span>&nbsp;{{ number_format($dish->price, 2, '.', "'") }}
                            </span>
                        </p>

                        @if(filled($dish->locale?->description))
                            <p class="daily__description">{{ $dish->locale->description }}</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        @else
            {{-- Staff only: why the slip is empty, and — when a card exists
                 but is outside its days — which side of them it is on. --}}
            <p class="daily__empty">
                @if($waiting)
                    {{ __('admin.daily_state.' . $waiting->dailyState()) }} —
                    <x-admin.daily-when :dish="$waiting"/>
                @else
                    {{ __('admin.empty.daily') }}
                @endif
            </p>
        @endif

        {{-- Staff only: the way into the editor, centred under the slip, a
             labelled button at a full thumb's height rather than a pencil
             crowded into a corner of the label. --}}
        @auth
            <footer class="daily__foot">
                <x-admin.action
                    class="daily__edit"
                    :name="Modal::ADMIN_DAILY"
                    :action="Action::UPDATE"
                    :label="__('admin.action.edit_daily')"
                />
            </footer>
        @endauth
    </aside>

    {{-- Pushed to the foot of the page, out of the hero: a fixed-position
         modal inside a stacking context cannot rise above the backdrop that
         Bootstrap appends to <body>, and every click would land on the
         backdrop instead of the field. --}}
    @auth
        @push('modals')
            <x-modal.index
                :name="Modal::ADMIN_DAILY"
                :action="Action::UPDATE"
                :title="__('admin.action.edit_daily')"
                size="lg"
                :route="route('admin.menu.daily')"
            >
                <x-admin.daily-fields :specials="$editing" :sections="$sections"/>
            </x-modal.index>
        @endpush
    @endauth
@endif
