@props(['specials' => null, 'sections' => null])

@php
    $specials ??= collect();
    $sections ??= collect();
    $today = \App\Models\MenuItem::today();
    $locale = app()->getLocale();

    // The days the plan can be written for in one choice: the next fourteen
    // the auberge serves, today first when it is one of them. A day further
    // out, or a stretch of several, is the "several days" choice's to write.
    $days = collect();

    for ($day = $today->copy(), $i = 0; $days->count() < 14 && $i < 42; $day = $day->addDay(), $i++) {
        if (\App\Support\Opening::isClosedOn($day)) {
            continue;
        }

        $local = $day->copy()->locale($locale);

        $days->push([
            'date' => $day->toDateString(),
            'name' => match (true) {
                $day->isSameDay($today) => __('admin.field.today'),
                $day->isSameDay($today->copy()->addDay()) => __('admin.field.tomorrow'),
                default => \Illuminate\Support\Str::ucfirst($local->isoFormat('dddd')),
            },
            'weekday' => \Illuminate\Support\Str::ucfirst($local->isoFormat('dddd')),
            'date_label' => $local->isoFormat('D MMMM'),
            'short' => \Illuminate\Support\Str::ucfirst($local->isoFormat('ddd D MMM')),
        ]);
    }

    // Ids and section ids are strings because the hidden fields send back
    // exactly what they were given.
    $row = function (\App\Models\MenuItem $dish, bool $keepDays) {
        $fr = $dish->localeIn(Lang::FR);

        return [
            'id' => (string) $dish->id,
            'menu_section_id' => (string) $dish->menu_section_id,
            'title' => $fr->title,
            'description' => $fr->description,
            'price' => number_format($dish->price, 2, '.', ''),
            'from' => $keepDays ? ($dish->daily_on?->toDateString() ?? '') : '',
            'until' => $keepDays ? ($dish->daily_until?->toDateString() ?? '') : '',
            'last' => $dish->daily_until?->copy()->locale(app()->getLocale())->isoFormat('D MMM'),
        ];
    };

    // The editor opens on what is still to be served, grouped by day. What
    // has been served already is offered back underneath, to plan again.
    $planned = \App\Models\MenuItem::planned($specials);
    $served = \App\Models\MenuItem::served($specials)->take(8);

    $config = [
        'rows' => $planned->map(fn ($dish) => $row($dish, true))->all(),
        'served' => $served->map(fn ($dish) => $row($dish, false))->all(),
        'days' => $days->all(),
        // A fresh dish lands in the first section rather than in none: the
        // field is required, and an empty one is a form that will not send.
        'section' => (string) $sections->first()?->id,
        'text' => [
            'new' => __('admin.field.daily_new'),
            'always' => __('admin.daily_choice.always_short'),
            'from' => __('admin.daily_when.from'),
            'until' => __('admin.daily_when.until'),
        ],
    ];
@endphp

@if($sections->isEmpty())
    <p class="admin-confirm">{{ __('admin.empty.daily_sections') }}</p>
