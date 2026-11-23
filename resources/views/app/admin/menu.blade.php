@extends('layouts.admin')

@section('title', ucfirst(__('nav.menu')))

@section('actions')
    <x-admin.action
        :name="Modal::ADMIN_MENU"
        :action="Action::CREATE"
        :label="__('admin.action.add_section')"
    />
@endsection

@section('content')
    {{-- Everything below is counted off the collections the controller
         already eager-loaded (sections.items.locales): the numbers cost
         nothing, and no controller had to learn about this screen. --}}
    @php
        $dishes = $sections->sum(fn ($section) => $section->items->reject->daily->count());

        // The dishes whose English is still the machine's. The list is the
        // count, and it is also the thing to act on.
        $review = $sections->flatMap(
            fn ($section) => $section->items->reject->daily->filter(
                fn ($item) => $item->localeIn(Lang::EN)->isAuto('title')
            )
        );

        // The carte table's rows and words; see the panel further down.
        $table = $sections->flatMap(fn ($section) => $section->items->reject->daily->map(function ($item) use ($section) {
            $fr = $item->localeIn(Lang::FR);
            $en = $item->localeIn(Lang::EN);

            return [
                'id' => $item->id,
                'section' => $section->id,
                'title_fr' => $fr->title,
                'title_en' => $en->title,
                'description_fr' => $fr->description,
                'auto' => $en->isAuto('title'),
                'price' => (float) $item->price,
            ];
        }))->values();

        $tableConfig = [
            'rows' => $table,
            'sections' => $sections->map(fn ($section) => [
                'id' => $section->id,
                'title' => $section->localeIn(Lang::FR)->title,
                'modal' => \App\Helpers\ModalHelper::getId(Modal::ADMIN_MENU, Action::UPDATE, $section->id),
            ])->values(),
            'text' => [
                'section' => __('admin.table.section'),
                'dish' => __('admin.table.dish'),
                'dish_en' => __('admin.table.dish_en'),
                'price' => __('admin.table.price'),
                'edit' => __('admin.table.edit'),
                'auto' => __('admin.auto'),
                'auto_hint' => __('admin.hint.auto'),
                'empty' => __('admin.table.empty'),
                'dishes' => [
                    trans_choice('admin.table.dishes', 0),
                    trans_choice('admin.table.dishes', 1),
                    trans_choice('admin.table.dishes', 2, ['count' => ':count']),
                ],
                'shown' => __('admin.table.shown'),
            ],
        ];
    @endphp

    <ul class="admin-metrics">
        <li class="admin-metric">
            <p class="admin-metric__label">{{ __('admin.metric.sections') }}</p>
            <p class="admin-metric__value u-tabular">{{ $sections->count() }}</p>
        </li>

        <li class="admin-metric">
            <p class="admin-metric__label">{{ __('admin.metric.dishes') }}</p>
            <p class="admin-metric__value u-tabular">{{ $dishes }}</p>
        </li>

        <li class="admin-metric">
            <p class="admin-metric__label">{{ __('admin.metric.daily') }}</p>
            <p class="admin-metric__value u-tabular">{{ $specials->count() }}</p>
        </li>
    </ul>

    {{-- The plat du jour, first and on its own.
         It is not a section of the carte and must not read as one: it changes
         every day, it is the only thing on the site that does, and its dishes
         are pulled out of the sections below so the same name never appears
         twice on this screen — exactly as the guest carte prints them once,
         in their own space, at the top.

         Two thirds of the row: it has five internal columns, and 8/12 is the
         width at which they stop collapsing. It is also the one heated
         surface of this screen, and there is never a second. --}}
    <section class="admin-daily bento-xl-8">
        <header class="admin-daily__header">
            <div>
                <h2 class="admin-daily__title">{{ __('app.daily') }}</h2>
                <p class="admin-daily__description">{{ __('admin.daily_description') }}</p>
                @if($specials->isNotEmpty())
                    {{-- When the card is on the home page, in words, and
                         whether today is one of those days. --}}
                    <p class="admin-daily__when">
                        <span class="admin-daily__state admin-daily__state--{{ $specials->first()->dailyState() }}">{{ __('admin.daily_state.' . $specials->first()->dailyState()) }}</span>
                        <x-admin.daily-when :dish="$specials->first()"/>
                    </p>
                @endif
            </div>

            <x-admin.action
                :name="Modal::ADMIN_DAILY"
                :action="Action::UPDATE"
                :label="__('admin.action.edit_daily')"
            />
        </header>

        @if($specials->isEmpty())
            <p class="admin-daily__empty">{{ __('admin.empty.daily') }}</p>
        @else
            <ul class="admin-daily__list">
                @foreach($specials as $dish)
                    @php($fr = $dish->localeIn(Lang::FR))
                    @php($en = $dish->localeIn(Lang::EN))

                    <li class="admin-daily__dish">
                        <span class="admin-daily__course">{{ $loop->iteration }}</span>

                        <span class="admin-daily__name">{{ $fr->title }}</span>

                        <span class="admin-daily__name-en">
                            {{ $en->title }}
                            @if($en->isAuto('title'))
                                <span class="admin-auto" title="{{ __('admin.hint.auto') }}">{{ __('admin.auto') }}</span>
                            @endif
                        </span>

                        <span class="admin-daily__section">{{ $dish->section?->localeIn(Lang::FR)->title }}</span>

                        <span class="admin-daily__price u-tabular">{{ number_format($dish->price, 2, '.', "'") }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    <x-modal.index
        :name="Modal::ADMIN_DAILY"
        :action="Action::UPDATE"
        :title="__('admin.action.edit_daily')"
        size="lg"
        :route="route('admin.menu.daily')"
    >
        <x-admin.daily-fields :specials="$specials" :sections="$sections"/>
    </x-modal.index>

    {{-- The remaining third: what the machine wrote and nobody has read back
         yet. It sits beside the card of the day rather than under the carte,
         because it is a worklist, not a part of the menu. --}}
    <x-admin.panel
        :title="__('admin.review_title')"
        :description="__('admin.review_description')"
        :xl="4"
    >
        {{-- Capped, and the cap is not cosmetic: the seeded carte alone is
             forty-four dishes with machine English, and an uncapped list in a
             4-of-12 cell opens three thousand pixels of dead space beside the
             card of the day and pushes the carte — the point of the screen —
             off the bottom of the document. The count above is the whole
             number; the list is the head of the worklist. --}}
        @php($shown = $review->take(8))

        @if($review->isEmpty())
            <p class="admin-empty">{{ __('admin.empty.review') }}</p>
        @else
            <p class="admin-hint">
                <strong class="u-tabular">{{ $review->count() }}</strong>
                {{ __('admin.metric.to_review') }}@if($review->count() > $shown->count()) — {{ __('admin.review_more', ['n' => $shown->count()]) }}@endif
            </p>

            <ul class="admin-section__items">
                @foreach($shown as $item)
                    <li class="admin-section__item">
                        <span class="admin-section__item-title">{{ $item->localeIn(Lang::FR)->title }}</span>

                        <span class="admin-section__item-en">
                            {{ $item->localeIn(Lang::EN)->title }}
                            <span class="admin-auto" title="{{ __('admin.hint.auto') }}">{{ __('admin.auto') }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-admin.panel>

    {{-- The carte itself stays full width at every step: x-sort on items laid
         out in several columns makes the drop placeholder jump on every
         hover, and reordering is the whole point of this list.

         flat, and not by preference: the real carte is nine sections and
         eighty-odd dishes, i.e. a panel some four thousand pixels tall. The
         blur budget gives that up first — one composited layer that size,
         re-sampled on every scroll frame, for a gradient nobody can see move
         behind it. --}}
    {{-- Two ways of looking at the same carte. Sections is the one that
         reorders; Table puts every dish on one grid, to sort by price, search
         a word or pick out what is still the machine's English. The table is
         drawn by Tabulator, loaded only the first time it is asked for.

         Every section's modals are declared after the panel, not inside the
         section rows: Bootstrap cannot open a dialog whose ancestor is
         display:none, and the rows are hidden while the table is shown —
         which is exactly when the table's edit buttons open them. --}}

    <x-admin.panel
        :title="__('admin.menu_title')"
        :description="__('admin.menu_description')"
        tone="flat"
        x-data="menuView"
    >
        @if($sections->isNotEmpty())
            <x-slot:actions>
                {{-- The back office's segmented control, reused as it is: the
                     sliding thumb reads aria-selected off these two tabs. --}}
                <div
                    class="tab__nav menu-view"
                    role="tablist"
                    aria-label="{{ __('admin.view.label') }}"
                    style="--tab-count: 2"
                    @keydown.right.prevent="show('table'); $nextTick(() => $refs.tabTable.focus())"
                    @keydown.left.prevent="show('sections'); $nextTick(() => $refs.tabSections.focus())"
                >
                    <button
                        type="button"
                        role="tab"
                        class="tab__link"
                        id="menu-view-sections"
                        aria-controls="menu-view-sections-panel"
                        x-ref="tabSections"
                        :aria-selected="view === 'sections'"
                        :tabindex="view === 'sections' ? 0 : -1"
                        @click="show('sections')"
                    >
                        @svg('heroicon-o-queue-list', 'menu-view__icon')
                        <span class="tab__label">{{ __('admin.view.sections') }}</span>
                    </button>

                    <button
                        type="button"
                        role="tab"
                        class="tab__link"
                        id="menu-view-table"
                        aria-controls="menu-view-table-panel"
                        x-ref="tabTable"
                        :aria-selected="view === 'table'"
                        :tabindex="view === 'table' ? 0 : -1"
                        @click="show('table')"
                    >
                        @svg('heroicon-o-table-cells', 'menu-view__icon')
                        <span class="tab__label">{{ __('admin.view.table') }}</span>
                    </button>

                    <span class="tab__thumb" aria-hidden="true"></span>
                </div>
            </x-slot:actions>
        @endif

        @if($sections->isEmpty())
            <p class="admin-empty">{{ __('admin.empty.menu') }}</p>
        @else
            <div
                id="menu-view-table-panel"
                role="tabpanel"
                aria-labelledby="menu-view-table"
                class="menu-table"
                x-show="view === 'table'"
                x-cloak
            >
                <div class="menu-table__tools">
                    <label class="menu-table__search">
                        @svg('heroicon-o-magnifying-glass', 'menu-table__search-icon')
                        <span class="visually-hidden">{{ __('admin.table.search') }}</span>
                        <input
                            type="search"
                            class="form-control"
                            placeholder="{{ __('admin.table.search') }}"
                            x-model.debounce.150ms="query"
                        >
                    </label>

                    <div class="form-check form-switch menu-table__switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="menu-table-auto" x-model="onlyAuto">
                        <label class="form-check-label" for="menu-table-auto">{{ __('admin.table.only_auto') }}</label>
                    </div>

                    <p class="menu-table__count u-tabular" aria-live="polite" x-text="count"></p>
                </div>

                <div x-ref="table" data-config='@json($tableConfig)'></div>
            </div>

            <ul
                id="menu-view-sections-panel"
                role="tabpanel"
                aria-labelledby="menu-view-sections"
                class="admin-sections"
                x-show="view === 'sections'"
                x-data="sort"
                x-sort.ghost="handle"
            >
                @foreach($sections as $section)
                    <li class="admin-section" x-sort:item="'{{ $section->id }}'">
                        <div class="admin-section__header">
                            <span
                                class="admin-section__handle"
                                x-sort:handle
                                title="{{ __('admin.action.reorder') }}"
                            >
                                @svg('heroicon-o-arrows-up-down')
                            </span>

                            <h3 class="admin-section__title">{{ $section->localeIn(Lang::FR)->title }}</h3>

                            <div class="admin-section__actions">
                                <x-admin.action
                                    :name="Modal::ADMIN_MENU"
                                    :action="Action::UPDATE"
                                    :iterator="$section->id"
                                    :compact="true"
                                />

                                <x-admin.action
                                    :name="Modal::ADMIN_MENU"
                                    :action="Action::DELETE"
                                    :iterator="$section->id"
                                    :compact="true"
                                />
                            </div>
                        </div>

                        <ul class="admin-section__items">
                            @foreach($section->items->reject->daily as $item)
                                @php($fr = $item->localeIn(Lang::FR))
                                @php($en = $item->localeIn(Lang::EN))

                                <li class="admin-section__item">
                                    <span class="admin-section__item-title">{{ $fr->title }}</span>

                                    {{-- The English beside the French, and a
                                         mark on it while it is still the
                                         machine's: the point of the list is to
                                         show at a glance what has been read
                                         over and what has not. --}}
                                    <span class="admin-section__item-en">
                                        {{ $en->title }}
                                        @if($en->isAuto('title'))
                                            <span class="admin-auto" title="{{ __('admin.hint.auto') }}">{{ __('admin.auto') }}</span>
                                        @endif
                                    </span>

                                    <span class="admin-section__item-price u-tabular">{{ $item->price }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-admin.panel>

    @foreach($sections as $section)
        <x-modal.index
            :name="Modal::ADMIN_MENU"
            :action="Action::DELETE"
            :iterator="$section->id"
            :route="route('admin.menu.delete', $section)"
            :title="__('admin.action.delete')"
        >
            <p class="admin-confirm">
                {{ __('admin.confirm.delete_section', ['name' => $section->localeIn(Lang::FR)->title]) }}
            </p>

            <x-slot:submit>
                <button type="submit" class="btn btn-danger">
                    {{ __('admin.action.delete') }}
                </button>
            </x-slot:submit>
        </x-modal.index>

        <x-modal.index
            :name="Modal::ADMIN_MENU"
            :action="Action::UPDATE"
            :iterator="$section->id"
            :route="route('admin.menu.update', $section)"
            :title="$section->localeIn(Lang::FR)->title"
        >
            <input type="hidden" name="section_id" value="{{ $section->id }}">

            {{-- French is the carte as the kitchen writes it.
                 Every English field below may be left blank,
                 and blank is not an omission: it hands that
                 one field to the translator and keeps it
                 following the French from then on. Type in it
                 and it is yours, and nothing overwrites it
                 again. --}}
            <p class="admin-hint">{{ __('admin.hint.translation') }}</p>

            <x-forms.input
                name="title_fr"
                :required="true"
                :label="__('admin.field.section_title_fr')"
                :icon="Icon::TITLE"
                :value="$section->localeIn(Lang::FR)->title"
            />

            <x-forms.input
                name="title_en"
                :label="__('admin.field.section_title_en')"
                :icon="Icon::TITLE"
                :value="$section->localeIn(Lang::EN)->title"
            />

            <x-forms.repeater
                :title="__('admin.field.items')"
                :items="$rows[$section->id]"
            >
                <x-forms.input
                    model="item.title_fr"
                    name="section_titles_fr[]"
                    :label="__('admin.field.dish_fr')"
                    :icon="Icon::EDIT"
                    :required="true"
                />

                <x-forms.input
                    model="item.title_en"
                    name="section_titles_en[]"
                    :label="__('admin.field.dish_en')"
                    :icon="Icon::EDIT"
                    :required="false"
                />

                <x-forms.input
                    model="item.description_fr"
                    name="section_descriptions_fr[]"
                    :label="__('admin.field.description_fr')"
                    :icon="Icon::INFO"
                    :required="false"
                />

                <x-forms.input
                    model="item.description_en"
                    name="section_descriptions_en[]"
                    :label="__('admin.field.description_en')"
                    :icon="Icon::INFO"
                    :required="false"
                />

                <x-forms.input
                    type="number"
                    step="0.05"
                    model="item.price"
                    name="section_prices[]"
                    :label="__('admin.field.price')"
                    :icon="Icon::PRICE"
                    :required="true"
                />
            </x-forms.repeater>

            {{-- Queued item removals are flushed just before the
                 form itself is submitted. --}}
            <x-slot:submit>
                <button
                    type="submit"
                    class="btn btn-primary"
                    x-data="repeaterDelete"
                    @click.prevent="submit()"
                >
                    {{ __('admin.action.save') }}
                </button>
            </x-slot:submit>
        </x-modal.index>
    @endforeach

    <x-modal.index
        :name="Modal::ADMIN_MENU"
        :action="Action::CREATE"
        :route="route('admin.menu.create')"
        :title="__('admin.action.add_section')"
    >
        <p class="admin-hint">{{ __('admin.hint.translation') }}</p>

        <x-forms.input
            name="title_fr"
            :required="true"
            :label="__('admin.field.section_title_fr')"
            :icon="Icon::TITLE"
        />

        <x-forms.input
            name="title_en"
            :label="__('admin.field.section_title_en')"
            :icon="Icon::TITLE"
        />
    </x-modal.index>
@endsection
