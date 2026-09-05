@props(['href', 'image' => null, 'title', 'reveal' => false])

<a href="{{ $href }}" class="tile" @if($reveal) data-reveal @endif>
    <img class="tile__media" src="{{ url($image) }}" alt="" loading="lazy" decoding="async" />
    <span class="tile__veil"></span>

    <span class="tile__body">
        <span class="tile__title">{{ $title }}</span>
        <span class="tile__rule"></span>

        <span class="tile__go" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h13M12 5l7 7-7 7"/>
            </svg>
        </span>
    </span>
</a>
