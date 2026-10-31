{{-- The site's pages, in order. Drawn twice: as words in the bar, and with
     `drawer` as the phone's sidebar rows — a plate number, the name, an arrow. --}}
@php
    $pages = [
        'home' => Request::is('/') || Request::is('en'),
        'menu' => Request::is('la-carte') || Request::is('en/menu'),
        'restaurant' => Request::is('restaurant') || Request::is('en/restaurant'),
        'hotel' => Request::is('hotel') || Request::is('en/hotel'),
        'contact' => Request::is('contact') || Request::is('en/contact'),
    ];
@endphp

@foreach($pages as $page => $current)
    <li @class(['active-item' => $current]) @if($drawer ?? false) style="--i: {{ $loop->index }}" @endif>
        <a href="{{ route(__("route.$page")) }}" @if($current) aria-current="page" @endif>
            @if($drawer ?? false)
                <span class="app-navigation__index" aria-hidden="true">{{ sprintf('%02d', $loop->iteration) }}</span>
                <span class="app-navigation__label">{{ ucfirst(__("nav.$page")) }}</span>
                <svg class="app-navigation__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            @else
                {{ ucfirst(__("nav.$page")) }}
            @endif
        </a>
    </li>
@endforeach
