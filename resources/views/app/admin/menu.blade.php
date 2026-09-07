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
    <x-admin.panel
        :title="__('admin.menu_title')"
        :description="__('admin.menu_description')"
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
                            @foreach($section->items as $item)
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

                                    <span class="admin-section__item-price">{{ $item->price }}</span>
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
