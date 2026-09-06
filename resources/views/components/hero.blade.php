@props([
    'image' => null,
    'title' => null,
    'lead' => null,
    'tall' => false,
    'priority' => false,
    'embers' => false,
    'parallax' => false,
])

<header @class(['hero', 'hero--tall' => $tall, 'hero--bare' => ! $image])>
    @if($image)
        <img
            class="hero__media"
            @if($parallax) data-parallax @endif
            src="{{ url($image) }}"
            alt=""
            aria-hidden="true"
            {{ $priority ? 'fetchpriority=high' : 'loading=lazy' }}
            decoding="async"
        />

        <div class="hero__veil"></div>
    @endif

    @if($embers)
        {{-- An auberge is a hearth. The embers rise off the bottom edge and
             burn out before the type, so the page opens with firelight
             rather than a photograph. --}}
        <div class="hero__hearth" aria-hidden="true"></div>
        <canvas class="hero__embers" data-embers aria-hidden="true"></canvas>
        <div class="hero__fade" aria-hidden="true"></div>
    @endif

    <div class="hero__inner">
        <div class="container">
            <h1 class="hero__title">{{ $title }}</h1>

            @if($lead)
                <p class="hero__lead">{{ $lead }}</p>
            @endif

            @if(isset($actions))
                <div class="hero__actions">{{ $actions }}</div>
            @endif
        </div>
    </div>
</header>
