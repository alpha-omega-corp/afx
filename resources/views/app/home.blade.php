@extends('layouts.guest')

@section('content')
    {{-- No photograph here: the type carries the arrival and the three
         cards below are the page's first imagery. --}}
    <x-page
        :title="__('app.title')"
        :lead="$page->locale?->title"
        :tall="true"
        :embers="true"
    >
        <x-slot:actions>
            <a href="https://wa.me/+41786857845" class="btn btn-primary" target="_blank" rel="noopener">
                {{ __('app.reserve') }}
            </a>
            <a href="{{ route(__('route.menu')) }}" class="btn btn-ghost">
                {{ __('app.see_menu') }}
            </a>
        </x-slot:actions>

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

        {{-- The house in its own words, beside its own photograph. Raised off
             the page so it reads as its own chapter. --}}
        <section class="home-lede">
            <div class="container home-lede__grid">
                <h2 class="app-section__title home-lede__title">{{ __('app.welcome') }}</h2>

                <p class="prose home-lede__text">{{ $page->locale?->content }}</p>

                <figure class="home-lede__media">
                    <img src="{{ Vite::image('afx-building.jpg') }}" alt="{{ __('app.title') }}" loading="lazy" decoding="async"/>
                </figure>
            </div>
        </section>

        @if($gallery && $gallery->items->isNotEmpty())
            <section class="app-section home-house">
                <div class="container" data-strip>
                    <div class="strip__head">
                        <h2 class="app-section__title">{{ __('app.our_auberge') }}</h2>

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
                    </div>

                    <ul class="strip__track" data-strip-track>
                        @foreach($gallery->items as $item)
                            <li class="strip__item">
                                <img src="{{ asset($item->image) }}" alt="" loading="lazy" decoding="async"/>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif
    </x-page>
@endsection
