@props([
    'address' => "Grand'Rue 31, 1297 Founex",
    'destination' => "Auberge de Founex, Grand'Rue 31, 1297 Founex",
])

@php
    $target = rawurlencode($destination);

    // The keyless embed form. The official Embed API wants a billing key for
    // what is, here, one pin on one street.
    $embed = 'https://www.google.com/maps?q=' . $target
        . '&hl=' . app()->getLocale() . '&z=16&output=embed';

    // Works in any browser, and Android hands it straight to the Maps app.
    $google = 'https://www.google.com/maps/dir/?api=1&destination=' . $target;

    // Opens Maps on iOS and macOS. The script swaps the link to this one
    // there, so the default stays the one that works everywhere.
    $apple = 'https://maps.apple.com/?daddr=' . $target . '&dirflg=d';
@endphp

<section class="app-map">
    <div class="app-map__frame">
        {{-- Lazy: a third-party frame is not worth a request until the
             visitor has scrolled far enough to want it. --}}
        <iframe
            src="{{ $embed }}"
            title="{{ __('app.map_title') }}"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen
        ></iframe>
    </div>

    <div class="app-map__bar">
        <p class="app-map__address">{{ $address }}</p>

        <a
            class="btn btn-primary app-map__go"
            href="{{ $google }}"
            data-directions
            data-apple="{{ $apple }}"
            target="_blank"
            rel="noopener"
        >
            @svg('heroicon-o-map-pin')
            {{ __('app.itinerary') }}
        </a>
    </div>
</section>
