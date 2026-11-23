@props(['specials' => null, 'sections' => null])

@php
    $specials ??= collect();
    $sections ??= collect();

    // The rows the editor opens on. Ids and section ids are strings because
    // the hidden section field sends back exactly what it was given.
    $rows = $specials->map(function ($dish) {
        $fr = $dish->localeIn(Lang::FR);

        return [
            'id' => (string) $dish->id,
            'menu_section_id' => (string) $dish->menu_section_id,
            'title' => $fr->title,
            'description' => $fr->description,
            'price' => number_format($dish->price, 2, '.', ''),
        ];
    })->values()->all();

    // A fresh row lands in the first section rather than in none: the field
    // is required, and an empty select is a form that will not send.
    $blankRow = ['menu_section_id' => (string) $sections->first()?->id];

    // The days the card is on show. A card being written from nothing, or one
    // whose last day has gone by, opens on the next day the auberge serves:
    // re-saving an old card must not quietly keep last month's dates. Any
    // other card opens on its own dates, empty ones included.
    $card = $specials->first();

    if ($card === null || $card->dailyState() === 'ended') {
        $from = $until = \App\Support\Opening::nextOpen(\App\Models\MenuItem::today())->toDateString();
    } else {
        $from = $card->daily_on?->toDateString();
        $until = $card->daily_until?->toDateString();
    }

    // The days the card can be written for in one tap: the next five the
    // auberge serves, today first when it is one of them. A day further out
    // is still the date field's to pick.
    $today = \App\Models\MenuItem::today();
    $days = collect();

    for ($day = $today->copy(), $i = 0; $days->count() < 5 && $i < 21; $day = $day->addDay(), $i++) {
        if (\App\Support\Opening::isClosedOn($day)) {
            continue;
        }

        $local = $day->copy()->locale(app()->getLocale());

        $days->push([
            'date' => $day->toDateString(),
            'name' => match (true) {
                $day->isSameDay($today) => __('admin.field.today'),
                $day->isSameDay($today->copy()->addDay()) => __('admin.field.tomorrow'),
                default => \Illuminate\Support\Str::ucfirst($local->isoFormat('dddd')),
            },
            'date_label' => $local->isoFormat('D MMMM'),
        ]);
    }
@endphp

@if($sections->isEmpty())
    <p class="admin-confirm">{{ __('admin.empty.daily_sections') }}</p>
@else
    <div class="daily-fields">
        {{-- The dishes come first: they are what changes every day. Each row
             reads as a line of the card — French beside English, then where
             it is filed and what it costs — instead of six fields stacked
             one under the other.

             Taking a row off the card unflags the dish; it stays on the
             carte. Nothing in here deletes anything, which is why this
             repeater queues no removals. --}}
        <x-forms.repeater
            :title="__('admin.field.daily_dishes')"
            :items="$rows"
            id-name="daily_ids[]"
            :queue="false"
            :defaults="$blankRow"
            :add-label="__('admin.action.add_daily')"
        >
            <div class="daily-fields__dish">
                <p class="daily-fields__course">
                    <span class="daily-fields__number u-tabular" x-text="index + 1"></span>
                    <span class="daily-fields__course-name" x-text="item.title || @js(__('admin.field.daily_new'))"></span>
                </p>

                {{-- Name, description, price: the card as a guest reads it.
                     English is not written here. It is left to the
                     translator and follows the French; a correction typed
                     on the carte is kept, because fields that are not sent
                     are not touched (see DailySpecialRequest::english).

                     The section is not asked either: a dish already on the
                     carte stays where it is filed, and a new one goes into
                     the first section. --}}
                <input type="hidden" name="daily_sections[]" x-model="item.menu_section_id">

                <div class="daily-fields__name">
                    <x-forms.input
                        model="item.title"
                        name="daily_titles[]"
                        :icon="Icon::TITLE"
                        :label="__('admin.field.daily_name')"
                        :required="true"
                    />
                </div>

                <div class="daily-fields__price">
                    <x-forms.input
                        type="number"
                        step="0.05"
                        model="item.price"
                        name="daily_prices[]"
                        :icon="Icon::PRICE"
                        :label="__('admin.field.price')"
                        :required="true"
                    />
                </div>

                <div class="daily-fields__description">
                    <x-forms.input
                        model="item.description"
                        name="daily_descriptions[]"
                        :icon="Icon::INFO"
                        :label="__('admin.field.description')"
                        :required="false"
                    />
                </div>
            </div>
        </x-forms.repeater>

        {{-- When the card is on the home page: the switch, the day it is
             for, then its days as dates, both ends included. Outside them it
             is off the home page by itself; an empty end is no end.

             Clearing the switch leaves every dish on the carte and takes the
             card off the home page. It is not a delete. Always open checked:
             a card that is being read is a card that is on show, and a card
             being written is one about to be.

             The two dates are one choice, not two. A day picked above sets
             both; a first day typed in carries the last one along while they
             are the same day, and never leaves it behind. Both fields used to
             open on the same day and move independently, so changing only
             the first one sent a last day before it: the save was refused,
             nothing said so, and the card stayed on the day it already had. --}}
        <fieldset class="daily-fields__when" x-data="dailyDays(@js($from), @js($until))">
            <legend class="daily-fields__legend">{{ __('admin.field.daily_when') }}</legend>

            <label class="admin-check">
                <input type="checkbox" name="daily" value="1" checked>
                <span>{{ __('admin.field.show_on_home') }}</span>
            </label>

            <p class="daily-fields__sub" id="daily-days-label">{{ __('admin.field.daily_day') }}</p>

            <div class="daily-fields__days" role="group" aria-labelledby="daily-days-label">
                @foreach($days as $day)
                    <button
                        type="button"
                        class="daily-fields__day"
                        @click="pick(@js($day['date']))"
                        :aria-pressed="isPicked(@js($day['date'])).toString()"
                        aria-pressed="{{ $from === $day['date'] && $until === $day['date'] ? 'true' : 'false' }}"
                    >
                        <span class="daily-fields__day-name">{{ $day['name'] }}</span>
                        <span class="daily-fields__day-date">{{ $day['date_label'] }}</span>
                    </button>
                @endforeach
            </div>

            <div class="daily-fields__dates">
                <x-forms.date
                    name="daily_from"
                    :label="__('admin.field.daily_from')"
                    :value="$from"
                    :required="false"
                    x-model="from"
                />
                <x-forms.date
                    name="daily_until"
                    :label="__('admin.field.daily_until')"
                    :value="$until"
                    :required="false"
                    x-model="until"
                    x-bind:min="from || null"
                />
            </div>

            <p class="daily-fields__note">{{ __('admin.hint.daily_dates') }}</p>
        </fieldset>
    </div>
@endif
