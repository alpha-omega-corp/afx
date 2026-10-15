@extends('layouts.guest')

@section('title', ucfirst(__('nav.hotel')) . ' — ' . __('app.title'))

@section('content')
    <x-page :page="$page" :title="$page?->locale?->title ?: ucfirst(__('nav.hotel'))">
        <x-slot:art><x-illustration.room/></x-slot:art>

        <x-slot:cta>
            <a href="https://wa.me/+41786857845" class="btn btn-primary" target="_blank" rel="noopener">
                {{ __('app.reserve_room') }}
            </a>
        </x-slot:cta>

        <x-gallery.index :gallery="$gallery"/>
    </x-page>
@endsection