@else
    {{-- The plan of the plats du jour: the days ahead first, each with what
         is served on it, then the dishes, each with the day it is for.

         Every dish has days of its own. A dish for next Tuesday is written
         today and goes up on Tuesday by itself, while this week's dishes
         stay where they are; once its last day is over it leaves the home
         page, and the editor offers it back under the list.

         Taking a row off the plan unflags the dish; it stays on the carte.
         Nothing in here deletes anything. --}}
    <div class="daily-fields" x-data="dailyPlan({{ Js::from($config) }})">
        <section class="daily-plan" aria-labelledby="daily-plan-title">
            <h3 class="daily-plan__title" id="daily-plan-title">{{ __('admin.field.daily_plan') }}</h3>

            <ol class="daily-plan__days">
                <template x-for="day in days.slice(0, 7)" :key="day.date">
                    <li class="daily-plan__day" :class="{ 'daily-plan__day--empty': !dishesOn(day.date).length }">
                        <p class="daily-plan__day-head">
                            <span class="daily-plan__day-name" x-text="day.name"></span>
                            <span class="daily-plan__day-date" x-text="day.date_label"></span>
                        </p>

                        <ul class="daily-plan__dishes">
                            <template x-for="dish in dishesOn(day.date)" :key="dish.key">
                                <li>
                                    <button type="button" class="daily-plan__dish" @click="reveal(dish)" :title="dish.title || text.new">
                                        <span class="daily-plan__dish-number u-tabular" x-text="rows.indexOf(dish) + 1"></span>
                                        <span class="daily-plan__dish-name" x-text="dish.title || text.new"></span>
                                    </button>
                                </li>
                            </template>
                        </ul>

                        <p class="daily-plan__nothing" x-show="!dishesOn(day.date).length">{{ __('admin.field.daily_nothing') }}</p>

                        <button
                            type="button"
                            class="daily-plan__add u-tap"
                            @click="add(day.date)"
                            :aria-label="@js(__('admin.action.add_daily_on')).replace(':day', day.weekday + ' ' + day.date_label)"
                        >
                            @svg(Icon::CREATE->value, 'daily-plan__add-icon')
                            <span>{{ __('admin.action.add_short') }}</span>
                        </button>
                    </li>
                </template>
            </ol>
        </section>

        <div class="app-repeater">
            <h3 class="app-repeater__title">{{ __('admin.field.daily_dishes') }}</h3>

            <p class="daily-fields__empty" x-show="!rows.length">{{ __('admin.empty.daily_plan') }}</p>

            <div class="app-repeater__items">
                <template x-for="(item, index) in rows" :key="item.key">
                    <div class="app-repeater__item" :class="{ 'daily-fields__row--flash': flashed === item.key }" :id="'daily-row-' + item.key">
                        <div class="app-repeater__fields">
                            <div class="daily-fields__dish">
                                {{-- "1  Civet de chevreuil  ·  Mardi 24 nov.": the
                                     row's number, its name as typed and the day
                                     it is served, so a long plan can be read
                                     down the left without opening every row. --}}
                                <p class="daily-fields__course">
                                    <span class="daily-fields__number u-tabular" x-text="index + 1"></span>
                                    <span class="daily-fields__course-name" x-text="item.title || text.new"></span>
                                    <span class="daily-fields__badge" :class="{ 'daily-fields__badge--always': !item.from && !item.until }" x-text="label(item)"></span>
                                </p>

                                <input type="hidden" name="daily_ids[]" :value="item.id">
                                <input type="hidden" name="daily_sections[]" :value="item.menu_section_id">
                                <input type="hidden" name="daily_froms[]" :value="item.from">
                                <input type="hidden" name="daily_untils[]" :value="item.until">

                                {{-- Name, description, price: the card as a guest
                                     reads it. English is left to the translator
                                     and follows the French; a correction typed on
                                     the carte is kept, because fields that are
                                     not sent are not touched (see
                                     DailySpecialRequest::english). --}}
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

                                {{-- When it is served: one of the next days, every
                                     day, or a stretch of dates. The two dates are
                                     one choice, not two: a first day typed in
                                     carries the last one along and never leaves
                                     it behind, since a last day before the first
                                     is a save the server refuses. --}}
                                <div class="daily-fields__served">
                                    <div class="form-floating mb-3">
                                        <select
                                            class="form-select"
                                            :id="'daily-served-' + item.key"
                                            @change="choose(item, $event.target.value)"
                                        >
                                            <template x-for="day in days" :key="day.date">
                                                <option :value="day.date" :selected="choice(item) === day.date" x-text="day.name === day.weekday ? day.weekday + ' ' + day.date_label : day.name + ' — ' + day.weekday + ' ' + day.date_label"></option>
                                            </template>
                                            <option value="always" :selected="choice(item) === 'always'">{{ __('admin.daily_choice.always') }}</option>
                                            <option value="range" :selected="choice(item) === 'range'">{{ __('admin.daily_choice.range') }}</option>
                                        </select>
                                        <label :for="'daily-served-' + item.key">{{ __('admin.field.daily_served') }}</label>
                                    </div>
                                </div>

                                <template x-if="item.ranged">
                                    <div class="daily-fields__dates">
                                        <div class="form-floating mb-3">
                                            <input type="date" class="form-control" placeholder=" "
                                                   :id="'daily-from-' + item.key"
                                                   x-model="item.from"
                                                   @change="keepOrder(item)">
                                            <label :for="'daily-from-' + item.key">{{ __('admin.field.daily_from') }}</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="date" class="form-control" placeholder=" "
                                                   :id="'daily-until-' + item.key"
                                                   x-model="item.until"
                                                   :min="item.from || null">
                                            <label :for="'daily-until-' + item.key">{{ __('admin.field.daily_until') }}</label>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <button
                            type="button"
                            class="admin-action admin-action--danger admin-action--compact u-tap"
                            @click="remove(index)"
                            aria-label="{{ __('admin.action.remove_daily') }}"
                            title="{{ __('admin.action.remove_daily') }}"
                        >
                            @svg(Icon::DELETE->value, 'admin-action__icon')
                        </button>
                    </div>
                </template>
            </div>

            <div class="app-repeater__footer">
                <button type="button" class="admin-action admin-action--neutral" @click="add()">
                    @svg(Icon::CREATE->value, 'admin-action__icon')
                    <span class="admin-action__label">{{ __('admin.action.add_daily') }}</span>
                </button>
            </div>
        </div>

        {{-- Served already: off the home page, kept off the carte, and back on
             the plan in one tap, on the first day that has nothing yet. --}}
        <section class="daily-served" x-show="served.length" aria-labelledby="daily-served-title">
            <h3 class="daily-served__title" id="daily-served-title">{{ __('admin.field.daily_served_before') }}</h3>

            <ul class="daily-served__list">
                <template x-for="(dish, index) in served" :key="dish.id">
                    <li>
                        <button type="button" class="daily-served__dish" @click="reuse(index)">
                            @svg(Icon::CREATE->value, 'daily-served__icon')
                            <span class="daily-served__name" x-text="dish.title"></span>
                            <span class="daily-served__last" x-text="dish.last"></span>
                        </button>
                    </li>
                </template>
            </ul>
        </section>

        <p class="daily-fields__note">{{ __('admin.hint.daily_dates') }}</p>
    </div>
@endif
