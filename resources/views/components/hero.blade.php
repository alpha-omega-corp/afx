@props([
    'image' => null,
    'title' => null,
    'lead' => null,
    'tall' => false,
    'priority' => false,
    'embers' => false,
    'rule' => false,
])

@php($quiet = $embers === 'quiet')

<header @class(['hero', 'hero--tall' => $tall, 'hero--bare' => ! $image, 'hero--media' => $image, 'hero--rule' => $rule, 'hero--quiet' => $quiet])>
    @if($embers)
        {{-- An auberge is a hearth. The embers rise off the bottom edge and
             burn out before the type, so the page opens with firelight
             rather than a photograph. --}}
        <div class="hero__hearth" aria-hidden="true"></div>
        <canvas class="hero__embers" data-embers="{{ $quiet ? 'quiet' : 'full' }}" aria-hidden="true"></canvas>
        <div class="hero__fade" aria-hidden="true"></div>
    @endif

    <div class="hero__inner">
        <div class="container hero__grid">
            <div class="hero__text">
                @isset($eyebrow)
                    <div class="hero__eyebrow">{{ $eyebrow }}</div>
                @endisset

                <h1 class="hero__title">{{ $title }}</h1>

                @if($lead)
                    <p class="hero__lead">{{ $lead }}</p>
                @endif

                @if(isset($actions))
                    <div class="hero__actions">{{ $actions }}</div>
                @endif
            </div>

            {{-- Framed beside the words rather than stretched behind them:
                 the photographs are small, and a full-bleed cover blew them
                 up past their own resolution. --}}
            @if($image)
                <figure class="hero__frame">
                    <img
                        class="hero__media"
                        src="{{ url($image) }}"
                        alt=""
                        aria-hidden="true"
                        {{ $priority ? 'fetchpriority=high' : 'loading=lazy' }}
                        decoding="async"
                    />
                </figure>
            @endif
        </div>
    </div>
</header>
