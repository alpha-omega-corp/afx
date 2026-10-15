@extends('layouts.admin')

@section('title', __('admin.pages_title'))

@section('content')
    {{-- The band that says what this screen is, so the panel that used to
         wrap the list — and add nothing but a border around it — can go. --}}
    <ul class="admin-metrics">
        <li class="admin-metric">
            <p class="admin-metric__label">{{ __('admin.metric.pages') }}</p>
            <p class="admin-metric__value u-tabular">{{ $pages->count() }}</p>
            <p class="admin-metric__note">{{ __('admin.pages_description') }}</p>
        </li>
    </ul>

    {{-- Five cells, two rows: 6 + 6, then 4 + 4 + 4. The home page — the one
         whose image is the hero of the whole site — gets the largest
         thumbnail, and the grid stops reading as a table of equal boxes.
         Below xl a page cell is full width: image, heading, excerpt and an
         action have nothing to do at 340px. --}}
    @foreach($pages as $page)
        <x-admin.page-row :page="$page" :xl="$loop->index < 2 ? 6 : 4"/>
    @endforeach
@endsection
