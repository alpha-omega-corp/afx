@props(['gallery', 'description' => null])

@if(filled($description))
    <div class="app-page__intro">
        <div class="container">
            <p class="prose">{{ $description }}</p>
        </div>
    </div>
@endif

@if($gallery && $gallery->items->isNotEmpty())
    <div class="container app-gallery-grid__wrap">
        {{-- Each photograph keeps its own shape. The box is reserved from the
             stored dimensions, so the columns balance correctly on first paint
             and nothing shifts as the files arrive. --}}
        <div class="pswp-gallery app-gallery-grid" id="gallery">
            @foreach($gallery->items as $item)
                <a
                    class="pswp-gallery__item"
                    href="{{ asset($item->image) }}"
                    style="--ratio: {{ $item->ratio() }}"
                    @if($item->isMeasured())
                        data-pswp-width="{{ $item->width }}"
                        data-pswp-height="{{ $item->height }}"
                    @endif
                    target="_blank"
                    rel="noopener"
                >
                    <img
                        src="{{ asset($item->image) }}"
                        @if($item->isMeasured())
                            width="{{ $item->width }}"
                            height="{{ $item->height }}"
                        @endif
                        alt=""
                        loading="lazy"
                        decoding="async"
                    />

                    <span class="pswp-gallery__item--overlay" aria-hidden="true">
                        @svg(Icon::ZOOM->value)
                    </span>
                </a>
            @endforeach
        </div>
    </div>
@endif
