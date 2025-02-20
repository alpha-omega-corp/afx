@extends('layouts.guest')

@section('content')
    <x-page :image="$page->image">
        <x-slot:title>
            <h1 class="animation-grow">{{$page->locale->title}}</h1>

            <div class="booking">
                <a href="https://wa.me/+41786857845" >
                    {{__('app.whatsapp')}}
                </a>
            </div>
        </x-slot:title>

        <x-gallery.index
            :gallery="$gallery"
            :description="$page->locale->content"
        />

    </x-page>

@endsection
