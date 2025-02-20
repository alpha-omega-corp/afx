<section class="app-section">
        @if($title)
            <h2 class="app-section__title">{{$title}}</h2>
        @endif

        <div class="app-section__content">
            {{$slot}}
        </div>
</section>
