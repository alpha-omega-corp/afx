<div class="card">
    <div class="card-body">

        <div class="card-title__container">
            <h5 class="card-title">{{ucfirst($title)}}</h5>

            @if(isset($actions))
                <div>
                    {{$actions}}
                </div>
            @endif
        </div>

        <div class="card-content">

            @if($padding)
                <div class="card-content__padding">
                    {{$slot}}
                </div>
            @else
                {{$slot}}
            @endif
        </div>
    </div>
</div>
