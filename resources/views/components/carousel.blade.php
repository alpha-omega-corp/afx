
<div class="carousel__frame">
    <div class="carousel__section">
        <div id="{{$name}}" x-data="carousel('{{$name}}', '{{$count}}')">
            <div class="container position-relative">

                <!-- Controls -->
                <div class="glide__arrows pointer-events-none" data-glide-el="controls">
                    <!-- Previous -->
                    <button
                        class="glide__arrow glide__arrow--left pointer-events-auto disabled:opacity-50 btn"
                        data-glide-dir="<">
                            <span class="arrow-content" aria-hidden="true">
                                @svg('heroicon-o-chevron-double-left')
                            </span>
                        <span class="sr-only"></span>
                    </button>


                    <!-- Next -->
                    <button
                        class="glide__arrow glide__arrow--right pointer-events-auto disabled:opacity-50 btn"
                        data-glide-dir=">">
                        <span class="arrow-content" aria-hidden="true">
                                @svg('heroicon-o-chevron-double-right')
                        </span>
                    </button>
                </div>

                <!-- Carousel -->
                <div class="glide__">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            {{$slot}}
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        @if(isset($mobile))
            {{$mobile}}
        @endif
    </div>
</div>
