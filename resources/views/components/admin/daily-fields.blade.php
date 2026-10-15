@props(['specials' => null, 'sections' => null])

@php
    $specials ??= collect();
    $sections ??= collect();

    // Defaults to the next day the kitchen is open, which is what the card is
    // nearly always for; clearing it takes the date off the card.
    $defaultDate = ($specials->first()?->daily_on ?? App\Support\Opening::nextOpen(today()))->format('Y-m-d');

    // The rows the editor opens on. Ids and section ids are strings because
    // Alpine matches a <select>'s value by identity, and 3 is not "3".
    $rows = $specials->map(function ($dish) {
        $fr = $dish->localeIn(Lang::FR);
        $en = $dish->localeIn(Lang::EN);

        return [
            'id' => (string) $dish->id,
            'menu_section_id' => (string) $dish->menu_section_id,
            'title' => $fr->title,
            'description' => $fr->description,
            // A machine translation is deliberately NOT put in the box.
            //
            // Whatever is in an English field comes back as typed, and typed
            // means "a person wrote this, never touch it again". Prefilling
            // the machine's own words therefore froze them on the first save
            // of any dish: the English stopped following the French for good.
            // Left empty, it stays the machine's and keeps up. The current
            // translation is still on screen — it is in the list behind this
            // form, beside its `auto` badge.
            'title_en' => $en->isAuto('title') ? null : $en->title,
            'description_en' => $en->isAuto('description') ? null : $en->description,
            'price' => number_format($dish->price, 2, '.', ''),
        ];
    })->values()->all();

    // A fresh row lands in the first section rather than in none: the field
    // is required, and an empty select is a form that will not send.
    $blankRow = ['menu_section_id' => (string) $sections->first()?->id];
@endphp

@if($sections->isEmpty())
    <p class="admin-confirm">{{ __('admin.empty.daily_sections') }}</p>
@else
    {{-- One date for the whole card: a meal is served on one day. --}}
    <x-forms.date
        name="daily_on"
        :label="__('admin.field.daily_on')"
        :value="$defaultDate"
        :required="false"
    />

    <p class="admin-hint">{{ __('admin.hint.translation') }}</p>

    {{-- Taking a row off the card unflags the dish; it stays on the carte.
         Nothing in here deletes anything, which is why this repeater queues
         no removals. --}}
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
                    <option value="{{ $section->id }}">{{ $section->localeIn(Lang::FR)->title }}</option>
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
            model="item.title_en"
            name="daily_titles_en[]"
            :icon="Icon::TITLE"
            :label="__('admin.field.dish_en')"
            :required="false"
        />

        <x-forms.input
            model="item.description"
            name="daily_descriptions[]"
            :icon="Icon::INFO"
            :label="__('admin.field.description_fr')"
            :required="false"
        />

        <x-forms.input
            model="item.description_en"
            name="daily_descriptions_en[]"
            :icon="Icon::INFO"
            :label="__('admin.field.description_en')"
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

    {{-- Clearing this leaves every dish on the carte and takes the card off
         the home page. It is not a delete. Always open checked: a card that
         is being read is a card that is on show, and a card being written is
         one about to be. --}}
    <label class="admin-check">
        <input type="checkbox" name="daily" value="1" checked>
        <span>{{ __('admin.field.show_on_home') }}</span>
    </label>
@endif
