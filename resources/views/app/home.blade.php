@extends('layouts.guest')

@section('content')
    <x-page
        :image="$page->image"
        :title="__('app.title')"
        :lead="$page->locale?->title"
        :tall="true"
    >
        <x-slot:actions>
            <a href="https://wa.me/+41786857845" class="btn btn-primary" target="_blank" rel="noopener">
                {{ __('app.reserve') }}
            </a>
            <a href="{{ route(__('route.menu')) }}" class="btn btn-ghost">
                {{ __('app.see_menu') }}
            </a>
        </x-slot:actions>

        {{-- Intro: the words on the left, the house itself on the right. --}}
        <section class="app-section home-intro">
            <div class="container home-intro__grid">
                <div class="home-intro__text">
                    <p class="prose">{{ $page->locale?->content }}</p>
                </div>

                <div class="home-intro__media">
                    <img src="{{ Vite::image('afx-building.jpg') }}" alt="{{ __('app.title') }}" loading="lazy" decoding="async"/>
                </div>
            </div>
        </section>

        {{-- Three doors into the house: the table, the carte, the rooms. --}}
        <section class="app-section home-doors">
            <div class="container">
                <div class="home-doors__grid">
                    <x-tile :href="route(__('route.restaurant'))" :image="$doors['restaurant']?->image" :title="ucfirst(__('nav.restaurant'))"/>
                    <x-tile :href="route(__('route.menu'))" :image="$doors['menu']?->image" :title="ucfirst(__('nav.menu'))"/>
                    <x-tile :href="route(__('route.hotel'))" :image="$doors['hotel']?->image" :title="ucfirst(__('nav.hotel'))"/>
                </div>
            </div>
        </section>

        @if($gallery && $gallery->items->isNotEmpty())
            <section class="app-section">
                <div class="container" data-strip>
                    <div class="strip__head">
                        <h2 class="app-section__title">{{ __('app.delicacies') }}</h2>

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
