<section class="app-section bg-{{$color}}">
        <h2 @class(['section-padding' => $padding])>{{$title}}</h2>

        <div class="container">
            {{$slot}}
        </div>
</section>
