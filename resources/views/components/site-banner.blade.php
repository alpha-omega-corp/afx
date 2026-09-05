{{-- Sits above the navigation so it is the first thing read, and scrolls
     away with the page rather than eating a sticky strip of every screen. --}}
<aside class="site-banner" role="status">
    <div class="container site-banner__inner">
        @svg('heroicon-o-exclamation-circle', 'site-banner__icon')

        <p class="site-banner__text">
            <strong class="site-banner__lead">{{ __('app.closed_lead') }}</strong>

            @if($reason = $status->reason())
                <span class="site-banner__reason">{{ $reason }}</span>
            @endif

            @if($reopens = $status->reopensOn())
                <span class="site-banner__reopen">
                    {{ __('app.closed_until', ['date' => $reopens->isoFormat('LL')]) }}
                </span>
            @endif
        </p>
    </div>
</aside>
