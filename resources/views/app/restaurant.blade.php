@extends('layouts.guest')

@section('content')

    <x-page :image="$page->image">

        <x-slot:title>
            <h1>{{$page->locale->title}}</h1>
        </x-slot:title>

        <x-gallery.index
            :gallery="$gallery"
            :description="$page->locale->content"
        />

    </x-page>

@endsection

