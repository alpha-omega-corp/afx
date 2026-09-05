@props(['title' => null, 'id' => null])

<section class="app-section" @if($id) id="{{ $id }}" @endif>
    <div class="container">
        @if($title)
            <h2 class="app-section__title">{{ $title }}</h2>
        @endif

        {{ $slot }}
    </div>
</section>
