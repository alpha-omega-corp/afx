@extends('layouts.guest')

@section('description', Str::limit(trim(strip_tags($page?->locale?->content ?? '')), 155) ?: __('footer.blurb'))

@section('title', ucfirst(__('nav.contact')) . ' — ' . __('app.title'))

@section('content')
    <x-page :page="$page" :title="$page?->locale?->title ?: ucfirst(__('nav.contact'))" emblem="map">
        <x-slot:preview>
            <ul class="page-preview__chips">
                <li>Grand'Rue&nbsp;31, 1297&nbsp;Founex</li>
                <li>022&nbsp;776&nbsp;10&nbsp;29</li>
                <li>aubergedefounex@bluewin.ch</li>
            </ul>
        </x-slot:preview>

        {{-- The home page's visit band, whole: the address, the hours, the
             ways to reach the house, and the map. --}}
        <x-visit/>
    </x-page>
@endsection
