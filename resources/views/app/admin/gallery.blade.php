@extends('layouts.admin')

@section('title', __('admin.gallery_title'))

@section('content')
    {{-- Which gallery, not which page. Three rail entries doing one job
         became one entry and a switch. --}}
    <nav class="admin-switch" aria-label="{{ __('admin.gallery_title') }}">
        @foreach(\App\Enums\Gallery::cases() as $case)
            <a
                href="{{ route('admin.gallery', $case->value) }}"
                @class(['admin-switch__item', 'is-current' => $case === $selected])
                @if($case === $selected) aria-current="page" @endif
            >{{ __('admin.gallery.' . $case->value) }}</a>
        @endforeach
    </nav>

    <x-gallery.manage :gallery="$gallery"/>
@endsection
