@extends('layouts.guest')

@section('description', Str::limit(trim(strip_tags($page?->locale?->content ?? '')), 155) ?: __('footer.blurb'))

@section('title', ucfirst(__('nav.restaurant')) . ' — ' . __('app.title'))

@section('content')
    <x-page :page="$page" :title="$page?->locale?->title ?: ucfirst(__('nav.restaurant'))" emblem="table">

        <x-slot:cta>
            <a href="https://wa.me/+41786857845" class="btn btn-primary" target="_blank" rel="noopener">
                {{ __('app.reserve') }}
            </a>
        </x-slot:cta>

        <x-slot:preview>
            @if($gallery && $gallery->items->isNotEmpty())
                <div class="page-preview__photos">
                    @foreach($gallery->items->take(4) as $item)
                        <img src="{{ asset($item->image) }}" alt="" loading="lazy" decoding="async"/>
                    @endforeach
                </div>
                <p class="page-preview__meta">{{ __('app.preview_photos', ['count' => $gallery->items->count()]) }}</p>
            @endif
        </x-slot:preview>

        <x-gallery.index :gallery="$gallery"/>
    </x-page>
@endsection
