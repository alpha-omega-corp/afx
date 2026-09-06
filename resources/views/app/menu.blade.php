@extends('layouts.guest')

@section('title', ucfirst(__('nav.menu')) . ' — ' . __('app.title'))

@section('content')
    {{-- The same rule as every other page title. It is also what makes this
         hero measure the same as the restaurant's and the hotel's: the
         lockup is anchored to the bottom edge, so the rule's height is the
         25px that had the title sitting lower here than anywhere else. --}}
    <x-page :image="$page->image" :title="$page->locale?->title ?: ucfirst(__('nav.menu'))" :rule="true" embers="quiet">

        @if($sections->isNotEmpty())
            {{-- Nine sections never fit the rail, on any screen. The rail
                 still scrolls and still fades at whichever end has more
                 beyond it, but a fade only says "there is more that way";
                 the button says how much more and takes you straight to it,
                 which is the only way to reach the last section without
                 dragging past the first eight. --}}
            <nav class="menu-anchors" aria-label="{{ ucfirst(__('nav.menu')) }}">
                <div
                    class="container menu-anchors__inner"
                    x-data="{ open: false }"
                    @keydown.escape.window="open = false"
                    @click.outside="open = false"
                >
                    <ul class="menu-anchors__track">
                        @foreach($sections as $section)
                            <li>
                                <a href="#section-{{ $section->id }}" data-menu-anchor>{{ $section->title }}</a>
                            </li>
                        @endforeach
                    </ul>

                    <button
                        type="button"
                        class="menu-anchors__more"
                        :class="{ 'is-open': open }"
                        @click="open = ! open"
                        :aria-expanded="open ? 'true' : 'false'"
                        aria-controls="menu-anchors-all"
                    >
                        <span class="visually-hidden">{{ __('app.all_sections') }}</span>
                        @svg('heroicon-o-ellipsis-horizontal')
                    </button>

                    <div
                        class="menu-anchors__panel"
                        id="menu-anchors-all"
                        x-show="open"
                        x-cloak
                        x-transition.opacity.duration.150ms
                    >
                        <p class="menu-anchors__panel-title">{{ __('app.all_sections') }}</p>

                        <ul>
                            @foreach($sections as $section)
                                <li>
                                    <a href="#section-{{ $section->id }}" @click="open = false">{{ $section->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </nav>
        @endif

        {{-- The dish of the day heads the carte it belongs to, above the
             words about the carte in general. --}}
        <x-daily :special="$special" :sections="$sections" :tuck="false"/>

        @if($page->locale?->content)
            <div class="app-page__intro">
                <div class="container">
                    <x-prose-more :text="$page->locale->content"/>
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
