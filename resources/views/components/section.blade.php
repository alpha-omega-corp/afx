<section class="app-section bg-{{$color}}">
        @if($title)
            <h2 @class(['section-padding' => $padding])>{{$title}}</h2>
        @endif

        <div class="container">
            {{$slot}}
        </div>
</section>
