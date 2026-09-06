@extends('layouts.guest')

@section('title', ucfirst(__('nav.menu')) . ' — ' . __('app.title'))

@section('content')
    <x-page :image="$page->image" :title="$page->locale?->title ?: ucfirst(__('nav.menu'))">

        @if($sections->isNotEmpty())
            <nav class="menu-anchors" aria-label="{{ ucfirst(__('nav.menu')) }}">
                <div class="container">
                    <ul class="menu-anchors__track">
                        @foreach($sections as $section)
                            <li>
                                <a href="#section-{{ $section->id }}" data-menu-anchor>{{ $section->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </nav>
        @endif

        @if($page->locale?->content)
            <div class="app-page__intro">
                <div class="container">
                    <p class="prose">{{ $page->locale->content }}</p>
                </div>
            </div>
        @endif

        <div class="container menu-list">
            @foreach($sections as $section)
                <section class="menu-section" id="section-{{ $section->id }}">
                    <h2 class="menu-section__title">{{ $section->title }}</h2>

                    <ul class="menu-section__list">
                        @foreach($section->items as $item)
                            <li class="menu-item">
                                <h3 class="menu-item__title">
                                    {{ $item->title }}

                                    {{-- The same dish the home page is showing
                                         today, marked where it lives. --}}
                                    @if($item->daily)
                                        <span class="menu-item__daily">{{ __('app.daily') }}</span>
                                    @endif
                                </h3>

                                <p class="menu-item__price">
                                    <span class="menu-item__currency">CHF</span>{{ number_format($item->price, 2, '.', "'") }}
                                </p>

                                @if(filled($item->description))
                                    <p class="menu-item__description">{{ $item->description }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>
    </x-page>
@endsection
