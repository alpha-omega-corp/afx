@extends('layouts.guest')

@section('title', ucfirst(__('nav.hotel')) . ' — ' . __('app.title'))

@section('content')
    <x-page :image="$page->image" :title="$page->locale?->title ?: ucfirst(__('nav.hotel'))">
        <x-slot:actions>
            <a href="https://wa.me/+41786857845" class="btn btn-primary" target="_blank" rel="noopener">
                {{ __('app.reserve_room') }}
            </a>
        </x-slot:actions>

        <x-gallery.index :gallery="$gallery" :description="$page->locale?->content"/>
    </x-page>
@endsection
