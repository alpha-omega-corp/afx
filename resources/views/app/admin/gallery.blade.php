@extends('layouts.admin')

@section('title', __('admin.gallery_title'))

@section('content')
    {{-- Which gallery, not which page. Three rail entries doing one job
         became one entry and a switch. The current entry is marked by
         aria-current alone: one attribute carries the state, so the style and
         the announcement can never drift apart. --}}
    <nav class="admin-switch bento-xl-4" aria-label="{{ __('admin.gallery_title') }}">
        @foreach(\App\Enums\Gallery::cases() as $case)
            <a
                href="{{ route('admin.gallery', $case->value) }}"
                class="admin-switch__item"
                @if($case === $selected) aria-current="page" @endif
            >{{ __('admin.gallery.' . $case->value) }}</a>
        @endforeach
    </nav>

    <ul class="admin-metrics bento-xl-8">
        <li class="admin-metric">
            <p class="admin-metric__label">{{ __('admin.metric.photos') }}</p>
            <p class="admin-metric__value u-tabular">{{ $gallery->items->count() }}</p>
            <p class="admin-metric__note">{{ __('admin.gallery.' . $selected->value) }}</p>
        </li>
    </ul>

    <x-gallery.manage :gallery="$gallery"/>
@endsection
