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
            @if($servedOn)
                <p class="admin-metric__note">{{ Str::ucfirst($servedOn->isoFormat('dddd D MMMM')) }}</p>
            @endif
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
            @if($servedOn)
                <p class="admin-daily__date">{{ Str::ucfirst($servedOn->isoFormat('dddd D MMMM')) }}</p>
            @endif

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
    <x-admin.panel
        :title="__('admin.menu_title')"
        :description="__('admin.menu_description')"
        tone="flat"
    >
        @if($sections->isEmpty())
            <p class="admin-empty">{{ __('admin.empty.menu') }}</p>
        @else
            <ul class="admin-sections" x-data="sort" x-sort.ghost="handle">
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
                    </li>
                @endforeach
            </ul>
        @endif
    </x-admin.panel>

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
