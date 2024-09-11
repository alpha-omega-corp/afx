<div class="app-page">

    <div class="app-page__header">

        <div class="parallax" style="background-image: url({{url($image)}})">
        </div>

        <div class="parallax-overlay"></div>

        <div class="app-page__title">
            <div class="container">
                <h1>{{$title}}</h1>
            </div>
        </div>

    </div>

    <div class="app-page__content">
        {{$slot}}
    </div>
</div>
