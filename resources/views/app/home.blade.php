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

        <div class="home-link">
            <div class="home-link__item">
                @svg('heroicon-s-book-open')
                <a href="">Menu</a>
            </div>

            <div class="home-link__item">
                @svg('heroicon-s-building-office-2')
                <a href="">Hotel</a>
            </div>

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

