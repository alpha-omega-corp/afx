@extends('layouts.guest')

@section('content')
    <x-page :image="$page->image">
        <x-slot:title>
            <h1>{{$page->locale->title}}</h1>

            <div class="booking">
                <a href="https://www.booking.com/hotel/ch/auberge-de-founex.fr.html" >
                    {{__('app.booking')}}
                </a>
            </div>
        </x-slot:title>

        <x-gallery.index
            :gallery="$gallery"
            :description="$page->locale->content"
        />

    </x-page>

@endsection
