@props([
    'image' => null,
    'title' => null,
    'lead' => null,
    'tall' => false,
    'priority' => false,
])

<header @class(['hero', 'hero--tall' => $tall])>
    @if($image)
        <img
            class="hero__media"
            src="{{ url($image) }}"
            alt=""
            aria-hidden="true"
            {{ $priority ? 'fetchpriority=high' : 'loading=lazy' }}
            decoding="async"
        />
    @endif

    <div class="hero__veil"></div>

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
