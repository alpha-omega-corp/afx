{{-- A thin strip over the navigation on every page: open or closed right
     now and until when, and where the house is. It scrolls away with the
     page; the fixed bar under it follows it up (app.js, "Bars above the
     navigation"). --}}
<aside class="top-bar" aria-label="{{ __('app.top_bar') }}">
    <div class="container top-bar__inner">
        <x-open-status/>

        <a class="top-bar__place" href="{{ route(__('route.contact')) }}">
            @svg('heroicon-o-map-pin', 'top-bar__icon')
            <span>Grand'Rue&nbsp;31, 1297&nbsp;Founex</span>
        </a>
    </div>
</aside>
