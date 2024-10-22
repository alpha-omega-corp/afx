@extends('layouts.admin')

@section('content')
    <x-admin :page="$page">
        <x-card :title="__('app.menu')">

            <x-slot:actions>
                <x-modal.open :name="Modal::ADMIN_MENU" :action="Action::CREATE" :icon="Icon::CREATE"/>
            </x-slot:actions>

            <div class="admin-menu">
                @foreach($sections->sortDesc() as $section)

                    <div class="admin-menu__section shadow-lg">

                        <div class="admin-menu__section-actions">
                            <x-modal.open
                                :name="Modal::ADMIN_MENU"
                                :action="Action::UPDATE"
                                :icon="Icon::EDIT"
                                :iterator="$loop->index"
                            />

                            <x-modal.open
                                :name="Modal::ADMIN_MENU"
                                :action="Action::DELETE"
                                :icon="Icon::DELETE"
                                :iterator="$loop->index"
                            />
                        </div>

                        <x-modal.index
                            :name="Modal::ADMIN_MENU"
                            :action="Action::DELETE"
                            :iterator="$loop->index"
                            :route="route('admin.menu.delete', $section)"
                            title="delete menu section"
                        >
                            <p class="p-4">Delete <b>{{$section->title}}</b> ?</p>
                        </x-modal.index>

                        <x-modal.index
                            :name="Modal::ADMIN_MENU"
                            :action="Action::UPDATE"
                            :iterator="$loop->index"
                            :route="route('admin.menu.update', $section)"
                            title="edit menu section"
                        >
                            <div class="p-4">
                                <input type="hidden" name="section_id" value="{{$section->id}}">

                                <x-forms.input
                                    name="title"
                                    :label="__('form.title')"
                                    :icon="Icon::TITLE"
                                    :value="$section->title"
                                />

                                <x-forms.repeater
                                    :title="__('app.menu-section-item')"
                                    :items="$section->items->toArray()"
                                >
                                    <div class="d-flex gap-3">
                                        <x-forms.input
                                            model="item.title"
                                            name="section_titles[]"
                                            :label="__('app.menu-title')"
                                            :icon="Icon::EDIT"
                                        />

                                        <x-forms.input
                                            type="float"
                                            model="item.price"
                                            name="section_prices[]"
                                            :label="__('app.menu-price')"
                                            :icon="Icon::PRICE"
                                        />
                                    </div>

                                    <x-forms.input
                                        model="item.description"
                                        name="section_descriptions[]"
                                        :label="__('app.menu-description')"
                                        :icon="Icon::INFO"
                                    />

                                </x-forms.repeater>
                            </div>

                            <x-slot:submit>
                                <div x-data="repeaterDelete">
                                    <span @remove.window="remove()"></span>

                                    <button
                                        class="btn btn-success text-white"
                                        type="submit"
                                        @click="$dispatch('remove')">
                                        Submit
                                    </button>
                                </div>

                            </x-slot:submit>

                        </x-modal.index>


                        <h5 class="admin-menu__section-title">
                            {{$section->title}}
                        </h5>

                        <ul>
                            @foreach($section->items as $item)
                                <li>
                                    {{$item->title}}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </x-card>
    </x-admin>


    <x-modal.index
        :name="Modal::ADMIN_MENU"
        :action="Action::CREATE"
        :route="route('admin.menu.create')"
        :title="__('app.menu-create')"
    >

        <div class="p-4">
            <x-forms.input
                name="title"
                :label="__('form.title')"
                :icon="Icon::TITLE"
            />
        </div>

    </x-modal.index>

@endsection
