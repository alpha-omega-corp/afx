@extends('layouts.admin')

@section('content')
    <x-admin :page="$page">
        <x-card :title="__('app.menu')">
            <div class="admin-menu">
                @foreach($sections as $section)

                    <div class="admin-menu__section shadow-lg">

                        <div class="admin-menu__section-actions">
                            <x-modal.open :name="Modal::ADMIN_MENU" :action="Action::UPDATE" :icon="Icon::EDIT"/>
                        </div>

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
        :action="Action::UPDATE"
        title="menu"
    >

    </x-modal.index>

@endsection
