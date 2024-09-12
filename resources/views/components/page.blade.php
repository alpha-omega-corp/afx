<div class="app-page">

    <div class="app-page__header">

        <div class="parallax" style="background-image: url({{url($image)}})"></div>

        <div class="parallax-overlay"></div>

        <div class="app-page__title">
            <div class="container">
                {{$title}}
            </div>
        </div>
    </div>

    <div class="app-page__content">
        {{$slot}}
    </div>

    <div class="app-page__footer">
        <div class="parallax" style="background-image: url({{url(Vite::image('afx-footer.jpg'))}})"></div>

        <div class="parallax-overlay"></div>
    </div>
</div>
