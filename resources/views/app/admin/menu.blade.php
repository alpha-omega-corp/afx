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

                            <h3 class="admin-section__title">{{ $section->title }}</h3>

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
                                <li class="admin-section__item">
                                    <span class="admin-section__item-title">{{ $item->title }}</span>
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
                                {{ __('admin.confirm.delete_section', ['name' => $section->title]) }}
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
                            :title="$section->title"
                        >
                            <input type="hidden" name="section_id" value="{{ $section->id }}">

                            <x-forms.input
                                name="title"
                                :required="true"
                                :label="__('form.title')"
                                :icon="Icon::TITLE"
                                :value="$section->title"
                            />

                            <x-forms.repeater
                                :title="__('admin.field.items')"
                                :items="$section->items->toArray()"
                            >
                                <x-forms.input
                                    model="item.title"
                                    name="section_titles[]"
                                    :label="__('admin.field.dish')"
                                    :icon="Icon::EDIT"
                                />

                                <x-forms.input
                                    model="item.description"
                                    name="section_descriptions[]"
                                    :label="__('admin.field.description')"
                                    :icon="Icon::INFO"
                                    :required="false"
                                />

                                <x-forms.input
                                    type="float"
                                    model="item.price"
                                    name="section_prices[]"
                                    :label="__('admin.field.price')"
                                    :icon="Icon::PRICE"
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
        <x-forms.input
            name="title"
            :required="true"
            :label="__('form.title')"
            :icon="Icon::TITLE"
        />
    </x-modal.index>
@endsection
