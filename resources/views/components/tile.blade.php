@props(['href', 'image' => null, 'title'])

<a href="{{ $href }}" class="tile">
    <img class="tile__media" src="{{ url($image) }}" alt="" loading="lazy" decoding="async" />
    <span class="tile__veil"></span>
    <span class="tile__title">{{ $title }}</span>
</a>
