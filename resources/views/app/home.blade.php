@extends('layouts.guest')

@section('content')
    {{-- The house itself, under the firelight: the embers rise over the
         photograph, and it drifts at a third of the page's speed as the
         hero scrolls away. The image is the home page's own, changed from
         Administration → Pages like every other hero on the site. --}}
    <x-page
        :image="$page->image"
        :title="__('app.title')"
        :lead="$page->locale?->title"
        :tall="true"
        :embers="true"
        :parallax="true"
    >
        <x-slot:actions>
            <a href="https://wa.me/+41786857845" class="btn btn-primary" target="_blank" rel="noopener">
                {{ __('app.reserve') }}
            </a>
            <a href="{{ route(__('route.menu')) }}" class="btn btn-ghost">
                {{ __('app.see_menu') }}
            </a>
        </x-slot:actions>

        {{-- The one thing on the page that is different tomorrow. It sits in
             the last of the hero's firelight, above the three doors, because
             it is the reason to come today rather than one day. --}}
        <x-daily :special="$special" :sections="$sections"/>

        {{-- The three ways into the house: a table, the carte, a bed.
             This band has no top gap and no seam — the cards climb into the
             hero's firelight, and reveal themselves as they arrive. --}}
        <section class="home-doors">
            <div class="container">
                <div class="home-doors__grid">
                    <x-tile :href="route(__('route.restaurant'))" :image="$doors['restaurant']?->image" :title="ucfirst(__('nav.restaurant'))" :reveal="true"/>
                    <x-tile :href="route(__('route.menu'))" :image="$doors['menu']?->image" :title="ucfirst(__('nav.menu'))" :reveal="true"/>
                    <x-tile :href="route(__('route.hotel'))" :image="$doors['hotel']?->image" :title="ucfirst(__('nav.hotel'))" :reveal="true"/>
                </div>
            </div>
        </section>

        {{-- One chapter, not two: the house in its own words and the look
             around it were the same subject under two headings, and on a
             phone the second title arrived before the first photograph had
             finished being looked at. The words open the band, the
             photographs run under them, and the house itself is the frame
             the strip opens on. Raised off the page, as one movement. --}}
        @php($photos = $gallery?->items ?? collect())

        <section class="home-lede">
            <div class="container" data-strip>
                <h2 class="app-section__title">{{ __('app.welcome') }}</h2>

                <p class="prose home-lede__text">{{ $page->locale?->content }}</p>

                {{-- Directly above the track: beside the heading these read as
                     decoration on the words rather than as controls for the
                     photographs three lines below them. --}}
                @if($photos->isNotEmpty())
                    <div class="strip__nav">
                        <button type="button" class="strip__btn" data-strip-prev>
                            <span class="visually-hidden">{{ __('app.previous') }}</span>
                            <x-icon.chevron-left/>
                        </button>
                        <button type="button" class="strip__btn" data-strip-next>
                            <span class="visually-hidden">{{ __('app.next') }}</span>
                            <x-icon.chevron-right/>
                        </button>
                    </div>
                @endif

                <ul class="strip__track" data-strip-track>
                    <li class="strip__item">
                        <img src="{{ Vite::image('afx-building.jpg') }}" alt="{{ __('app.title') }}" loading="lazy" decoding="async"/>
                    </li>

                    @foreach($photos as $item)
                        <li class="strip__item">
                            <img src="{{ asset($item->image) }}" alt="" loading="lazy" decoding="async"/>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    </x-page>
@endsection
