@extends('layouts.guest')

@section('content')

    <x-page :image="$page->image">

        <x-slot:title>
            <h1>{{$page->locale->title}}</h1>
        </x-slot:title>

        <div class="container text-center p-4">
            {{$page->locale->content}}
        </div>

        <div id="lightgallery">
            @foreach($gallery->items as $item)
                <a href="{{asset($item->image)}}" >
                    <img alt=".." src="{{asset($item->image)}}" />
                </a>
            @endforeach
        </div>


    </x-page>
@endsection

