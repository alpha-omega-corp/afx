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
        <div class="pswp-gallery app-gallery-grid" id="gallery" x-data="{
            init() {
                this.$el.querySelectorAll('img').forEach((img) => {
                    const set = () => {
                        img.closest('a')?.setAttribute('data-pswp-width', img.naturalWidth);
                        img.closest('a')?.setAttribute('data-pswp-height', img.naturalHeight);
                    };
                    img.complete ? set() : img.addEventListener('load', set, { once: true });
                });
            }
        }">
            @foreach($gallery->items as $item)
                <a class="pswp-gallery__item" href="{{ asset($item->image) }}" target="_blank" rel="noopener">
                    <img src="{{ asset($item->image) }}" alt="" loading="lazy" decoding="async"/>
                    <span class="pswp-gallery__item--overlay" aria-hidden="true">
                        @svg(Icon::ZOOM->value)
                    </span>
                </a>
            @endforeach
        </div>
    </div>
@endif
