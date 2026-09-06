@extends('layouts.guest')

@section('title', ucfirst(__('nav.restaurant')) . ' — ' . __('app.title'))

@section('content')
    <x-page :image="$page->image" :title="$page->locale?->title ?: ucfirst(__('nav.restaurant'))" :rule="true" embers="quiet">
        {{-- The invitation waits until the page has made its case: the hero
             carries the name and the photograph, and the button sits under
             the paragraph that explains what is being booked. --}}
        @if($page->locale?->content)
            <div class="app-page__intro">
                <div class="container">
                    <p class="prose">{{ $page->locale->content }}</p>

                    <p class="app-page__actions">
                        <a href="https://wa.me/+41786857845" class="btn btn-primary" target="_blank" rel="noopener">
                            {{ __('app.reserve') }}
                        </a>
                    </p>
                </div>
            </div>
        @endif

        <x-gallery.index :gallery="$gallery"/>
    </x-page>
@endsection
