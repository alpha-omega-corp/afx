@extends('layouts.guest')

@section('content')
    <x-page :image="$page->image">

        <x-slot:title>
            <h1>{{$page->locale->title}}</h1>
        </x-slot:title>

        <div class="app-page__description container">
            {{$page->locale->content}}
        </div>

        @foreach($sections as $section)
            <div class="menu-section">

                <div class="menu-parallax" style="background-image: url({{Vite::image('afx-menu.jpg')}})">
                    <div class="menu-parallax__overlay"></div>
                    <h2 class="menu-section__title">{{$section->title}}</h2>
                </div>

                <div class="container">
                    @foreach($section->items as $item)
                            <div class="menu-section__item">
                                <div>
                                    <h4 class="menu-item__title">{{$item->title}}</h4>
                                    <p class="menu-item__description">{{$item->description}}</p>
                                </div>
                                <div class="menu-item__content">
                                    <span class="menu-item__price">CHF {{$item->price}}</span>
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </x-page>
@endsection
