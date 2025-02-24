@extends('layouts.guest')

@section('content')
    <x-page image="{{$page->image}}" :is-large="true">
        <x-slot:title>
            <h1 class="animation-grow">{{$page->locale->title}}</h1>

            <div class="booking">
                <a href="https://wa.me/+41786857845" >
                    {{__('app.whatsapp')}}
                </a>
            </div>
        </x-slot:title>

        <div class="app-page__description container">
            {{$page->locale->content}}
        </div>

        <div class="home-link container">
            <a href="{{route(__('route.menu'))}}" class="home-link__item">
                @svg('heroicon-s-book-open')
                <span>Menu</span>
            </a>

            <a href="{{route(__('route.hotel'))}}" class="home-link__item">
                @svg('heroicon-s-building-office-2')
                <span>Hotel</span>
            </a>
        </div>


        <x-section :title="__('app.delicacies')">
            <x-carousel name="foods" :count="4">
                @foreach($gallery->items as $item)
                    <li class="glide__slide">
                        <div class="delicacies">
                            <img src="{{asset($item->image)}}" alt=""/>
                        </div>
                    </li>
                @endforeach
            </x-carousel>
        </x-section>

    </x-page>
@endsection

