@extends('layouts.admin')

@section('title', __('admin.pages_title'))

@section('content')
    <x-admin.panel :description="__('admin.pages_description')" :padding="false">
        <ul class="admin-page-rows">
            @foreach($pages as $page)
                <x-admin.page-row :page="$page"/>
            @endforeach
        </ul>
    </x-admin.panel>
@endsection
