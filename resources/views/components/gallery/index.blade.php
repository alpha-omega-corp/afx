

<div class="pswp-gallery" id="gallery">
    @foreach($gallery->items as $item)
        <a href="{{$item->image}}"
           data-pswp-width="1200"
           data-pswp-height="600"
           target="_blank">
            <img src="{{$item->image}}" alt="" />
        </a>
    @endforeach
</div>

<div class="pswp-gallery" id="galleryw">
    <div class="masonry">
        @foreach($gallery->items as $item)
            <div class="pswp-gallery-item">
                <a id="galleryItem{{$loop->index}}"
                   class="pswp-gallery-item__content"
                   href="{{$item->image}}"
                   target="_blank">

                    <div class="pswp-gallery-item__content--overlay">
                        @svg('heroicon-o-magnifying-glass-circle')
                    </div>

                    <img class="pswp-gallery-image"
                         id="galleryImage{{$loop->index}}"
                         alt=""
                         src="{{asset($item->image)}}"
                         data-pswp-height="1000"
                    />
                </a>
            </div>

        @endforeach
    </div>
</div>

<script type="module">
    const items = document.getElementsByClassName('pswp-gallery-item');

    for (let i = 0; i < items.length; i++) {
        const image = document.getElementById(`galleryImage${i}`)

        image.onload = function() {
            const item = document.getElementById(`galleryItem${i}`)

            if (image.naturalHeight < 600 && image.naturalWidth < 1200) {
                image.classList.add('pswp-gallery-item__content--small')
            } else if (image.naturalHeight > 1000 && image.naturalHeight < 1400) {
                image.classList.add('pswp-gallery-item__content--medium')
            } else if (image.naturalHeight > 1400) {
                image.classList.add('pswp-gallery-item__content--large')
            }

        }

    }


</script>
