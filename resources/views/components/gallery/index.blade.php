<div class="app-page__description container">
    {{$description}}
</div>

<div class="pswp-gallery" id="gallery" x-data="gallery('{{count($gallery->items)}}')">
    @foreach($gallery->items as $item)
        <div class="position-relative">
            <a class="pswp-gallery__item" href="{{$item->image}}" target="_blank">

                <div class="pswp-gallery__item--overlay">
                    @svg(Icon::ZOOM->value)
                </div>

                <img id="galleryImage{{$loop->index}}" class="pswp-gallery__image" src="{{$item->image}}" alt="" />
            </a>
        </div>


    @endforeach
</div>

