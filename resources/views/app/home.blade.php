@extends('layouts.guest')

@section('content')
    <x-page image="{{$page->image}}" :is-large="true">

        <x-slot:title>
            <h1>{{$page->locale->title}}</h1>

            <div class="booking">
                <a href="https://wa.me/+41786857845" >
                    {{__('app.booking')}}
                </a>
            </div>
        </x-slot:title>


        <x-section :color="Color::DARK">
            <div class="description">

                <div class="description-image animation-grow">
                    <img class="shadow-lg" src="{{Vite::image('afx-building.png')}}" alt="{{$page->image}}"/>
                </div>

                <div class="d-flex justify-content-center">
                    <p class="app-page__description">{{$page->locale->content}}</p>
                </div>

            </div>
        </x-section>


        <x-section
            :color="Color::LIGHT"
            :title="__('app.delicacies')"
            :padding="true"
        >
            <div class="animation-grow">
                <x-carousel name="foods" :count="4">
                    @foreach($gallery->items as $item)
                        <li class="glide__slide">
                            <div class="delicacies">
                                <img src="{{asset($item->image)}}" alt=""/>
                            </div>
                        </li>
                    @endforeach
                </x-carousel>
            </div>
        </x-section>

    </x-page>
@endsection

